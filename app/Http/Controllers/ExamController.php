<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\ExamStatusEnum;
use App\Enums\QuestionStatusEnum;
use App\Models\Exam;
use App\Models\ExamLayout;
use App\Models\ExamRequirement;
use App\Models\Master;
use App\Models\Matrix;
use App\Models\MatrixRequirement;
use App\Models\Question;
use App\Models\Text;
use App\Services\LatexService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;

class ExamController extends Controller
{
    public function __construct(
        private LatexService $latex
    ) {}

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

    public function getAnswers(Exam $exam)
    {
        if($exam->status !== ExamStatusEnum::VARIATED) {
            return response()->json(['error' => 'Respuestas no disponibles para exámenes no permutados'], 422);
        }
        $layouts = ExamLayout::join('questions', 'questions.id', '=', 'exam_layouts.question_id')
            ->where('exam_layouts.exam_id', $exam->id)
            ->orderBy('area')
            ->orderBy('variation')
            ->orderBy('position')
            ->select('area', 'variation', 'questions.answer')
            ->get();

        // Converts a positive integer (1 → A, 27 → AA, etc.)
        $toLetter = function (int $num): string {
            $letters = '';
            while ($num > 0) {
                $num--; // adjust because A starts at 1
                $letters = chr(65 + ($num % 26)) . $letters;
                $num = intdiv($num, 26);
            }
            return $letters;
        };

        // Group and structure answers
        $grouped = $layouts
            ->groupBy(['area', 'variation'])
            ->flatMap(function ($variations, $area) use ($toLetter) {
                return collect($variations)->map(function ($rows, $variation) use ($toLetter, $area) {
                    return [
                        'area' => $area,
                        'variation' => $variation,
                        'answers' => $rows->pluck('answer')
                            ->map(fn($n) => $toLetter((int) $n))
                            ->values()
                            ->toArray(),
                    ];
                });
            })
            ->values();

        return $grouped;
    }

    public function approve(Exam $exam)
    {
        if ($exam->status !== ExamStatusEnum::VARIATED) {
            return response()->json([
                'success' => false,
                'message' => 'El examen debe estar en estado VARIADO para ser aprobado.'
            ], 400);
        }

        try {
            DB::beginTransaction();

            $exam->status = ExamStatusEnum::APPROVED;
            $exam->save();

            // Set all questions of the exam to USED
            Question::where('exam_id', $exam->id)
                ->update(['questions.status' => QuestionStatusEnum::USED]);

            // Set all texts of the exam to USED
            $texts_ids = Question::where('exam_id', $exam->id)
                ->whereNotNull('text_id')
                ->distinct()
                ->pluck('text_id');

            Text::whereIn('id', $texts_ids)
                ->update(['texts.status' => QuestionStatusEnum::USED]);
            DB::commit();
            return response()->json([
                'success' => true,
                'message' => 'El examen ha sido marcado como APROBADO.'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Ocurrió un error al marcar el examen como aprobado: ' . $e->getMessage()
            ], 500);
        }
    }

    public function downloadExamAssets(Request $request, Exam $exam)
    {
        Log::alert("Download assets requested for exam {$exam->id}");
        /*if($exam->status !== ExamStatusEnum::APPROVED) {
            return response()->json([
                'success' => false,
                'message' => 'Los activos solo pueden ser descargados para examenes aprobados.'
            ], 400);
        }*/

        $baseFolder = '/tmp/exam_assets_' . $exam->id;
        $zipPath = storage_path('app/exam_assets_' . $exam->id . '.zip');

        // Limpiar si existe
        if (File::exists($baseFolder)) {
            File::deleteDirectory($baseFolder);
        }

        File::makeDirectory($baseFolder, 0755, true);
        $logoSrc = public_path('images/logounsa.eps');

        foreach(AreaEnum::cases() as $area) {
            for( $i = 0; $i < $exam->total_variations; $i++ ) {
                $variation = chr(65 + $i); // A, B, C...
                $folder = "{$baseFolder}/{$area->value}_{$variation}";

                $layout = ExamLayout::with('question.options', 'question.images')
                    ->where('exam_id', $exam->id)
                    ->where('area', $area)
                    ->where('variation', $variation)
                    ->orderBy('position')
                    ->get();

                if($layout->isEmpty()) {
                    Log::info("No layout found for exam {$exam->id}, area {$area->value}, variation {$variation}");
                    continue;
                }

                File::makeDirectory($folder, 0755, true, true);

                $latex = $this->latex->buildVariation($exam, $layout, $area->value, $variation);
                
                // Guardar latex en archivo .tex dentro de la carpeta 'folder'
                File::put("{$folder}/{$area->value}_{$variation}.tex", $latex);
                
                File::copy($logoSrc, "{$folder}/logounsa.eps");

                // copiar imagenes a una carpeta imagenes dentro de la carpeta 'folder'
                $images = $layout->pluck('question.images')->flatten()->all();
                foreach ($images as $img) {
                    $srcRelative = $img->path;
                    $src = storage_path('app/' . $srcRelative);

                    if (File::exists($src)) {
                        $filename = basename($src);
                        File::copy($src, "{$folder}/{$filename}");
                    }
                }
            }
        }

        // Comprimir la carpeta baseFolder y devolver el archivo zip para descarga
        $zip = new \ZipArchive();

        if ($zip->open($zipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $files = new \RecursiveIteratorIterator(
                new \RecursiveDirectoryIterator($baseFolder),
                \RecursiveIteratorIterator::LEAVES_ONLY
            );

            foreach ($files as $file) {
                if (!$file->isDir()) {
                    $filePath = $file->getRealPath();
                    $relativePath = substr($filePath, strlen($baseFolder) + 1);

                    $zip->addFile($filePath, $relativePath);
                }
            }

            $zip->close();
        }

        File::deleteDirectory($baseFolder);
        return response()->download($zipPath)->deleteFileAfterSend(true);
    }
}
