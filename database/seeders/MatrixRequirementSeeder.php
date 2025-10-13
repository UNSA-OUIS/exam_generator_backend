<?php

namespace Database\Seeders;

use App\Enums\AreaEnum;
use App\Models\Block;
use App\Models\Matrix;
use App\Models\MatrixDetail;
use App\Models\MatrixRequirement;
use App\Models\Process;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MatrixRequirementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $areas = AreaEnum::cases();
        $ejes = Block::where('level_id', 1)->get();
        $matrix = Matrix::first();

        $QUESTIONS_PER_COMPONENT = 3;
        foreach ($areas as $area) {
            if ($area === AreaEnum::UNICA) {
                continue;
            }
            foreach ($ejes as $eje) {
                $children = Block::where('parent_block_id', $eje->id)->get();
                $total = $children->count() * $QUESTIONS_PER_COMPONENT;
                $eje_req = MatrixRequirement::create([
                    'matrix_id' => $matrix->id,
                    'area' => $area,
                    'block_id' => $eje->id,
                    'n_questions' => $total,
                ]);

                foreach ($children as $component) {
                    $comp_req = MatrixRequirement::create([
                        'matrix_id' => $matrix->id,
                        'area' => $area,
                        'block_id' => $component->id,
                        'n_questions' => $QUESTIONS_PER_COMPONENT,
                        'parent_id' => $eje_req->id,
                    ]);
                }
            }
        }

        MatrixRequirement::whereNotIn('block_id', [2, 5, 6])->delete();
    }
}
