<?php

namespace App\Http\Controllers;

use App\Enums\ExamStatusEnum;
use App\Models\Exam;
use App\Models\ExamRequirement;
use App\Models\Matrix;
use App\Models\MatrixRequirement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        $validated['user_id'] = $request->user()->id;
        $validated['status'] = ExamStatusEnum::CONFIGURING;
        $exam = Exam::create($validated);

        $areas = MatrixRequirement::where('matrix_id', $validated['matrix_id'])
            ->distinct('area')
            ->pluck('area');

        foreach ($areas as $area) {
            $root_req = MatrixRequirement::where('matrix_id', $validated['matrix_id'])
                ->where('area', $area)
                ->whereNull('parent_id')
                ->first();

            $this->createExamRequirement($exam->id, $root_req, null);
        }

        return response()->json($exam, 201);
    }

    private function createExamRequirement($exam_id, MatrixRequirement $matrixReq, $parent_id)
    {
        $examReq = ExamRequirement::create([
            'exam_id' => $exam_id,
            'area' => $matrixReq->area,
            'block_id' => $matrixReq->block_id,
            'n_questions' => $matrixReq->n_questions,
            'parent_id' => $parent_id,
        ]);

        $children = MatrixRequirement::where('parent_id', $matrixReq->id)->get();
        foreach ($children as $child) {
            $this->createExamRequirement($exam_id, $child, $examReq->id);
        }
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

    public function validate(Exam $exam)
    {
        $roots = ExamRequirement::where('exam_id', $exam->id)
            ->whereNull('parent_id')
            ->get();

        foreach ($roots as $root) {
            $isComplete = DB::scalar('SELECT is_exam_tree_complete(:exam_id, :area)', [
                'exam_id' => $exam->id,
                'area' => $root->area->value,
            ]);

            if (!$isComplete) {
                return response()->json([
                    'success' => false,
                    'message' => "Los requerimientos del examen para el área {$root->area->value} no están completos.",
                ], 400);
            }
        }

        $exam->status = ExamStatusEnum::VALIDATED;
        $exam->save();

        return response()->json([
            'success' => true,
            'message' => 'Todos los requerimientos del examen están completos y validados.',
        ]);
    }
}
