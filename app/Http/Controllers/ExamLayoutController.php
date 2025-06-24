<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\ExamLayout;
use Illuminate\Http\Request;

class ExamController extends Controller
{
    /**
     * Display the specified resource.
     */
    public function show(Exam $exam)
    {
        $layout = ExamLayout::where('exam_id', $exam->id)
            ->orderBy('area')
            ->orderBy('variation')
            ->orderBy('position')
            ->get();

        return response()->json($layout);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Exam $exam)
    {
        ExamLayout::where('exam_id', $exam->id)->delete();
        return response()->json(null, 204);
    }
}
