<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class CategoryController extends Controller
{
    public function indexByEvent(Request $request, Event $event)
    {
        $user = $request->user();
        $isOwner = $event->organizer_id === $user->id;
        if (!$isOwner && !in_array($event->status, [\App\Models\Event::STATUS_OPEN, \App\Models\Event::STATUS_STARTED], true)) {
            abort(403, 'Evento nao disponivel.');
        }
        return response()->json($event->categories()->latest()->get());
    }

    public function store(Request $request, Event $event)
    {
        try {
            abort_unless($event->organizer_id === $request->user()->id, 403, 'You can only manage your own events.');

            $data = $request->validate([
                'belt' => ['required', 'string', 'max:50'],
                'weight_min' => ['required', 'numeric', 'min:0'],
                'weight_max' => ['required', 'numeric', 'min:0'],
                'age_min' => ['required', 'integer', 'min:1'],
                'age_max' => ['required', 'integer', 'min:0'],
                'gender' => ['required', Rule::in(['MALE', 'FEMALE', 'MIXED'])],
                'max_participants' => ['required', 'integer', 'min:2'],
            ]);

            if ($data['weight_max'] < $data['weight_min']) {
                return response()->json(['message' => 'Peso maximo deve ser maior ou igual ao peso minimo.'], 422);
            }
            if ($data['age_max'] < $data['age_min']) {
                return response()->json(['message' => 'Idade maxima deve ser maior ou igual a idade minima.'], 422);
            }

            $data['event_id'] = $event->id;
            $data['bracket_generated'] = false;

            $category = Category::create($data);

            return response()->json($category, 201);
        } catch (\Throwable $e) {
            \Log::error('CategoryController::store', [
                'message' => $e->getMessage(),
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);
            return response()->json([
                'message' => 'Erro ao criar categoria.',
                'error' => config('app.debug') ? $e->getMessage() : null,
                'file' => config('app.debug') ? $e->getFile() . ':' . $e->getLine() : null,
            ], 500);
        }
    }

    public function update(Request $request, Category $category)
    {
        abort_unless($category->event->organizer_id === $request->user()->id, 403, 'You can only manage your own categories.');

        $data = $request->validate([
            'belt' => ['sometimes', 'string', 'max:50'],
            'weight_min' => ['sometimes', 'numeric', 'min:0'],
            'weight_max' => ['sometimes', 'numeric'],
            'age_min' => ['sometimes', 'integer', 'min:1'],
            'age_max' => ['sometimes', 'integer'],
            'gender' => ['sometimes', Rule::in(['MALE', 'FEMALE', 'MIXED'])],
            'max_participants' => ['sometimes', 'integer', 'min:2'],
            'bracket_generated' => ['sometimes', 'boolean'],
        ]);

        if (isset($data['weight_min']) && isset($data['weight_max']) && $data['weight_max'] < $data['weight_min']) {
            return response()->json(['message' => 'weight_max must be greater than or equal to weight_min.'], 422);
        }

        if (isset($data['age_min']) && isset($data['age_max']) && $data['age_max'] < $data['age_min']) {
            return response()->json(['message' => 'age_max must be greater than or equal to age_min.'], 422);
        }

        $category->update($data);

        return response()->json($category);
    }

    public function destroy(Request $request, Category $category)
    {
        abort_unless($category->event->organizer_id === $request->user()->id, 403, 'You can only manage your own categories.');
        $category->delete();

        return response()->json([], 204);
    }
}
