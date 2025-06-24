<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Models\ExamText;
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
            'total_texts' => 'required|integer',
        ]);

        $examText = ExamText::create($validated);

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
            'total_texts' => 'sometimes|required|integer',
        ]);

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
