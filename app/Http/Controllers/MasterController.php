<?php

namespace App\Http\Controllers;

use App\Models\Exam;
use App\Models\MatrixDetail;
use App\Models\Master;
use App\Models\MatrixRequirement;
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
            'area' => 'required|in:SOCIALES,INGENIERIAS,BIOMEDICAS'
        ]);

        $examId = $request->input('exam_id');
        $area = $request->input('area');

        DB::beginTransaction();

        try {
            // === Restore previous questions for this exam & area ===
            Question::where('exam_id', $examId)
                ->whereIn('id', function ($query) use ($examId, $area) {
                    $query->select('question_id')
                        ->from('masters')
                        ->where('exam_id', $examId)
                        ->where('area', $area);
                })
                ->where('status', 'UNAVAILABLE')
                ->update([
                    'status' => 'AVAILABLE',
                    'exam_id' => null
                ]);

            // remove old masters
            Master::where('exam_id', $examId)
                ->where('area', $area)
                ->delete();

            // === Load requirements for the given area ===
            $details = MatrixRequirement::where('area', $area)->get();

            if ($details->isEmpty()) {
                throw new Exception("Falta la configuracion de matriz para {$area}");
            }

            $mastersToInsert = [];
            $selectedQuestionIds = [];

            foreach ($details as $detail) {
                // === Fetch available questions for this block/difficulty/area ===
                $available = Question::query()
                    ->where('status', 'AVAILABLE')
                    ->where('block_id', $detail->block_id)
                    //->where('difficulty', $detail->difficulty)
                    //->whereHas('areas', fn($q) => $q->where('area', $area))
                    ->inRandomOrder()
                    ->limit($detail->questions_required)
                    ->get();

                if ($available->count() < $detail->questions_required) {
                    throw new Exception(
                        "No hay suficientes preguntas para el bloque {$detail->block_id}, " .
                            "dificultad {$detail->difficulty->value}, area {$area}. " .
                            "Requeridos {$detail->questions_required}, encontrados {$available->count()}"
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

                    $selectedQuestionIds[] = $q->id;
                }
            }

            // === Insert into masters ===
            Master::insert($mastersToInsert);

            // === Mark selected questions as UNAVAILABLE and assign exam_id ===
            Question::whereIn('id', $selectedQuestionIds)
                ->update([
                    'status' => 'UNAVAILABLE',
                    'exam_id' => $examId
                ]);

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Master generado exitosamente.',
            ]);
        } catch (Exception $e) {
            DB::rollBack();

            return response()->json([
                'success' => false,
                'message' => 'Generation failed: ' . $e->getMessage()
            ], 400);
        }
    }
}
