<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AthleteProfile;
use App\Models\Team;
use App\Models\TeamMember;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{
    /** Listar equipes (minhas + descoberta). */
    public function index(Request $request)
    {
        $user = $request->user();
        $profile = $user->athleteProfile;

        $myTeams = collect();
        if ($profile) {
            $myTeams = Team::query()
                ->whereHas('members', fn ($q) => $q->where('athlete_id', $profile->id))
                ->with('owner:id,name')
                ->withCount('members')
                ->get();
        }

        $discover = Team::query()
            ->with('owner:id,name')
            ->withCount('members')
            ->latest()
            ->limit(15)
            ->get();

        return response()->json([
            'my_teams' => $myTeams,
            'discover' => $discover,
        ]);
    }

    /** Ver uma equipe (página da equipe). */
    public function show(Request $request, Team $team)
    {
        $team->load(['owner:id,name', 'members.user:id,name']);
        $team->loadCount('members');

        $profile = $request->user()->athleteProfile;
        $isMember = $profile && $team->members()->where('athlete_id', $profile->id)->exists();
        $isOwner = $team->owner_id === $request->user()->id;

        return response()->json([
            'team' => $team,
            'is_member' => $isMember,
            'is_owner' => $isOwner,
        ]);
    }

    /** Criar equipe (apenas organizadores). */
    public function store(Request $request)
    {
        if ($request->user()->role !== 'organizer') {
            return response()->json(['message' => 'Apenas organizadores podem criar equipes.'], 403);
        }

        $data = $request->validate([
            'name' => ['required', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        $slug = Str::slug($data['name']);
        $base = $slug;
        $i = 0;
        while (Team::where('slug', $slug)->exists()) {
            $slug = $base . '-' . (++$i);
        }

        $team = Team::create([
            'name' => $data['name'],
            'slug' => $slug,
            'description' => $data['description'] ?? null,
            'owner_id' => $request->user()->id,
        ]);

        $profile = $request->user()->athleteProfile;
        if ($profile) {
            TeamMember::create([
                'team_id' => $team->id,
                'athlete_id' => $profile->id,
                'role' => TeamMember::ROLE_ADMIN,
            ]);
        }

        return response()->json($team->load('owner:id,name')->loadCount('members'), 201);
    }

    /** Atualizar equipe (só dono ou admin). */
    public function update(Request $request, Team $team)
    {
        $this->authorizeTeamAdmin($request, $team);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:120'],
            'description' => ['nullable', 'string', 'max:2000'],
        ]);

        if (isset($data['name'])) {
            $team->name = $data['name'];
            $team->slug = Str::slug($data['name']);
        }
        if (array_key_exists('description', $data)) {
            $team->description = $data['description'];
        }
        $team->save();

        return response()->json($team->fresh(['owner:id,name'])->loadCount('members'));
    }

    /** Entrar na equipe (qualquer atleta). */
    public function join(Request $request, Team $team)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json(['message' => 'Complete seu perfil de atleta para entrar em equipes.'], 422);
        }

        if ($team->members()->where('athlete_id', $profile->id)->exists()) {
            return response()->json(['message' => 'Você já é membro desta equipe.'], 422);
        }

        TeamMember::create([
            'team_id' => $team->id,
            'athlete_id' => $profile->id,
            'role' => TeamMember::ROLE_MEMBER,
        ]);

        return response()->json(['message' => 'Você entrou na equipe!', 'team' => $team->fresh()->loadCount('members')]);
    }

    /** Sair da equipe. */
    public function leave(Request $request, Team $team)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json([], 204);
        }

        if ($team->owner_id === $request->user()->id) {
            return response()->json(['message' => 'O dono da equipe não pode sair. Transfira a equipe ou exclua-a.'], 422);
        }

        TeamMember::query()
            ->where('team_id', $team->id)
            ->where('athlete_id', $profile->id)
            ->delete();

        return response()->json(['message' => 'Você saiu da equipe.']);
    }

    /** Remover membro (só dono/admin). */
    public function removeMember(Request $request, Team $team, AthleteProfile $athlete)
    {
        $this->authorizeTeamAdmin($request, $team);

        TeamMember::query()
            ->where('team_id', $team->id)
            ->where('athlete_id', $athlete->id)
            ->delete();

        return response()->json(['message' => 'Membro removido.']);
    }

    private function authorizeTeamAdmin(Request $request, Team $team): void
    {
        if ($team->owner_id === $request->user()->id) {
            return;
        }
        $profile = $request->user()->athleteProfile;
        $member = $profile ? TeamMember::where('team_id', $team->id)->where('athlete_id', $profile->id)->first() : null;
        if ($member && $member->role === TeamMember::ROLE_ADMIN) {
            return;
        }
        abort(403, 'Apenas o dono ou um admin da equipe pode fazer isso.');
    }
}
