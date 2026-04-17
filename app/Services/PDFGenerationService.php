<?php
namespace App\Services;

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PDFGenerationService
{
    public function generate(string $latex, string $folder, string $filename): string
    {
        Storage::makeDirectory($folder);

        $texPath = "{$folder}/{$filename}.tex";
        Storage::put($texPath, $latex);

        $fullPath = storage_path("app/{$folder}");
        $this->compile($fullPath, "{$filename}.tex");

        return "{$fullPath}/{$filename}.pdf";
    }

    public function prepareAssets(array $images, string $folder)
    {
        // Ensure directory exists via Storage
        Storage::makeDirectory($folder);

        foreach ($images as $img) {
            $srcRelative = $img->path; // already relative to storage/app
            $destRelative = "{$folder}/{$img->name}";

            if (!Storage::exists($srcRelative)) {
                Log::error("Missing source image: {$srcRelative}");
                continue;
            }

            if (!Storage::exists($destRelative)) {
                Storage::copy($srcRelative, $destRelative);

                $destAbsolute = storage_path("app/{$destRelative}");

                if (pathinfo($destAbsolute, PATHINFO_EXTENSION) === 'eps') {
                    $this->convertEpsToPdf($destAbsolute);
                }
            }
        }

        $this->copyLogo($folder);
    }

    private function copyLogo(string $folder)
    {
        Storage::makeDirectory($folder);

        $destRelative = "{$folder}/logounsa.eps";

        if (!Storage::exists($destRelative)) {
            $src = public_path('images/logounsa.eps');
            $destAbsolute = storage_path("app/{$destRelative}");

            copy($src, $destAbsolute);

            $this->convertEpsToPdf($destAbsolute);
        }
    }

    private function compile(string $dir, string $file)
    {
        $pdflatex = trim(shell_exec("which pdflatex"));
        $command = "cd \"$dir\" && $pdflatex -interaction=nonstopmode \"$file\" 2>&1";
        exec($command, $output, $code);

        if ($code !== 0) {
            logger()->error("LaTeX compilation failed", $output);
        }
    }

    private function convertEpsToPdf(string $absolutePath): ?string
    {
        if (!file_exists($absolutePath)) {
            Log::error("EPS file not found: {$absolutePath}");
            return null;
        }

        $outputPath = preg_replace('/\.eps$/', '-eps-converted-to.pdf', $absolutePath);

        // 👇 IMPORTANT: ensure PATH
        putenv("PATH=/usr/bin:/usr/local/bin:" . getenv("PATH"));

        $cmd = "epstopdf --debug " 
            . escapeshellarg($absolutePath)
            . " --outfile=" . escapeshellarg($outputPath)
            . " 2>&1";

        $output = shell_exec($cmd);

        if (!file_exists($outputPath)) {
            Log::error("EPS conversion failed", [
                'command' => $cmd,
                'output' => $output
            ]);
            return null;
        }

        return $outputPath;
    }

    public function cleanup(string $folder)
    {
        Storage::deleteDirectory($folder);
    }

    public function deleteFolderAfterResponse(string $folder)
    {
        app()->terminating(function () use ($folder) {
            Storage::deleteDirectory($folder);
        });
    }
}