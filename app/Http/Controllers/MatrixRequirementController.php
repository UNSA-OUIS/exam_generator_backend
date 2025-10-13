<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\DifficultyEnum;
use App\Models\Block;
use App\Models\MatrixRequirement;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class MatrixRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matrixDetails = MatrixRequirement::with(['matrix', 'block'])->get();
        return response()->json($matrixDetails);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matrix_id' => 'required|exists:matrices,id',
            'area' => ['required', Rule::enum(AreaEnum::class)],
            'block_id' => 'nullable|exists:blocks,id',
            'n_questions' => 'required|integer|min:1',
        ]);

        $block = Block::findOrFail($validated['block_id']);
        $parent_requirement = MatrixRequirement::where('matrix_id', $validated['matrix_id'])
            ->where('area', $validated['area'])
            ->where('block_id', $block->parent_block_id)
            ->first();

        if (!$parent_requirement) {
            return response()->json(['error' => 'El bloque padre no tiene un requerimiento asociado'], 422);
        }

        $questions_required = MatrixRequirement::where('parent_id', $parent_requirement->id)->sum('n_questions');

        if ($validated['n_questions'] + $questions_required > $parent_requirement->n_questions) {
            return response()->json(['error' => 'Sobrepasa el número de preguntas del requerimiento padre'], 422);
        }

        $validated['parent_id'] = $parent_requirement->id;
        $matrixRequirement = MatrixRequirement::create($validated);

        return response()->json($matrixRequirement, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MatrixRequirement $matrixRequirement)
    {
        $matrixRequirement->load(['matrix', 'block']);
        return response()->json($matrixRequirement);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MatrixRequirement $matrixRequirement)
    {
        $validated = $request->validate([
            'n_questions' => 'sometimes|integer|min:1',
        ]);

        $block = $matrixRequirement->block;
        $parent_requirement = MatrixRequirement::where('matrix_id', $validated['matrix_id'])
            ->where('area', $validated['area'])
            ->where('block_id', $block->parent_block_id)
            ->firstOrFail();

        $questions_required = MatrixRequirement::where('parent_id', $parent_requirement->id)
            ->where('id', '!=', $matrixRequirement->id)
            ->sum('n_questions');

        if ($validated['n_questions'] + $questions_required > $parent_requirement->n_questions) {
            return response()->json(['error' => 'Sobrepasa el número de preguntas del requerimiento padre'], 422);
        }
        $matrixRequirement->update($validated);

        return response()->json($matrixRequirement);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatrixRequirement $matrixRequirement)
    {
        $matrixRequirement->delete();
        return response()->json(null, 204);
    }
}
