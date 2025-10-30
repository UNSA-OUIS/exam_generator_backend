<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\ExamStatusEnum;
use App\Enums\QuestionStatusEnum;
use App\Models\Exam;
use App\Models\ExamLayout;
use App\Models\ExamRequirement;
use App\Models\Master;
use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class SortController extends Controller
{
    function generatePermutation($collection)
    {
        $groupedLevel2 = $collection->groupBy(fn($i) => substr($i->code, 0, 4))
            ->map(fn($g) => $g->shuffle());

        $groupedLevel1 = $groupedLevel2->groupBy(fn($_, $c2) => substr($c2, 0, 2))
            ->map(fn($g) => $g->shuffle())
            ->shuffle();

        return $groupedLevel1
            ->flatMap(fn($lvl2) => $lvl2->flatMap(fn($q) => $q))
            ->values();
    }

    /**
     * Generate master layout for a given exam and area.
     */
    public function sortVariations(Request $request)
    {
        $request->validate([
            'exam_id' => 'required|uuid',
        ]);

        $exam = Exam::find($request->input('exam_id'));
        $variations = ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K'];

        if ($exam->status !== ExamStatusEnum::MASTERED) {
            return response()->json([
                'success' => false,
                'message' => 'El examen debe tener los master generados para realizar el sorteo de temas.'
            ], 400);
        }

        $alternatives = range(1, $exam->matrix->n_alternatives);
        $n_variations = $exam->total_variations;

        try {
            DB::beginTransaction();
            $areas = ExamRequirement::where('exam_id', $request->input('exam_id'))
                ->whereNull('parent_id')
                ->distinct('area')
                ->pluck('area');

            foreach ($areas as $area) {
                $master = Master::join('questions', 'masters.question_id', '=', 'questions.id')
                    ->join('blocks', 'questions.block_id', '=', 'blocks.id')
                    ->where('masters.exam_id', $request->input('exam_id'))
                    ->where('masters.area', $area)
                    ->select('masters.id', 'masters.question_id', 'blocks.code', 'questions.text_id')
                    ->get();

                // Generate and insert $n_variations
                for ($v = 0; $v < $n_variations; $v++) {
                    $permuted = $this->generatePermutation($master);

                    $records = $permuted->values()->map(function ($item, $index) use ($exam, $area, $variations, $v, $alternatives) {
                        // Create a new shuffled copy of alternatives for each question
                        $shuffledAlternatives = $alternatives;
                        shuffle($shuffledAlternatives);

                        return [
                            'exam_id' => $exam->id,
                            'area' => $area,
                            'variation' => $variations[$v],
                            'position' => $index + 1,
                            'question_id' => $item->question_id,
                            'options' => json_encode($shuffledAlternatives),
                            'created_at' => now(),
                            'updated_at' => now(),
                        ];
                    })->toArray();

                    ExamLayout::insert($records);
                }
            }


            $exam->update(['status' => ExamStatusEnum::VARIATED]);

            DB::commit();

            $distinctMasterVariations = ExamLayout::where('exam_id', $exam->id)
                ->select('exam_id', 'area', 'variation')
                ->distinct()
                ->orderBy('area', 'asc')
                ->orderBy('variation', 'asc')
                ->get();

            return response()->json([
                'success' => true,
                'message' => 'Temas generados exitosamente.',
                'variations' => $distinctMasterVariations
            ]);
        } catch (Exception $e) {
            DB::rollBack();
            Log::error("Error generando temas de master. " . $e->getMessage() . ' Linea: ' . $e->getLine() . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Error generando temas: ' . $e->getMessage()
            ], 400);
        }
    }
}
