<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Registration;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class EventController extends Controller
{
    public function highlights()
    {
        $events = Event::query()
            ->whereIn('status', [Event::STATUS_OPEN, Event::STATUS_STARTED])
            ->whereNotNull('banner_url')
            ->latest()
            ->limit(6)
            ->get([
                'id',
                'name',
                'date',
                'location',
                'sport_type',
                'status',
                'banner_url',
            ]);

        return response()->json($events);
    }

    public function index(Request $request)
    {
        $user = $request->user();
        $query = Event::query()->latest();

        if ($user->role === 'organizer') {
            $query->where('organizer_id', $user->id);
        } else {
            $query->whereIn('status', [Event::STATUS_OPEN, Event::STATUS_STARTED]);
        }

        return response()->json($query->get());
    }

    /** Dashboard do organizador: eventos com totais de inscrições e status de pagamento. */
    public function organizerDashboard(Request $request)
    {
        $user = $request->user();
        abort_unless($user->role === 'organizer', 403, 'Apenas organizadores.');

        $events = Event::query()
            ->where('organizer_id', $user->id)
            ->with(['categories' => function ($q) {
                $q->withCount('registrations')
                    ->withCount(['registrations as pending_count' => fn ($q2) => $q2->where('payment_status', Registration::PAYMENT_PENDING)])
                    ->withCount(['registrations as paid_count' => fn ($q2) => $q2->where('payment_status', Registration::PAYMENT_PAID)]);
            }])
            ->latest()
            ->get();

        $totalRegistrations = 0;
        $totalPending = 0;
        $totalPaid = 0;
        $eventsPayload = $events->map(function (Event $event) use (&$totalRegistrations, &$totalPending, &$totalPaid) {
            $regCount = $event->categories->sum('registrations_count');
            $pending = $event->categories->sum('pending_count');
            $paid = $event->categories->sum('paid_count');
            $totalRegistrations += $regCount;
            $totalPending += $pending;
            $totalPaid += $paid;
            return [
                'id' => $event->id,
                'name' => $event->name,
                'date' => $event->date?->toDateString(),
                'status' => $event->status,
                'location' => $event->location,
                'sport_type' => $event->sport_type,
                'banner_url' => $event->banner_url,
                'registrations_count' => $regCount,
                'pending_count' => $pending,
                'paid_count' => $paid,
                'categories_count' => $event->categories->count(),
                'categories' => $event->categories->map(fn ($c) => [
                    'id' => $c->id,
                    'belt' => $c->belt,
                    'gender' => $c->gender,
                    'registrations_count' => $c->registrations_count ?? 0,
                    'pending_count' => $c->pending_count ?? 0,
                    'paid_count' => $c->paid_count ?? 0,
                    'max_participants' => $c->max_participants,
                ])->values()->all(),
            ];
        });

        return response()->json([
            'total_events' => $events->count(),
            'total_registrations' => $totalRegistrations,
            'total_pending' => $totalPending,
            'total_paid' => $totalPaid,
            'events' => $eventsPayload,
        ]);
    }

    /**
     * Torneios abertos para o atleta: eventos com categorias e flag "compatible"
     * por perfil (peso, idade, gênero, faixa). Usado na página Torneios com recomendados.
     */
    public function indexForAthlete(Request $request)
    {
        $events = Event::query()
            ->with('categories')
            ->whereIn('status', [Event::STATUS_OPEN, Event::STATUS_STARTED])
            ->orderBy('date')
            ->get();

        $profile = $request->user()->athleteProfile;
        $athleteAge = $profile ? Carbon::parse($profile->birth_date)->age : null;
        $athleteWeight = $profile ? (float) $profile->weight : null;
        $athleteGender = $profile ? $profile->gender : null;
        $athleteBelt = $profile ? trim(strtoupper((string) $profile->belt)) : null;

        $events->each(function (Event $event) use ($athleteAge, $athleteWeight, $athleteGender, $athleteBelt) {
            $event->categories->each(function ($category) use ($athleteAge, $athleteWeight, $athleteGender, $athleteBelt) {
                $category->compatible = $this->categoryCompatibleWithAthlete(
                    $category,
                    $athleteAge,
                    $athleteWeight,
                    $athleteGender,
                    $athleteBelt
                );
            });
            $event->compatible_categories_count = $event->categories->where('compatible', true)->count();
            $event->setRelation('categories', $event->categories);
        });

        return response()->json($events);
    }

    private function categoryCompatibleWithAthlete($category, ?int $age, ?float $weight, ?string $gender, ?string $belt): bool
    {
        if ($age === null && $weight === null && $gender === null && $belt === null) {
            return false;
        }

        if ($age !== null && ($age < (int) $category->age_min || $age > (int) $category->age_max)) {
            return false;
        }
        if ($weight !== null && ($weight < (float) $category->weight_min || $weight > (float) $category->weight_max)) {
            return false;
        }
        if ($gender !== null && $category->gender !== 'MIXED' && $category->gender !== $gender) {
            return false;
        }
        if ($belt !== null && trim(strtoupper((string) $category->belt)) !== $belt) {
            return false;
        }

        return true;
    }

    public function show(Request $request, Event $event)
    {
        $user = $request->user();
        if ($user->role !== 'organizer' || $event->organizer_id !== $user->id) {
            abort_unless(
                in_array($event->status, [Event::STATUS_OPEN, Event::STATUS_STARTED], true),
                403,
                'Evento nao disponivel.'
            );
        }

        return response()->json($event->load('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'athlete_info' => ['nullable', 'string', 'max:5000'],
            'date' => ['required', 'date'],
            'starts_at' => ['nullable', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'banner_url' => ['nullable', 'url', 'max:2048'],
            'sport_type' => ['required', Rule::in(['BJJ', 'JUDO'])],
            'registration_deadline' => ['required', 'date'],
            'status' => ['nullable', Rule::in([
                Event::STATUS_DRAFT,
                Event::STATUS_OPEN,
                Event::STATUS_CLOSED,
                Event::STATUS_STARTED,
                Event::STATUS_FINISHED,
            ])],
        ]);

        $data['organizer_id'] = $request->user()->id;
        $data['status'] = $data['status'] ?? Event::STATUS_DRAFT;

        $event = Event::create($data);

        return response()->json($event, 201);
    }

    /**
     * Atletas: criar evento de campeonato no programa Fight Company Kids (fica como rascunho).
     */
    public function storeFightCompanyKids(Request $request)
    {
        abort_unless($request->user()->role === User::ROLE_ATHLETE, 403, 'Disponível apenas para contas de atleta.');

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'date' => ['required', 'date'],
            'starts_at' => ['nullable', 'date'],
            'location' => ['required', 'string', 'max:255'],
            'sport_type' => ['required', Rule::in(['BJJ', 'JUDO'])],
            'registration_deadline' => ['required', 'date'],
        ]);

        $data['organizer_id'] = $request->user()->id;
        $data['status'] = Event::STATUS_DRAFT;
        $data['name'] = 'Fight Company Kids — '.$data['name'];

        $event = Event::create($data);

        return response()->json($event, 201);
    }

    public function update(Request $request, Event $event)
    {
        $this->assertOrganizerOwnsEvent($request, $event);

        $data = $request->validate([
            'name' => ['sometimes', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'athlete_info' => ['nullable', 'string', 'max:5000'],
            'date' => ['sometimes', 'date'],
            'starts_at' => ['nullable', 'date'],
            'location' => ['sometimes', 'string', 'max:255'],
            'banner_url' => ['nullable', 'url', 'max:2048'],
            'sport_type' => ['sometimes', Rule::in(['BJJ', 'JUDO'])],
            'registration_deadline' => ['sometimes', 'date'],
            'status' => ['sometimes', Rule::in([
                Event::STATUS_DRAFT,
                Event::STATUS_OPEN,
                Event::STATUS_CLOSED,
                Event::STATUS_STARTED,
                Event::STATUS_FINISHED,
            ])],
        ]);

        $event->update($data);

        return response()->json($event);
    }

    public function destroy(Request $request, Event $event)
    {
        $this->assertOrganizerOwnsEvent($request, $event);
        $event->delete();

        return response()->json([], 204);
    }

    public function openRegistration(Request $request, Event $event)
    {
        $this->assertOrganizerOwnsEvent($request, $event);
        $event->update(['status' => Event::STATUS_OPEN]);

        return response()->json($event);
    }

    public function closeRegistration(Request $request, Event $event)
    {
        $this->assertOrganizerOwnsEvent($request, $event);
        $event->update(['status' => Event::STATUS_CLOSED]);

        return response()->json($event);
    }

    public function finalize(Request $request, Event $event)
    {
        $this->assertOrganizerOwnsEvent($request, $event);
        $event->update(['status' => Event::STATUS_FINISHED]);

        return response()->json($event);
    }

    private function assertOrganizerOwnsEvent(Request $request, Event $event): void
    {
        abort_unless($event->organizer_id === $request->user()->id, 403, 'You can only manage your own events.');
    }
}
