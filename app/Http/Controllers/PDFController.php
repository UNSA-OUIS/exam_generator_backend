<?php

namespace App\Http\Controllers;

use App\Enums\AreaEnum;
use App\Enums\ExamStatusEnum;
use App\Models\Block;
use App\Models\Exam;
use App\Models\ExamLayout;
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
    \\fancyhead[LO,RE]{\\scriptsize{\\textbf{Bloques y niveles}}}
    \\chead{Area: \\huge{\\textrm{{$componentName}}}}
    \\fancyfoot[LE,RO]{\\Large{\\textbf{\\textsf{\\thepage}}}}
    \\fancyfoot[LO,RE]{\\vspace {-4mm}
    \\includegraphics[scale=0.6]{logounsa.eps}}
    \\cfoot{\\scriptsize{\\textbf{" . now('America/Lima')->translatedFormat('l j \d\e F Y') . "}}}
    \\clubpenalty=10000 \\widowpenalty=10000

    \\begin{document}";
    }

    public function getExamHeader(Exam $exam, $title, $variation)
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
    \\fancyhead[LE,RO]{\\textsf{Tema: \\Large{\\textsf{{\\textbf{" . $variation . "}}}}}}
    \\fancyhead[LO,RE]{\\scriptsize{\\textbf{" . $exam->description . "}}} \\chead{\'{A}rea: \\huge{\\textrm{" . $title . "}}}
    \\fancyfoot[LE,RO]{\\Large{\\textbf{\\textsf{\\thepage}}}}
    \\fancyfoot[LO,RE]{\\vspace {-4mm}
    \\includegraphics[scale=0.6]{logounsa.eps}}
    \\cfoot{\\scriptsize{\\textbf{" . now('America/Lima')->translatedFormat('l j \d\e F Y') . "}}} \\clubpenalty=10000 \\widowpenalty=10000

    \\begin{document}
    \\begin{enumerate}[label=\\textbf{\arabic*}.,start=1]\n";
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
        $exam = Exam::findOrFail($examId);
        if ($exam->status !== ExamStatusEnum::MASTERED) {
            return response()->json([
                'error' => 'Se debe generar el master del examen en el area solicitada para generar el PDF.'
            ], 422);
        }

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
            ->orderBy('block_id')
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

        $last_block_id = null;
        foreach ($questions as $question) {
            if ($question->block_id !== $last_block_id) {
                $blockComponent = Block::where('code', substr($question->block->code, 0, 4))->first();
                $componentName = strtoupper($blockComponent?->name) ?? '';

                if ($componentName != '') {
                    $latex .= "\\subsubsection*{{$componentName}}\n";
                }
                $last_block_id = $question->block_id;
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

        if (!file_exists($pdfPath)) {
            Log::error("Fallo en conversión EPS a PDF. Comando: {$command}\nSalida: {$output}");
            return null;
        }

        return $pdfPath;
    }

    public function getPreguntaLatex(Question $question, $orden_alternativas)
    {
        $tex_pregunta = "% $question->block->code \n";
        $tex_pregunta .= "\\item " . $question->statement . "\n";
        $tex_pregunta .= "\\begin{description}\n";

        $options = $question->options->sortBy('number')->values();

        if (count($orden_alternativas) != count($options))
            throw new Exception("No se puede obtener el latex de la pregunta $question->id el # de alternativas es incorrecto");

        $item = "A";
        foreach ($orden_alternativas as $orden) {
            $option = $options[$orden - 1];
            $tex_pregunta .= "\\item [" . $item++ . ".] " . $option->description . "\n";
        }

        $tex_pregunta .= "\\end{description}\n";
        $tex_pregunta .= "\\pagebreak[2]\n";
        return $tex_pregunta;
    }

    public function convertImage($imagen_path)
    {
        $inputFile = storage_path('app/') . $imagen_path;
        $outputFile = storage_path('app/') . preg_replace('/\.eps$/', '-eps-converted-to.pdf', $imagen_path);
        $command = "epstopdf --debug  $inputFile --outfile=$outputFile 2>&1";
        $output = shell_exec($command);
        if (!file_exists($outputFile)) {
            Log::error("Conversion failed. Output: " . $output);
            return false;
        }
        return true;
    }

    public function compileLatex($latex_file_path)
    {
        $variable = shell_exec("/usr/bin/pdflatex -interaction=nonstopmode -output-directory /var/www/html/exam_generator/backend/storage/app/compilation $latex_file_path 2>&1");

        // Replace .tex with .pdf to get the expected output path
        $pdf_file_path = preg_replace('/\.tex$/', '.pdf', $latex_file_path);

        if (!file_exists($pdf_file_path)) {
            Log::error("Ha ocurrido un error generando el pdf master: " . $variable);
            return false;
        }

        return true;
    }

    public function downloadVariation(Exam $exam, AreaEnum $area, $variation)
    {
        $exam_layout = ExamLayout::with('question.options')
            ->where('exam_id', $exam->id)
            ->where('area', $area)
            ->where('variation', $variation)
            ->orderBy('position', 'asc')
            ->get();

        $texts_compiled = [];
        $tex_content = $this->getExamHeader($exam, $area->value, $variation);
        foreach ($exam_layout as $l) {
            $question = $l->question;

            if ($question->text_id && !in_array($question->text_id, $texts_compiled)) {
                $text = $question->text;
                $tex_content .= "\n" . $text->content;
                $textos_compilados[] = $question->text_id;
            }

            $tex_content .= $this->getPreguntaLatex($question, $l->options);

            foreach ($question->images as $image) {
                $sourcePath = storage_path("app/{$image->path}"); // 👈 real path
                $destPath = storage_path("app/compilation/{$image->name}");

                if (!file_exists($destPath) && file_exists($sourcePath)) {
                    copy($sourcePath, $destPath);
                    if (pathinfo($destPath, PATHINFO_EXTENSION) == 'eps') {
                        $this->convertEpsToPdf($sourcePath, $destPath);
                    }
                }
            }
        }

        $tex_content = $tex_content . "\n\\end{enumerate}\n\\end{document}";

        // Copy logo
        $logoPath = public_path('images/logounsa.eps');
        $logoDest = storage_path("app/compilation/logounsa.eps");
        if (!file_exists($logoDest) && file_exists($logoPath)) {
            copy($logoPath, $logoDest);
            if (pathinfo($logoDest, PATHINFO_EXTENSION) == 'eps') {
                $this->convertEpsToPdf($logoPath, $logoDest);
            }
        }

        // Compilar
        $tema_file = 'compilation/tema';
        if (!Storage::put($tema_file . ".tex", $tex_content)) {
            Log::error("No se puedo escribir el tema " . "$area->value-$variation" . " en el almacenamiento");
            abort(500, "No se puedo escribir el tema en el almacenamiento");
        }

        $filename = storage_path('app/') . $tema_file . ".tex";
        if (!$this->compileLatex($filename))
            abort(500, "Ha ocurrido un error generando el pdf master");

        return response()->file(storage_path('app/') . $tema_file . ".pdf", [
            'Content-Disposition' => 'inline; filename="tema_' . $variation . "_" . time() . '.pdf"'
        ]);
    }
}
