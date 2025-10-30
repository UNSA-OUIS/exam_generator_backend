<?php

namespace App\Http\Controllers;

use App\Models\Block;
use Illuminate\Http\Request;
use App\Models\MatrixRequirement;
use App\Services\Requirements\MatrixRequirementService;
use Illuminate\Support\Facades\Log;

class MatrixRequirementController extends Controller
{
    public function __construct(protected MatrixRequirementService $service) {}

    public function index()
    {
        $requirements = MatrixRequirement::with(['block'])->get();
        return response()->json($requirements);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'matrix_id' => 'required|uuid|exists:matrices,id',
            'parent_id' => 'required|integer|exists:matrix_requirements,id',
            'block_id' => 'required|integer|exists:blocks,id',
            'area' => 'required|string', // Assuming enum handled by model
            'n_questions' => 'required|integer|min:0',
        ]);

        try {
            $block = Block::find($request->block_id);
            if ($block->level_id !== 1 && $block->level_id !== 2) {
                return response()->json(['error' => 'El bloque debe ser de nivel 1 o 2'], 422);
            }

            $created = $this->service->store($validated);
            return response()->json($created, 201);
        } catch (\Throwable $e) {
            Log::error('Error creating MatrixRequirement: ' . $e->getMessage());
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }

    public function update(Request $request, MatrixRequirement $matrixRequirement)
    {
        $validated = $request->validate([
            'n_questions' => 'sometimes|integer|min:0',
        ]);

        try {
            $updated = $this->service->update($matrixRequirement, $validated);
            return response()->json($updated);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }

    public function destroy(MatrixRequirement $matrixRequirement)
    {
        $matrixRequirement->delete();
        return response()->noContent();
    }

    public function byMatrix(Request $request, $matrixId)
    {
        $requirements = MatrixRequirement::with(['block'])
            ->where('matrix_id', $matrixId)
            ->when($request->has('area'), function ($query) use ($request) {
                $query->where('area', $request->input('area'));
            })
            ->orderBy('parent_id')
            ->get();

        return response()->json($requirements);
    }
}
