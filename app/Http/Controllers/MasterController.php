<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\ExamStatusEnum;
use App\Enums\QuestionStatusEnum;
use App\Models\Exam;
use App\Models\ExamRequirement;
use App\Models\Master;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class MasterController extends Controller
{
    public function index(Exam $exam)
    {
        $masters = Master::with(['question.block', 'question.options'])->where('exam_id', $exam->id)->get();
        return response()->json($masters);
    }

    /**
     * Generate master layout for a given exam and area.
     */
    public function generate(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|uuid',
        ]);

        $examId = $request->input('exam_id');

        $exam = Exam::find($examId);
        if ($exam->status !== ExamStatusEnum::VALIDATED) {
            return response()->json([
                'success' => false,
                'message' => 'El examen debe estar en estado VALIDADO para generar el master.'
            ], 400);
        }


        try {
            DB::beginTransaction();

            $areas = ExamRequirement::where('exam_id', $examId)
                ->whereNull('parent_id')
                ->distinct('area')
                ->pluck('area');

            $usedQuestionIds = [];
            foreach ($areas as $area) {
                // === Restore previous questions for this exam & area ===
                Question::where('exam_id', $examId)
                    ->where('status', QuestionStatusEnum::UNAVAILABLE)
                    ->update([
                        'status' => QuestionStatusEnum::AVAILABLE,
                        'exam_id' => null
                    ]);

                // remove old masters
                Master::where('exam_id', $examId)
                    ->where('area', $area)
                    ->delete();

                $requirements = DB::select(
                    'SELECT * FROM get_requirements(:model, :uuid, :area)',
                    [
                        'model' => 'exam',
                        'uuid' => $examId,
                        'area' => $area->value,
                    ]
                );

                if (count($requirements) === 0) {
                    throw new Exception("Faltan los requerimientos del examen para area {$area->value}");
                }

                $mastersToInsert = [];
                $questionsIds = [];

                foreach ($requirements as $req) {
                    // === Fetch available questions for this block/difficulty/area ===
                    $available = Question::query()
                        ->where('status', QuestionStatusEnum::AVAILABLE)
                        ->where('block_id', $req->block_id)
                        ->when($req->difficulty !== null, fn($q) => $q->where('difficulty', $req->difficulty))
                        ->whereHas('areas', fn($q) => $q->whereIn('area', [$area, AreaEnum::UNICA])) // Selecciona preguntas del area o generales
                        ->inRandomOrder()
                        ->limit($req->n_questions)
                        ->get();

                    if ($available->count() < $req->n_questions) {
                        throw new Exception(
                            "No hay suficientes preguntas para el bloque {$req->block_id}, " .
                                "dificultad {$req->difficulty?->value}, area {$area->value}. " .
                                "Requeridos {$req->n_questions}, encontrados {$available->count()}"
                        );
                    }

                    // === Build master records ===
                    foreach ($available as $q) {
                        $mastersToInsert[] = [
                            'exam_id' => $examId,
                            'area' => $area,
                            'question_id' => $q->id,
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];

                        $questionsIds[] = $q->id;
                    }
                }

                Master::insert($mastersToInsert);
                $usedQuestionIds = array_unique(array_merge($usedQuestionIds, $questionsIds));
            }

            // === Mark selected questions as UNAVAILABLE and assign exam_id ===
            Question::whereIn('id', $usedQuestionIds)
                ->update([
                    'status' => QuestionStatusEnum::UNAVAILABLE,
                    'exam_id' => $examId
                ]);

            $exam->status = ExamStatusEnum::MASTERED;
            $exam->save();

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Master generado exitosamente.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Error generando master: ' . $e->getMessage()
            ], 400);
        }
    }
}
