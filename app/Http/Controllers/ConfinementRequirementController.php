<?php

namespace App\Http\Controllers;

use App\Services\Requirements\ConfinementRequirementService;
use Illuminate\Http\Request;
use App\Models\ConfinementRequirement;

class ConfinementRequirementController extends Controller
{
    public function __construct(protected ConfinementRequirementService $service) {}

    public function index()
    {
        return response()->json(ConfinementRequirement::with(['confinement', 'block'])->get());
    }

    public function show(ConfinementRequirement $confinementRequirement)
    {
        return response()->json($confinementRequirement);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'confinement_id' => 'required|exists:confinements,id',
            'parent_id' => 'required|exists:confinement_requirements,id',
            'block_id' => 'required|exists:blocks,id',
            'difficulty' => 'nullable|string',
            'n_questions' => 'required|integer|min:0',
        ]);

        try {
            $created = $this->service->store($validated);
            return response()->json($created, 201);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }

    public function update(Request $request, ConfinementRequirement $confinementRequirement)
    {
        $validated = $request->validate([
            'n_questions' => 'required|integer|min:0',
        ]);

        try {
            $updated = $this->service->update($confinementRequirement, $validated);
            return response()->json($updated);
        } catch (\Throwable $e) {
            $status = $e->getCode() ?: 422;
            return response()->json(['error' => $e->getMessage()], $status);
        }
    }

    public function destroy(ConfinementRequirement $confinementRequirement)
    {
        $confinementRequirement->delete();
        return response()->noContent();
    }

    public function byConfinement($confinementId)
    {
        $confinementRequirements = ConfinementRequirement::with(['block'])
            ->where('confinement_id', $confinementId)
            ->orderBy('parent_id')
            ->get();

        return response()->json($confinementRequirements);
    }
}
