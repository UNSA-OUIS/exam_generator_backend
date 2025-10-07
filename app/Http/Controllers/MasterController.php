<?php

namespace App\Http\Controllers;

use App\Models\MatrixDetail;
use App\Models\Master;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class MasterController extends Controller
{
    /**
     * Generate master layout for a given exam and area.
     */
    public function generate(Request $request)
    {
        Log::info('Generating master layout with data: ', $request->all());
        $request->validate([
            'exam_id' => 'required|uuid',
            'area' => 'required|string'
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
                ->update([
                    'status' => 'DISPONIBLE',
                    'exam_id' => null
                ]);

            // remove old masters
            Master::where('exam_id', $examId)
                ->where('area', $area)
                ->delete();

            // === Load requirements for the given area ===
            $details = MatrixDetail::where('area', $area)->get();

            if ($details->isEmpty()) {
                throw new Exception("No matrix details found for area {$area}");
            }

            $mastersToInsert = [];
            $selectedQuestionIds = [];

            foreach ($details as $detail) {
                // === Fetch available questions for this block/difficulty/area ===
                $available = Question::query()
                    ->where('status', 'DISPONIBLE')
                    ->where('block_id', $detail->block_id)
                    ->where('difficulty', $detail->difficulty)
                    ->whereHas('areas', fn($q) => $q->where('area', $area))
                    ->inRandomOrder()
                    ->limit($detail->questions_required)
                    ->get();

                if ($available->count() < $detail->questions_required) {
                    throw new Exception(
                        "Not enough questions for block {$detail->block_id}, " .
                            "difficulty {$detail->difficulty}, area {$area}. " .
                            "Required {$detail->questions_required}, found {$available->count()}"
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
                'message' => 'Master layout generated successfully.',
                'count' => count($mastersToInsert)
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
