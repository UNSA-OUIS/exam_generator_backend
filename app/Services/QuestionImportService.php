<?php

namespace App\Services;

use App\Models\Block;
use App\Models\Collaborator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use App\Models\Question;
use App\Models\Option;
use App\Models\QuestionArea;
use App\Models\QuestionImage;
use App\Models\Text;
use Exception;

class QuestionImportService
{
    public static function importFromJson(string $jsonFilePath, string $resolutionSourcePath, string $imageSourcePath, string $confinementId): array
    {
        if (!file_exists($jsonFilePath)) {
            return ['success' => false, 'message' => 'JSON file not found.'];
        }

        $json = json_decode(file_get_contents($jsonFilePath), true);

        if (!isset($json['questions'], $json['texts'], $json['collaborators'])) {
            return ['success' => false, 'message' => 'Invalid JSON structure. Required keys: questions, texts, collaborators'];
        }

        $baseTargetPath = storage_path("app/confinements/{$confinementId}");
        $resolutionsTargetPath = "{$baseTargetPath}/resolutions";
        $imagesTargetPath = "{$baseTargetPath}/images";

        try {
            // === Copy resolution and image folders only once ===
            if (!File::exists($resolutionsTargetPath)) {
                File::makeDirectory($resolutionsTargetPath, 0755, true);
            }

            if (!File::exists($imagesTargetPath)) {
                File::makeDirectory($imagesTargetPath, 0755, true);
            }

            // Copy entire folders
            File::copyDirectory($resolutionSourcePath, $resolutionsTargetPath);
            File::copyDirectory($imageSourcePath, $imagesTargetPath);

            DB::beginTransaction();

            $textMap = [];
            $collaboratorMap = [];

            // === TEXTS ===
            foreach ($json['texts'] as $text) {
                // Create a new Text with auto-generated UUID
                $newText = Text::create([
                    'content' => $text['content']
                ]);

                // Map the old ID to the new UUID
                $textMap[$text['id']] = $newText->id;
            }

            // === COLLABORATORS ===
            foreach ($json['collaborators'] as $p) {
                $existing = Collaborator::where('dni', $p['dni'])->first();

                if (!$existing) {
                    $collaborator = Collaborator::create([
                        'dni' => $p['dni'],
                        'name' => $p['name'],
                    ]);
                } else {
                    $collaborator = $existing;
                }

                $collaboratorMap[$p['dni']] = $collaborator->id;
            }

            $importedCount = 0;

            // === QUESTIONS ===
            foreach ($json['questions'] as $q) {
                $resolutionFilename = $q['resolution'];
                $resolutionPath = "{$resolutionsTargetPath}/{$resolutionFilename}";

                if (!file_exists($resolutionPath)) {
                    throw new Exception("Resolution file '{$resolutionFilename}' not found in destination folder.");
                }

                // Validate collaborators by dni
                foreach (['formulator', 'validator', 'style_editor', 'digitizer'] as $role) {
                    if (!isset($collaboratorMap[$q[$role]]) && !empty($q[$role])) {
                        throw new Exception("Collaborator with DNI '{$q[$role]}' not found for role '{$role}' in question ID '{$q['id']}'");
                    }
                }

                $block = Block::where('code', trim($q['block']))->first();

                if (!$block) {
                    throw new Exception("Block with code '{$q['block']}' not found for question ID '{$q['id']}'");
                }

                $question = Question::create([
                    'statement' => $q['statement'],
                    'difficulty' => $q['difficulty'],
                    'status' => 'DISPONIBLE',
                    'block_id' => $block->id,
                    'text_id' => $q['text_id'] ? ($textMap[$q['text_id']] ?? null) : null,
                    'formulator_id' => $collaboratorMap[$q['formulator']] ?? null,
                    'validator_id' => $collaboratorMap[$q['validator']] ?? null,
                    'style_editor_id' => $collaboratorMap[$q['style_editor']] ?? null,
                    'digitizer_id' => $collaboratorMap[$q['digitizer']] ?? null,
                    'resolution_path' => "confinements/{$confinementId}/resolutions/{$resolutionFilename}",
                    'answer' => $q['answer'],
                    'confinement_id' => $confinementId,
                    'created_by' => 1,
                    'modified_by' => 1
                ]);

                // === OPTIONS ===
                foreach ($q['options'] as $opt) {
                    Option::create([
                        'question_id' => $question->id,
                        'number' => $opt['number'],
                        'description' => $opt['description']
                    ]);
                }

                // === AREAS ===
                foreach ($q['areas'] as $area) {
                    QuestionArea::create([
                        'question_id' => $question->id,
                        'area' => $area
                    ]);
                }

                // === IMAGES ===
                foreach ($q['images'] as $imageName) {
                    $imagePath = "{$imagesTargetPath}/{$imageName}";

                    if (!file_exists($imagePath)) {
                        throw new Exception("Image file '{$imageName}' not found in destination folder.");
                    }

                    QuestionImage::create([
                        'name' => $imageName,
                        'path' => "confinements/{$confinementId}/images/{$imageName}",
                        'question_id' => $question->id
                    ]);
                }

                $importedCount++;
            }

            DB::commit();

            return [
                'success' => true,
                'message' => "Successfully imported {$importedCount} questions.",
                'count' => $importedCount
            ];
        } catch (Exception $e) {
            DB::rollBack();
            // === Cleanup filesystem on error ===
            if (File::exists($baseTargetPath)) {
                File::deleteDirectory($baseTargetPath);
            }
            return [
                'success' => false,
                'message' => 'Import failed: ' . $e->getMessage()
            ];
        }
    }
}
