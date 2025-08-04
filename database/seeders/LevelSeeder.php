<?php

namespace Database\Seeders;

use App\Models\Block;
use App\Models\Level;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $ejes_level = Level::create([
            'stage' => 1,
            'name' => 'EJE TEMATICO',
        ]);

        $ejes_componentes = ['APTITUD ACADEMICA' => ['RAZONAMIENTO LOGICO', 'COMPRENSION LECTORA'], 'MATEMATICA' => ['GEOMETRIA', 'TRIGONOMETRIA'], 'IDIOMA EXTRANJERO' => ['GRAMATICA', 'LECTURA']];

        $componentes_level = Level::create([
            'stage' => 2,
            'name' => 'COMPONENTE',
        ]);

        foreach ($ejes_componentes as $eje => $componentes) {
            $children_count = Block::where('level_id', $ejes_level->id)->count();
            $eje_code = str_pad($children_count + 1, 2, '0', STR_PAD_LEFT);
            $block_eje = Block::create([
                'level_id' => $ejes_level->id,
                'code' => $eje_code,
                'name' => $eje,
                'parent_block_id' => null
            ]);

            foreach ($componentes as $componente) {
                $children_count = Block::where('parent_block_id', $block_eje->id)->count();
                $componente_code = $eje_code . str_pad($children_count + 1, 2, '0', STR_PAD_LEFT);
                Block::create([
                    'level_id' => $componentes_level->id,
                    'code' => $componente_code,
                    'name' => $componente,
                    'parent_block_id' => $block_eje->id
                ]);
            }
        }
    }
}
