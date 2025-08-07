<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $exams = Exam::all();
        return response()->json($exams);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'matrix_id' => 'required|exists:matrices,id',
            'description' => 'required|string',
            'total_variations' => 'required|integer',
        ]);

        $validated['user_id'] = $request->user()->id; // Assuming the user is authenticated

        $exam = Exam::create($validated);

        return response()->json($exam, 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        $exam->load('matrix', 'user');
        return response()->json($exam);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Exam $exam)
    {
        $validated = $request->validate([
            'matrix_id' => 'sometimes|required|exists:matrices,id',
            'description' => 'sometimes|required|string',
            'total_variations' => 'sometimes|required|integer',
        ]);

        $exam->update($validated);

        return response()->json($exam);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        $exam->delete();
        return response()->json(null, 204);
    }
}
