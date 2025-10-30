<?php

namespace Database\Seeders;

use App\Enums\AreaEnum;
use App\Models\Block;
use App\Models\Confinement;
use App\Models\ConfinementRequirement;
use App\Models\Matrix;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class ConfinementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $confinement = Confinement::create([
            'id' => Str::uuid(),
            'name' => 'internamiento 2025 1',
            'start_date' => '2025-01-01',
            'end_date' => '2025-12-31',
        ]);

        $areas = AreaEnum::cases();
        $ejes = Block::where('level_id', 1)->get();

        $QUESTIONS_PER_COMPONENT = 3;
        foreach ($areas as $area) {
            if ($area !== AreaEnum::UNICA) {
                continue;
            }
            $root_req = ConfinementRequirement::create([
                'confinement_id' => $confinement->id,
                'block_id' => null,
                'n_questions' => $ejes->count() * $QUESTIONS_PER_COMPONENT * 2,
            ]);
            foreach ($ejes as $eje) {
                $children = Block::where('parent_block_id', $eje->id)->get();
                $total = $children->count() * $QUESTIONS_PER_COMPONENT;
                $eje_req = ConfinementRequirement::create([
                    'confinement_id' => $confinement->id,
                    'block_id' => $eje->id,
                    'n_questions' => $total,
                    'parent_id' => $root_req->id,
                ]);

                foreach ($children as $component) {
                    $comp_req = ConfinementRequirement::create([
                        'confinement_id' => $confinement->id,
                        'block_id' => $component->id,
                        'n_questions' => $QUESTIONS_PER_COMPONENT,
                        'parent_id' => $eje_req->id,
                    ]);
                }
            }
        }
    }
}
