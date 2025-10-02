<?php

namespace App\Http\Controllers;

use App\Models\Block;
use App\Models\Exam;
use App\Models\MatrixDetail;
use App\Models\Master;
use App\Models\Question;
use App\Models\QuestionImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class PDFController extends Controller
{
    protected function generateLatexHeader($componentName): string
    {
        return "\\documentclass[11pt,twocolumn,twoside]{article}
    \\usepackage[papersize={215mm,320mm},tmargin=18mm,bmargin=32mm,lmargin=15mm,rmargin=15mm]{geometry}
    \\usepackage{epsfig}
    \\usepackage{amsmath}
    \\usepackage{latexsym}
    \\usepackage{amssymb}
    \\usepackage[spanish]{babel}
    \\usepackage{fancyhdr}
    \\usepackage{cmbright}
    \\usepackage{pb-diagram}
    \\usepackage{enumitem}
    \\setlength{\\columnsep}{.7cm} \\pagestyle{fancy}
    \\fancyhead[LE,RO]{\\textsf{MASTER}}
    \\fancyhead[LO,RE]{\\scriptsize{\\textbf{Examen de conocimientos}}}
    \\chead{Componente: \\huge{\\textrm{{$componentName}}}}
    \\fancyfoot[LE,RO]{\\Large{\\textbf{\\textsf{\\thepage}}}}
    \\fancyfoot[LO,RE]{\\vspace {-4mm}
    \\includegraphics[scale=0.6]{logounsa.eps}}
    \\cfoot{\\scriptsize{\\textbf{" . now('America/Lima')->translatedFormat('l j \d\e F Y') . "}}}
    \\clubpenalty=10000 \\widowpenalty=10000

    \\begin{document}";
    }

    public function requiredImages($blockCode)
    {
        $block = Block::where('code', $blockCode)->firstOrFail();
        $blockIds = $this->getAllChildBlockIds($block);
        $blockIds[] = $block->id;

        $questionIds = Question::whereIn('block_id', $blockIds)->pluck('id');

        return QuestionImage::whereIn('question_id', $questionIds)
            ->get(['question_id', 'name'])
            ->toArray();
    }

    private function getAllChildBlockIds(Block $block): array
    {
        $childIds = [];

        foreach ($block->children as $child) {
            $childIds[] = $child->id;
            $childIds = array_merge($childIds, $this->getAllChildBlockIds($child));
        }

        return $childIds;
    }

    public function generateMasterPdf($examId, $area)
    {
        // === Get selected questions for exam/area ===
        $masters = Master::where('exam_id', $examId)
            ->where('area', $area)
            ->get();

        if ($masters->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No questions found in master layout for this exam/area.'
            ], 404);
        }

        $questionIds = $masters->pluck('question_id');

        // Build quoted UUIDs for Postgres
        $quotedIds = $questionIds->map(fn($id) => "'$id'")->implode(',');

        // === Load questions WITH images ===
        $questions = Question::whereIn('id', $questionIds)
            ->with(['options', 'text', 'block.level', 'images'])
            ->orderByRaw("array_position(ARRAY[{$quotedIds}]::uuid[], id::uuid)")
            ->get();

        // === Collect required images from relation ===
        $allImages = $questions->pluck('images')->flatten();

        $requiredNames = $allImages->map(fn($img) => $img->name)
            ->unique()
            ->values()
            ->toArray();

        $uploadedNames = [];
        foreach ($allImages as $img) {
            // 👇 Use real DB path instead of forcing graficos/
            $imagePath = storage_path("app/{$img->path}");
            if (file_exists($imagePath)) {
                $uploadedNames[] = $img->name;
            }
        }

        $missing = array_diff($requiredNames, $uploadedNames);
        if (count($missing) > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Faltan imágenes por subir para generar el PDF',
                'missing' => array_values($missing)
            ], 422);
        }

        // === Prepare LaTeX ===
        $latex = $this->generateLatexHeader($area);
        $latex .= "\\begin{enumerate}[label=\\textbf{\\arabic*.},start=1]\n";

        $counter = 1;

        foreach ($questions as $question) {
            $blockName = $question->block?->name ?? '';
            $levelName = strtoupper($question->block?->level?->name ?? '');

            if ($blockName || $levelName) {
                $latex .= "\\subsubsection*{{$levelName} {$blockName}}\n";
            }

            if ($question->text) {
                $latex .= "% TEXT\n{$question->text->content}\n";
            }

            $latex .= "% Q" . str_pad($counter, 2, '0', STR_PAD_LEFT) . "\n\n";
            $latex .= "\\item {$question->statement}\n\\begin{description}\n";

            // Handle images for this question
            foreach ($question->images as $img) {
                $imageName = $img->name;
                $sourcePath = storage_path("app/{$img->path}"); // 👈 real path
                $folderName = "pdf/{$examId}_{$area}";
                Storage::makeDirectory($folderName);

                $destPath = storage_path("app/{$folderName}/{$imageName}");
                if (!file_exists($destPath) && file_exists($sourcePath)) {
                    copy($sourcePath, $destPath);
                    if (pathinfo($destPath, PATHINFO_EXTENSION) == 'eps') {
                        $this->convertEpsToPdf($sourcePath, $destPath);
                    }
                }
            }

            foreach ($question->options as $option) {
                $letter = chr(64 + $option->number);
                $latex .= "\\item[{$letter}.] {$option->description}\n";
            }

            $latex .= "\\end{description}\n\\pagebreak[2]\n% ENDQ\n";
            $counter++;
        }

        $latex .= "\\end{enumerate}\n\\end{document}";

        $folderName = "pdf/{$examId}_{$area}";
        Storage::makeDirectory($folderName);

        $filename = "master_{$examId}_{$area}.tex";
        $texRelativePath = "{$folderName}/{$filename}";
        Storage::put($texRelativePath, $latex);

        $texFullPath = storage_path("app/{$texRelativePath}");

        // Copy logo
        $logoPath = public_path('images/logounsa.eps');
        $logoDest = storage_path("app/{$folderName}/logounsa.eps");
        if (!file_exists($logoDest) && file_exists($logoPath)) {
            copy($logoPath, $logoDest);
            if (pathinfo($logoDest, PATHINFO_EXTENSION) == 'eps') {
                $this->convertEpsToPdf($logoPath, $logoDest);
            }
        }

        // === Compile LaTeX ===
        $outputDir = storage_path("app/{$folderName}");
        $pdflatex = trim(shell_exec("which pdflatex"));
        $command = "cd \"$outputDir\" && $pdflatex -interaction=nonstopmode \"$filename\" 2>&1";
        exec($command, $output, $returnVar);

        $pdfFilename = str_replace('.tex', '.pdf', $filename);
        $pdfPath = "$outputDir/$pdfFilename";

        if (!file_exists($pdfPath)) {
            return response()->json([
                'error' => 'No se pudo generar el PDF.',
                'output' => $output
            ], 500);
        }

        return response()->file($pdfPath);
    }



    protected function convertEpsToPdf(string $epsPath, string $destPath): ?string
    {
        if (!file_exists($epsPath)) {
            Log::error("Archivo EPS no encontrado: " . $epsPath);
            return null;
        }

        $pdfPath = preg_replace('/\.eps$/', '-eps-converted-to.pdf', $destPath);

        $escapedEpsPath = escapeshellarg($epsPath);
        $escapedPdfPath = escapeshellarg($pdfPath);

        putenv("PATH=/usr/bin:/usr/local/bin:" . getenv("PATH"));

        $epstopdfPath = '/usr/bin/epstopdf';
        $command = "{$epstopdfPath} {$escapedEpsPath} --outfile={$escapedPdfPath} 2>&1";
        $output = shell_exec($command);

        Log::info("Salida del comando epstopdf", ['output' => $output]);

        if (!file_exists($pdfPath)) {
            Log::error("Fallo en conversión EPS a PDF. Comando: {$command}\nSalida: {$output}");
            return null;
        }

        Log::info("Conversión EPS a PDF exitosa: {$pdfPath}");
        return $pdfPath;
    }
}
