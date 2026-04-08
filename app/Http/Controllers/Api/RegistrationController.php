<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\AthleteProfile;
use App\Models\Category;
use App\Models\Registration;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class RegistrationController extends Controller
{
    public function indexMyRegistrations(Request $request)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json([]);
        }

        $registrations = Registration::query()
            ->with(['category.event'])
            ->where('athlete_id', $profile->id)
            ->latest()
            ->get();

        return response()->json($registrations);
    }

    /** Organizador: lista inscrições de uma categoria (para marcar pagamento). */
    public function indexByCategory(Request $request, Category $category)
    {
        abort_unless($category->event->organizer_id === $request->user()->id, 403, 'Apenas o organizador do evento pode ver inscricoes.');

        $registrations = Registration::query()
            ->with(['athlete.user'])
            ->where('category_id', $category->id)
            ->orderBy('id')
            ->get();

        return response()->json($registrations);
    }

    /** Organizador: atualiza status de pagamento da inscrição. */
    public function updatePaymentStatus(Request $request, Registration $registration)
    {
        abort_unless($registration->category->event->organizer_id === $request->user()->id, 403, 'Apenas o organizador pode alterar o pagamento.');

        $data = $request->validate([
            'payment_status' => ['required', Rule::in([Registration::PAYMENT_PENDING, Registration::PAYMENT_PAID])],
        ]);

        $registration->update($data);

        return response()->json($registration->fresh(['athlete.user', 'category.event']));
    }

    public function showAthleteProfile(Request $request)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json(['message' => 'Athlete profile not found.'], 404);
        }

        return response()->json($profile);
    }

    /**
     * Dashboard do atleta: perfil + resumo (títulos, inscrições, histórico).
     * Retorna profile null se o cadastro não foi finalizado.
     */
    public function athleteDashboard(Request $request)
    {
        $user = $request->user();
        $profile = $user->athleteProfile;

        $payload = [
            'completed' => (bool) $profile,
            'profile' => $profile,
            'user_name' => $user->name,
        ];

        if ($profile) {
            $payload['championships_won'] = $profile->championshipsWon();
            $payload['followers_count'] = $profile->followers()->count();
            $payload['following_count'] = $profile->following()->count();
            $payload['posts_count'] = $profile->posts()->count();
            $payload['my_posts'] = $profile->posts()->with('event:id,name,date')->latest()->limit(30)->get()->map(fn ($p) => [
                'id' => $p->id,
                'media_url' => $p->media_url,
                'media_type' => $p->media_type,
                'caption' => $p->caption,
                'event' => $p->event ? ['id' => $p->event->id, 'name' => $p->event->name, 'date' => $p->event->date?->toDateString()] : null,
                'created_at' => $p->created_at?->toIso8601String(),
            ]);
            $payload['my_teams'] = $profile->teams()->with('owner:id,name')->withCount('members')->get();
            $registrations = Registration::query()
                ->with(['category.event'])
                ->where('athlete_id', $profile->id)
                ->latest()
                ->get();
            $payload['my_registrations'] = $registrations;
            $payload['my_registrations_count'] = $registrations->count();
            // Histórico: eventos em que participou (inscrito ou teve partida)
            $eventIds = $registrations->pluck('category.event_id')->unique()->values();
            $payload['history_events'] = \App\Models\Event::query()
                ->whereIn('id', $eventIds)
                ->orderByDesc('date')
                ->get();
        }

        return response()->json($payload);
    }

    public function storeAthleteProfile(Request $request)
    {
        if ($request->user()->athleteProfile) {
            return response()->json(['message' => 'Athlete profile already exists.'], 422);
        }

        $data = $this->validateProfile($request);
        $data['user_id'] = $request->user()->id;

        $profile = AthleteProfile::create($data);

        return response()->json($profile, 201);
    }

    public function updateAthleteProfile(Request $request)
    {
        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json(['message' => 'Athlete profile not found.'], 404);
        }

        $data = $this->validateProfile($request, true);
        $profile->update($data);

        return response()->json($profile);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
        ]);

        $profile = $request->user()->athleteProfile;
        if (!$profile) {
            return response()->json(['message' => 'Complete seu perfil antes de se inscrever.'], 422);
        }

        $category = Category::with(['event', 'registrations'])->findOrFail($data['category_id']);
        if ($category->event->status !== 'OPEN') {
            return response()->json(['message' => 'Inscricoes fechadas para este evento.'], 422);
        }

        $this->validateAthleteAgainstCategory($profile, $category);

        if ($category->registrations()->where('athlete_id', $profile->id)->exists()) {
            return response()->json(['message' => 'Voce ja esta inscrito nesta categoria.'], 422);
        }

        if ($category->registrations()->count() >= $category->max_participants) {
            return response()->json(['message' => 'Categoria lotada.'], 422);
        }

        $registration = Registration::create([
            'athlete_id' => $profile->id,
            'category_id' => $category->id,
            'payment_status' => Registration::PAYMENT_PENDING,
        ]);

        return response()->json($registration, 201);
    }

    private function validateProfile(Request $request, bool $partial = false): array
    {
        $required = $partial ? 'sometimes' : 'required';

        return $request->validate([
            'birth_date' => [$required, 'date', 'before:today'],
            'weight' => [$required, 'numeric', 'min:1'],
            'belt' => [$required, 'string', 'max:50'],
            'academy' => [$required, 'string', 'max:255'],
            'gender' => [$required, Rule::in(['MALE', 'FEMALE'])],
            'photo_url' => ['nullable', 'string', 'url', 'max:500'],
        ]);
    }

    private function validateAthleteAgainstCategory(AthleteProfile $profile, Category $category): void
    {
        $categoryBelt = strtoupper(trim($category->belt ?? ''));
        if ($categoryBelt !== 'TODAS' && $categoryBelt !== 'ALL' && strcasecmp($profile->belt, $category->belt) !== 0) {
            abort(422, 'Faixa do atleta nao confere com a faixa da categoria.');
        }

        if ($profile->weight < $category->weight_min || $profile->weight > $category->weight_max) {
            abort(422, 'Athlete weight does not match category weight range.');
        }

        $ageAtEvent = Carbon::parse($profile->birth_date)->age;
        if ($category->event->date) {
            $ageAtEvent = Carbon::parse($profile->birth_date)->diffInYears(Carbon::parse($category->event->date));
        }
        if ($ageAtEvent < $category->age_min || $ageAtEvent > $category->age_max) {
            abort(422, 'Athlete age does not match category age range.');
        }

        if ($category->gender !== 'MIXED' && $profile->gender !== $category->gender) {
            abort(422, 'Athlete gender does not match category gender.');
        }
    }
}
