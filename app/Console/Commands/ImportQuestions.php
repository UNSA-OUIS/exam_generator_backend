<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\QuestionImportService;

class ImportQuestions extends Command
{
    protected $signature = 'questions:import
        {json : Path to the JSON file}
        {resolutions : Path to resolutions folder}
        {images : Path to images folder}
        {confinement : Confinement UUID}';

    protected $description = 'Import questions from a JSON file into the system';

    public function handle()
    {
        $jsonPath = $this->argument('json');
        $resolutionsPath = $this->argument('resolutions');
        $imagesPath = $this->argument('images');
        $confinementId = $this->argument('confinement');

        // If no images path is passed, create a temporary empty one
        if (!is_dir($imagesPath)) {
            mkdir($imagesPath, 0755, true);
        }

        $this->info("Starting import...");

        $result = QuestionImportService::importFromJson(
            $jsonPath,
            $resolutionsPath,
            $imagesPath,
            $confinementId
        );

        if ($result['success']) {
            $this->info($result['message']);
        } else {
            $this->error($result['message']);
        }

        return $result['success'] ? 0 : 1;
    }
}
