<?php
namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\ExamStatusEnum;
use App\Models\Exam;
use App\Models\ExamLayout;
use App\Models\Master;
use App\Models\Question;
use App\Services\LatexService;
use App\Services\PDFGenerationService;

class PDFController extends Controller
{
    public function __construct(
        private LatexService $latex,
        private PDFGenerationService $pdf
    ) {}

    private function getQuestions($examId, $area)
    {
        $masters = Master::where('exam_id', $examId)
            ->where('area', $area)
            ->get();

        if ($masters->isEmpty()) {
            abort(404, 'No questions found for this exam/area');
        }

        $questionIds = $masters->pluck('question_id');

        // Maintain original order (Postgres)
        $quotedIds = $questionIds->map(fn($id) => "'$id'")->implode(',');

        return Question::whereIn('id', $questionIds)
            ->with(['options', 'text', 'block.level', 'images'])
            ->orderBy('block_id')
            ->orderBy('text_id')
            ->orderByRaw("array_position(ARRAY[{$quotedIds}]::uuid[], id::uuid)")
            ->get();
    }

    public function generateMasterPdf($examId, $area)
    {
        $exam = Exam::findOrFail($examId);

        if ($exam->status === ExamStatusEnum::CONFIGURING || $exam->status === ExamStatusEnum::VALIDATED) {
            return response()->json(['error' => 'Master not generated yet'], 422);
        }

        $questions = $this->getQuestions($examId, $area);
        $latex = $this->latex->buildMaster($questions, $area);

        $folder = "pdf/{$examId}_{$area}";
        $this->pdf->prepareAssets(
            $questions->pluck('images')->flatten()->all(),
            $folder
        );

        $pdfPath = $this->pdf->generate($latex, $folder, "master");
        $this->pdf->deleteFolderAfterResponse($folder);

        return response()->file($pdfPath);
    }

    public function generateVariationPdf(Exam $exam, AreaEnum $area, $variation)
    {
        $layout = ExamLayout::with('question.options', 'question.images')
            ->where('exam_id', $exam->id)
            ->where('area', $area)
            ->where('variation', $variation)
            ->orderBy('position')
            ->get();

        $latex = $this->latex->buildVariation($exam, $layout, $area->value, $variation);

        $folder = "pdf/tmp_" . uniqid();

        $images = $layout->pluck('question.images')->flatten()->all();
        $this->pdf->prepareAssets($images, $folder);

        $pdfPath = $this->pdf->generate($latex, $folder, "variation");
        $this->pdf->deleteFolderAfterResponse($folder);

        return response()->file($pdfPath);
    }
}