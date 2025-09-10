<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\DifficultyEnum;
use App\Models\ConfinementText;
use App\Models\MatrixDetail;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Validation\Rules\Enum;

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

        $confinementText = ConfinementText::create($validated);

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
}
