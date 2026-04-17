<?php

namespace App\Http\Controllers;

use App\Enums\ExamStatusEnum;
use App\Models\Exam;
use App\Models\ExamLayout;
use Illuminate\Http\Request;

class ExamLayoutController extends Controller
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
        if($exam->status !== ExamStatusEnum::VARIATED){
            return response()->json([
                'success' => false,
                'message' => 'El examen debe estar en estado PERMUTADO para eliminar los temas.'
            ], 400);
        }

        ExamLayout::where('exam_id', $exam->id)->delete();
        $exam->status = ExamStatusEnum::MASTERED;
        $exam->save();
        
        return response()->json(null, 204);
    }
}
