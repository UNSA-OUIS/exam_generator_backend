<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\DifficultyEnum;
use App\Models\MatrixDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class MatrixDetailController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $matrixDetails = MatrixDetail::with(['matrix', 'block'])->get();
        return response()->json($matrixDetails);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matrix_id' => 'required|exists:matrices,id',
            'block_id' => 'required|exists:blocks,id',
            'area' => ['required', Rule::enum(AreaEnum::class)],
            'difficulty' => ['required', Rule::enum(DifficultyEnum::class)],
            'questions_required' => 'required|integer|min:1',
            'questions_to_do' => 'required|integer|min:0',
        ]);

        $matrixDetail = MatrixDetail::create($validated);

        return response()->json($matrixDetail, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(MatrixDetail $matrixDetail)
    {
        $matrixDetail->load(['matrix', 'block']);
        return response()->json($matrixDetail);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, MatrixDetail $matrixDetail)
    {
        $validated = $request->validate([
            'area' => ['sometimes', new Enum(AreaEnum::class)],
            'difficulty' => ['sometimes', new Enum(DifficultyEnum::class)],
            'questions_required' => 'sometimes|integer|min:1',
            'questions_to_do' => 'sometimes|integer|min:0',
        ]);

        $matrixDetail->update($validated);

        return response()->json($matrixDetail);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(MatrixDetail $matrixDetail)
    {
        $matrixDetail->delete();
        return response()->json(null, 204);
    }
}
