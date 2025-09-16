<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\DifficultyEnum;
use App\Models\ConfinementBlock;
use App\Models\MatrixDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

class ConfinementBlockController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $confinementBlocks = ConfinementBlock::with(['confinement', 'block'])->get();
        return response()->json($confinementBlocks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'confinement_id' => 'required|exists:confinements,id',
            'block_id' => 'required|exists:blocks,id',
            'questions_to_do' => 'required|integer|min:0',
        ]);

        $confinementBlock = ConfinementBlock::create($validated);

        return response()->json($confinementBlock, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ConfinementBlock $confinementBlock)
    {
        $confinementBlock->load(['confinement', 'block']);
        return response()->json($confinementBlock);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ConfinementBlock $confinementBlock)
    {
        $validated = $request->validate([
            'questions_to_do' => 'sometimes|integer|min:0',
        ]);

        $confinementBlock->update($validated);

        return response()->json($confinementBlock);
    }

    public function byConfinement($confinementId)
    {
        $confinementBlocks = ConfinementBlock::with(['confinement', 'block'])
            ->where('confinement_id', $confinementId)
            ->get();

        return response()->json($confinementBlocks);
    }
    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ConfinementBlock $confinementBlock)
    {
        $confinementBlock->delete();
        return response()->json(null, 204);
    }
}
