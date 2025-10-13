<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\DifficultyEnum;
use App\Models\Block;
use App\Models\ConfinementRequirement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ConfinementRequirementController extends Controller
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
            'block_id' => 'required|exists:blocks,id',
            'difficulty' => ['nullable', Rule::enum(DifficultyEnum::class)],
            'questions_to_do' => 'required|integer|min:0',
        ]);

        $block = Block::findOrFail($validated['block_id']);
        $parent_requirement = ConfinementRequirement::where('confinement_id', $validated['confinement_id'])
            ->where('block_id', $block->parent_block_id)
            ->firstOrFail();

        $questions_required = ConfinementRequirement::where('parent_id', $parent_requirement->id)->sum('questions_to_do');

        if ($validated['questions_to_do'] + $questions_required > $parent_requirement->questions_to_do) {
            return response()->json(['error' => 'Sobrepasa el número de preguntas del requerimiento padre'], 422);
        }

        $validated['parent_id'] = $parent_requirement->id;
        $confinementRequirement = ConfinementRequirement::create($validated);
        return response()->json($confinementRequirement, 201);
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

        $block = $confinementRequirement->block;
        $parent_requirement = ConfinementRequirement::where('confinement_id', $confinementRequirement->confinement_id)
            ->where('block_id', $block->parent_block_id)
            ->firstOrFail();

        $questions_required = ConfinementRequirement::where('parent_id', $parent_requirement->id)
            ->where('id', '!=', $confinementRequirement->id)
            ->sum('questions_to_do');

        if ($validated['questions_to_do'] + $questions_required > $parent_requirement->questions_to_do) {
            return response()->json(['error' => 'Sobrepasa el número de preguntas del requerimiento padre'], 422);
        }

        $confinementRequirement->update($validated);

        return response()->json($confinementRequirement);
    }

    public function byConfinement($confinementId)
    {
        $confinementRequirements = ConfinementRequirement::with(['confinement', 'block'])
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
