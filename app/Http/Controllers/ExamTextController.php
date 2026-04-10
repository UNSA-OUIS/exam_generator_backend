<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Models\Block;
use App\Models\ExamRequirement;
use App\Models\ExamText;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Validation\Rules\Enum;

class ExamTextController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $examTexts = ExamText::all();
        return response()->json($examTexts);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'exam_id' => 'required|exists:exams,id',
            'area' => ['sometimes', new Enum(AreaEnum::class)],
            'block_id' => 'required|exists:blocks,id',
            'n_texts' => 'required|integer',
            'questions_per_text' => 'required|integer',
        ]);

        $block = Block::find($validated['block_id']);

        if(!$block->has_text) {
            return response()->json(['error' => 'El bloque seleccionado no admite textos'], 422);
        }

        $current_n_questions = ExamText::where('exam_id', $validated['exam_id'])
            ->where('area', $validated['area'])
            ->where('block_id', $validated['block_id'])
            ->selectRaw('COALESCE(SUM(n_texts * questions_per_text), 0) as total')
            ->value('total');

        $questionRequirement = ExamRequirement::where('exam_id', $validated['exam_id'])
            ->where('area', $validated['area'])
            ->where('block_id', $validated['block_id'])
            ->whereNull('difficulty')
            ->first();

        if(!$questionRequirement) {
            return response()->json(['error' => 'No existe un requerimiento de preguntas para el bloque y área seleccionados'], 422);
        }

        $new_n_questions = $validated['n_texts'] * $validated['questions_per_text'];

        if ($current_n_questions + $new_n_questions > $questionRequirement->n_questions) {
            return response()->json(['error' => 'El total de preguntas por textos excede el requerimiento del bloque en este examen.'], 422);
        }

        try {
            $examText = ExamText::create($validated);
        } catch (QueryException $e) {
            if ($e->getCode() === '23505') {
                return response()->json(['error' => 'Requerimiento de Texto duplicado.'], 409);
            }
            throw $e;
        }

        return response()->json($examText, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(ExamText $examText)
    {
        $examText->load('exam', 'block');
        return response()->json($examText);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, ExamText $examText)
    {
        $validated = $request->validate([
            'exam_id' => 'sometimes|required|exists:exams,id',
            'area' => ['sometimes', new Enum(AreaEnum::class)],
            'block_id' => 'sometimes|required|exists:blocks,id',
            'n_texts' => 'sometimes|required|integer',
            'questions_per_text' => 'sometimes|required|integer',
        ]);

        $block = Block::find($validated['block_id']);

        if(!$block->has_text) {
            return response()->json(['error' => 'El bloque seleccionado no admite textos'], 422);
        }

        $current_n_questions = ExamText::where('exam_id', $validated['exam_id'])
            ->where('area', $validated['area'])
            ->where('block_id', $validated['block_id'])
            ->selectRaw('COALESCE(SUM(n_texts * questions_per_text), 0) as total')
            ->value('total');

        $questionRequirement = ExamRequirement::where('exam_id', $validated['exam_id'])
            ->where('area', $validated['area'])
            ->where('block_id', $validated['block_id'])
            ->whereNull('difficulty')
            ->first();

        if(!$questionRequirement) {
            return response()->json(['error' => 'No existe un requerimiento de preguntas para el bloque y área seleccionados'], 422);
        }

        $new_n_questions = $validated['n_texts'] * $validated['questions_per_text'];

        if ($current_n_questions + $new_n_questions > $questionRequirement->n_questions) {
            return response()->json(['error' => 'El total de preguntas por textos excede el requerimiento del bloque en este examen.'], 422);
        }

        $examText->update($validated);

        return response()->json($examText);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(ExamText $examText)
    {
        $examText->delete();
        return response()->json(null, 204);
    }
}
