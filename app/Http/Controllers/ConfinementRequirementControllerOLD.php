<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\DifficultyEnum;
use App\Models\Block;
use App\Models\ConfinementRequirement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;
use Illuminate\Database\QueryException;

class ConfinementRequirementControllerOLD extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $confinementRequirements = ConfinementRequirement::with(['confinement', 'block'])->get();
        return response()->json($confinementRequirements);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'confinement_id' => 'required|exists:confinements,id',
            'parent_requirement_id' => 'required|exists:confinement_requirements,id',
            'block_id' => 'required|exists:blocks,id',
            'difficulty' => ['nullable', Rule::enum(DifficultyEnum::class)],
            'questions_to_do' => 'required|integer|min:0',
        ]);

        $block = Block::findOrFail($validated['block_id']);
        $parent_requirement = ConfinementRequirement::find($validated['parent_requirement_id']);
        if ($parent_requirement->difficulty !== null && $validated['difficulty'] !== $parent_requirement->difficulty->value) {
            return response()->json(['error' => 'El requerimiento no puede ser añadido, ya que no tiene la misma dificultad del requerimiento superior'], 422);
        }

        if (!$parent_requirement || $parent_requirement->confinement_id !== $validated['confinement_id']) {
            return response()->json(['error' => 'El requerimiento no puede ser añadido, ya que el requerimiento superior no esta definido'], 422);
        }

        if ($block->parent_block_id !== $parent_requirement->block_id && $block->id !== $parent_requirement->block_id) {
            return response()->json(['error' => 'El requerimiento no puede ser añadido, ya que el requerimiento superior no coincide con el bloque del requerimiento'], 422);
        }

        $divided_by_difficulty = ConfinementRequirement::where('parent_id', $parent_requirement->id)
            ->where('block_id', $parent_requirement->block_id)
            ->whereNotNull('difficulty')
            ->exists();

        if ($divided_by_difficulty && $block->id !== $parent_requirement->block_id) {
            return response()->json(['error' => 'El requerimiento no puede ser añadido, ya que el requerimiento superior está dividido por dificultad'], 422);
        }

        $questions_required = ConfinementRequirement::where('parent_id', $parent_requirement->id)->sum('questions_to_do');

        if ($validated['questions_to_do'] + $questions_required > $parent_requirement->questions_to_do) {
            return response()->json(['error' => 'Sobrepasa el número de preguntas del requerimiento en el bloque superior'], 422);
        }

        $validated['parent_id'] = $parent_requirement->id;

        try {
            $confinementRequirement = ConfinementRequirement::create($validated);
            return response()->json($confinementRequirement, 201);
        } catch (QueryException $e) {
            // PostgreSQL unique violation code
            if ($e->getCode() === '23505') {
                return response()->json([
                    'error' => 'Ya existe un requerimiento con estos valores únicos. Verifique los datos.'
                ], 409); // 409 Conflict
            }

            throw $e;
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(ConfinementRequirement $confinementRequirement)
    {
        $confinementRequirement->load(['confinement', 'block']);
        return response()->json($confinementRequirement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConfinementRequirement $confinementRequirement)
    {
        $validated = $request->validate([
            'questions_to_do' => 'sometimes|integer|min:0',
        ]);

        // Verificar que no se sobrepase el número de preguntas del requerimiento padre
        if (!empty($confinementRequirement->parent_id)) {
            $parent_requirement = ConfinementRequirement::where('id', $confinementRequirement->parent_id)
                ->firstOrFail();

            $questions_required = ConfinementRequirement::where('parent_id', $parent_requirement->id)
                ->where('id', '!=', $confinementRequirement->id)
                ->sum('questions_to_do');

            if ($validated['questions_to_do'] + $questions_required > $parent_requirement->questions_to_do) {
                return response()->json(['error' => 'Sobrepasa el número de preguntas del requerimiento del bloque superior'], 422);
            }
        }

        // Verificar que no se reduzca el número de preguntas a menos que sea mayor o igual a la suma de los requerimientos hijos
        $questions_required_children = ConfinementRequirement::where('parent_id', $confinementRequirement->id)
            ->sum('questions_to_do');

        if ($validated['questions_to_do'] < $questions_required_children) {
            return response()->json(['error' => 'No se puede reducir el número de preguntas, ya que es menor que la suma de las preguntas de los requerimientos hijos'], 422);
        }

        $confinementRequirement->update($validated);

        return response()->json($confinementRequirement);
    }

    public function byConfinement($confinementId)
    {
        $confinementRequirements = ConfinementRequirement::with(['block'])
            ->where('confinement_id', $confinementId)
            ->orderBy('parent_id')
            ->get();

        return response()->json($confinementRequirements);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConfinementRequirement $confinementRequirement)
    {
        $confinementRequirement->delete();
        return response()->json(null, 204);
    }
}
