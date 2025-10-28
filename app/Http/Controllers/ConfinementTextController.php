<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\DifficultyEnum;
use App\Models\Block;
use App\Models\Confinement;
use App\Models\ConfinementRequirement;
use App\Models\ConfinementText;
use App\Models\MatrixDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Database\QueryException;

class ConfinementTextController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $confinementTexts = ConfinementText::with(['confinement', 'block'])->get();
        return response()->json($confinementTexts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'confinement_id' => 'required|exists:confinements,id',
            'block_id' => 'required|exists:blocks,id',
            'texts_to_do' => 'required|integer|min:0',
            'questions_per_text' => 'required|integer|min:0',
        ]);

        $block = Block::find($validated['block_id']);

        if (!$block->has_text)
            return response()->json(['error' => 'El bloque seleccionado no puede tener textos.'], 422);

        $current_n_questions = ConfinementText::where('confinement_id', $validated['confinement_id'])
            ->where('block_id', $validated['block_id'])
            ->selectRaw('COALESCE(SUM(texts_to_do * questions_per_text), 0) as total')
            ->value('total');

        $confinement_block_req = ConfinementRequirement::where('confinement_id', $validated['confinement_id'])
            ->where('block_id', $validated['block_id'])
            ->whereNull('difficulty')
            ->first();

        if (!$confinement_block_req) {
            return response()->json(['error' => 'El bloque seleccionado no tiene un requerimiento asociado en este internamiento.'], 422);
        }

        $new_n_questions = $validated['texts_to_do'] * $validated['questions_per_text'];

        if ($current_n_questions + $new_n_questions > $confinement_block_req->n_questions) {
            return response()->json(['error' => 'El total de preguntas por textos excede el requerimiento del bloque en este internamiento.'], 422);
        }

        try {
            $confinementText = ConfinementText::create($validated);
        } catch (QueryException $e) {
            if ($e->getCode() === '23505') {
                return response()->json(['error' => 'Requerimiento de Texto duplicado.'], 409);
            }
            throw $e;
        }

        return response()->json($confinementText, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ConfinementText $confinementText)
    {
        $confinementText->load(['confinement', 'block']);
        return response()->json($confinementText);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConfinementText $confinementText)
    {
        $validated = $request->validate([
            'texts_to_do' => 'required|integer|min:0',
            'questions_per_text' => 'sometimes|integer|min:0',
        ]);

        $current_n_questions = ConfinementText::where('id', '!=', $confinementText->id)
            ->where('confinement_id', $confinementText->confinement_id)
            ->where('block_id', $confinementText->block_id)
            ->selectRaw('COALESCE(SUM(texts_to_do * questions_per_text), 0) as total')
            ->value('total');

        $confinement_block_req = ConfinementRequirement::where('confinement_id', $validated['confinement_id'])
            ->where('block_id', $validated['block_id'])
            ->whereNull('difficulty')
            ->first();

        if (!$confinement_block_req)
            return response()->json(['error' => 'El bloque seleccionado no tiene un requerimiento asociado en este internamiento.'], 422);

        $new_n_questions = $validated['texts_to_do'] * $validated['questions_per_text'];

        if ($current_n_questions + $new_n_questions > $confinement_block_req->n_questions)
            return response()->json(['error' => 'El total de preguntas por textos excede el requerimiento del bloque en este internamiento.'], 422);

        $confinementText->update($validated);

        return response()->json($confinementText);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConfinementText $confinementText)
    {
        $confinementText->delete();
        return response()->json(null, 204);
    }

    public function byConfinement($confinementId)
    {
        $confinementTexts = ConfinementText::with(['confinement', 'block'])
            ->where('confinement_id', $confinementId)
            ->get();

        return response()->json($confinementTexts);
    }
}
