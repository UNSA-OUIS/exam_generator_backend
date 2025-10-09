<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\Question;
use Illuminate\Http\Request;

class QuestionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return Question::with('areas', 'confinement', 'exam')
            ->when($request->has('area'), function ($query) use ($request) {
                $query->whereHas('areas', function ($q) use ($request) {
                    $q->where('area', $request->input('area'));
                });
            })
            ->when($request->has('confinement_id'), function ($query) use ($request) {
                $query->where('confinement_id', $request->input('confinement_id'));
            })
            ->when($request->has('exam_id'), function ($query) use ($request) {
                $query->where('exam_id', $request->input('exam_id'));
            })
            ->when($request->has('block_id'), function ($query) use ($request) {
                $query->where('block_id', $request->input('block_id'));
            })
            ->get();
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        return Question::with('options', 'block', 'text', 'formulator', 'validator', 'style_editor', 'digitizer', 'confinement', 'images', 'areas')->findOrFail($id);
    }
}
