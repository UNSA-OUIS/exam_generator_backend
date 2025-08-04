<?php

namespace Database\Seeders;

use App\Enums\AreaEnum;
use App\Models\Block;
use App\Models\Matrix;
use App\Models\MatrixDetail;
use App\Models\Process;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class MatrixDetailSeeder extends Seeder
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
            if ($area === AreaEnum::TODAS) {
                continue;
            }
            foreach ($ejes as $eje) {
                $children = Block::where('parent_block_id', $eje->id)->get();
                $total = $children->count() * $QUESTIONS_PER_COMPONENT;
                $detail = MatrixDetail::create([
                    'matrix_id' => $matrix->id,
                    'area' => $area,
                    'block_id' => $eje->id,
                    'difficulty' => 'FACIL',
                    'questions_required' => $total,
                    'questions_to_do' => $total * 5
                ]);

                foreach ($children as $component) {
                    $detail2 = MatrixDetail::create([
                        'matrix_id' => $matrix->id,
                        'area' => $area,
                        'block_id' => $component->id,
                        'difficulty' => 'FACIL',
                        'questions_required' => $QUESTIONS_PER_COMPONENT,
                        'questions_to_do' => $QUESTIONS_PER_COMPONENT * 5
                    ]);
                }
            }
        }
    }
}
