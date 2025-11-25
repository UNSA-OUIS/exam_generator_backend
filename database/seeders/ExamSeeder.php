<?php

namespace Database\Seeders;

use App\Enums\AreaEnum;
use App\Enums\ExamStatusEnum;
use App\Models\Block;
use App\Models\Exam;
use App\Models\ExamRequirement;
use App\Models\Matrix;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $exam = Exam::create([
            'matrix_id' => Matrix::first()->id,
            'user_id' => 1,
            'description' => 'Examen de admisión 2025-I',
            'total_variations' => 4,
            'status' => ExamStatusEnum::CONFIGURING
        ]);

        $areas = AreaEnum::cases();
        $ejes = Block::where('level_id', 1)->get();

        $QUESTIONS_PER_COMPONENT = 3;
        foreach ($areas as $area) {
            if ($area !== AreaEnum::UNICA) {
                continue;
            }
            $root_req = ExamRequirement::create([
                'exam_id' => $exam->id,
                'area' => $area,
                'block_id' => null,
                'n_questions' => $ejes->count() * $QUESTIONS_PER_COMPONENT * 2,
            ]);
            foreach ($ejes as $eje) {
                $children = Block::where('parent_block_id', $eje->id)->get();
                $total = $children->count() * $QUESTIONS_PER_COMPONENT;
                $eje_req = ExamRequirement::create([
                    'exam_id' => $exam->id,
                    'area' => $area,
                    'block_id' => $eje->id,
                    'n_questions' => $total,
                    'parent_id' => $root_req->id,
                ]);

                foreach ($children as $component) {
                    $comp_req = ExamRequirement::create([
                        'exam_id' => $exam->id,
                        'area' => $area,
                        'block_id' => $component->id,
                        'n_questions' => $QUESTIONS_PER_COMPONENT,
                        'parent_id' => $eje_req->id,
                    ]);
                }
            }
        }
    }
}
