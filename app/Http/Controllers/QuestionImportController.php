<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use ZipArchive;
use Exception;
use Illuminate\Support\Facades\Log;

class QuestionImportController extends Controller
{
    public function import(Request $request)
    {
        $request->validate([
            'confinementId' => 'required|uuid',
            'questions' => 'required|file|mimes:zip'
        ]);


        $confinementId = $request->input('confinementId');
        $zipFile = $request->file('questions');

        // Create a unique temporary directory under the "local" disk
        $tempFolder = 'tmp_import_' . Str::uuid();
        $tempPath = Storage::disk('local')->path($tempFolder);

        Storage::disk('local')->makeDirectory($tempFolder);

        try {
            // Extract the ZIP directly to the temporary directory
            $zip = new ZipArchive;
            if ($zip->open($zipFile->getRealPath()) === true) {
                $zip->extractTo($tempPath);
                $zip->close();
            } else {
                throw new Exception("No se pudo abrir el archivo ZIP.");
            }

            // Define expected paths
            $jsonPath = $tempPath . '/questions.json';
            $imagesPath = $tempPath . '/images';
            $resolutionsPath = $tempPath . '/resolutions';

            // Validate contents
            if (!file_exists($jsonPath)) {
                throw new Exception("No se encontró el archivo 'questions.json' en el ZIP.");
            }

            if (!is_dir($resolutionsPath)) {
                throw new Exception("No se encontró la carpeta 'resolutions' en el ZIP.");
            }

            if (!is_dir($imagesPath)) {
                throw new Exception("No se encontró la carpeta 'images' en el ZIP.");
            }

            // Call the Artisan command
            $exitCode = Artisan::call('questions:import', [
                'json' => $jsonPath,
                'resolutions' => $resolutionsPath,
                'images' => $imagesPath,
                'confinement' => $confinementId,
            ]);

            $output = Artisan::output();

            // Clean up temp directory
            Storage::disk('local')->deleteDirectory($tempFolder);

            if ($exitCode === 0) {
                return response()->json([
                    'success' => true,
                    'message' => 'Preguntas importadas correctamente.',
                    //'output' => $output,
                ]);
            } else {
                Log::info("Question import failed: " . $output);
                return response()->json([
                    'success' => false,
                    'message' => 'Error al importar preguntas.',
                    'output' => $output,
                ], 500);
            }
        } catch (Exception $e) {
            // Ensure cleanup on failure
            Storage::disk('local')->deleteDirectory($tempFolder);

            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
}
