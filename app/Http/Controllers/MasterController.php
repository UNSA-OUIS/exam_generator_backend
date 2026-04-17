<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\ExamStatusEnum;
use App\Enums\QuestionStatusEnum;
use App\Models\Block;
use App\Models\Exam;
use App\Models\ExamRequirement;
use App\Models\ExamText;
use App\Models\Master;
use App\Models\Question;
use App\Models\Text;
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
    public function generate(Exam $exam)
    {
        /*if ($exam->status !== ExamStatusEnum::VALIDATED) {
            return response()->json([
                'success' => false,
                'message' => 'El examen debe estar en estado VALIDADO para generar el master.'
            ], 400);
        }*/


        try {
            DB::beginTransaction();

            $areas = ExamRequirement::where('exam_id', $exam->id)
                ->whereNull('parent_id')
                ->distinct('area')
                ->pluck('area');

            $usedQuestionIds = [];
            foreach ($areas as $area) {
                Question::where('exam_id', $exam->id)
                    ->where('status', QuestionStatusEnum::UNAVAILABLE)
                    ->update([
                        'status' => QuestionStatusEnum::AVAILABLE,
                        'exam_id' => null
                    ]);

                Master::where('exam_id', $exam->id)
                    ->where('area', $area)
                    ->delete();

                $requirements = DB::select(
                    'SELECT * FROM get_requirements(:model, :uuid, :area)',
                    [
                        'model' => 'exam',
                        'uuid' => $exam->id,
                        'area' => $area->value,
                    ]
                );

                if (count($requirements) === 0) {
                    throw new Exception("Faltan los requerimientos del examen para area {$area->value}");
                }

                $mastersToInsert = [];
                $questionsIds = [];

                $texts_ids = [];
                foreach ($requirements as $req) {
                    // Los bloques con texto deben ser hojas
                    $block = Block::find($req->block_id);
                    if ($block->has_text) {
                        // sortear preguntas con texto asociado
                        $exam_texts = ExamText::where('exam_id', $exam->id)
                            ->where('area', $area)
                            ->where('block_id', $req->block_id)->get();

                        // !!verificar que las preguntas requeridas con texto sean las requeridas por el bloque en el examen (pendiente)
                        // Req dominio: En el internamiento se elaboran textos con n preguntas (2,3,4) y aqui se sortean esos textos dependiendo de su nro de preguntas exacto. De tal forma que no se desperdicien textos con varias preguntas para requerimientos con pocas.
                        $texts_selected_ids = [];
                        foreach ($exam_texts as $et) {
                            $n_texts = $et->n_texts;
                            $n_questions = $et->questions_per_text;
                            $req_text_ids = Text::where('status', QuestionStatusEnum::AVAILABLE)
                                ->where('block_id', $block->id)
                                ->where('n_questions', $n_questions)
                                ->inRandomOrder()
                                ->limit($n_texts)
                                ->get()
                                ->pluck('id')
                                ->toArray();
                            
                            if(count($req_text_ids) !== $n_texts) {
                                throw new Exception("No hay suficientes textos disponibles para el bloque {$block->code} con {$n_questions} preguntas. Requeridos: {$n_texts}, disponibles: " . count($texts_selected_ids));
                            }

                            $texts_selected_ids = array_merge($texts_selected_ids, $req_text_ids);
                        }

                        $available = Question::query()
                            ->whereIn('text_id', $texts_selected_ids)
                            ->where('status', QuestionStatusEnum::AVAILABLE)
                            ->get();

                        if ($available->count() < $req->n_questions) {
                            throw new Exception(
                                "No hay suficientes preguntas con texto para el bloque {$req->block_id}, " .
                                    "area {$area->value}. " .
                                    "Requeridos {$req->n_questions}, encontrados {$available->count()}"
                            );
                        }

                        $texts_ids = array_merge($texts_ids, $texts_selected_ids);
                    } else {
                        // === Fetch available questions for this block/difficulty/area ===
                        $available = Question::query()
                            ->where('status', QuestionStatusEnum::AVAILABLE)
                            ->where('block_id', $req->block_id)
                            ->when($req->difficulty !== null, fn($q) => $q->where('difficulty', $req->difficulty))
                            ->when(
                                $area !== AreaEnum::UNICA,
                                fn($q) => $q->whereHas('areas', fn($q) => $q->whereIn('area', [$area, AreaEnum::UNICA])) // Selecciona preguntas del area o generales
                            )
                            ->inRandomOrder()
                            ->limit($req->n_questions)
                            ->get();
                    }
                    

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
                            'exam_id' => $exam->id,
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

            // Se actualiza aca para permitir que sean reutilizados en varias areas
            Text::whereIn('id', $texts_ids)
                ->update(['status' => QuestionStatusEnum::UNAVAILABLE]);

            Question::whereIn('id', $usedQuestionIds)
                ->update([
                    'status' => QuestionStatusEnum::UNAVAILABLE,
                    'exam_id' => $exam->id
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

    public function destroy(Exam $exam)
    {
        if($exam->status !== ExamStatusEnum::MASTERED){
            return response()->json([
                'success' => false,
                'message' => 'El examen debe estar en estado SORTEADO para eliminar los masters.'
            ], 400);
        }

        Master::where('exam_id', $exam->id)->delete();
        $exam->status = ExamStatusEnum::CONFIGURING;    // Volver a validar para generar nuevo master
        $exam->save();
        return response()->json(null, 204);
    }
}
