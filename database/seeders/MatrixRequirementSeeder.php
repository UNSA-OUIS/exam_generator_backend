<?php

namespace Database\Seeders;

use App\Enums\AreaEnum;
use App\Models\Block;
use App\Models\Matrix;
use App\Models\MatrixRequirement;
use Illuminate\Database\Seeder;

class MatrixRequirementSeeder extends Seeder
{
    public function run(): void
    {
        $matrix = Matrix::first();

        // 🔹 Datos de requerimientos (según tu tabla)
        $data = [
            ['eje' => 'APTITUD ACADÉMICA', 'asignatura' => 'RAZONAMIENTO LÓGICO', 'ingenierias' => 4, 'biomedicas' => 4, 'sociales' => 4],
            ['eje' => 'APTITUD ACADÉMICA', 'asignatura' => 'RAZONAMIENTO MATEMÁTICO', 'ingenierias' => 5, 'biomedicas' => 5, 'sociales' => 5],
            ['eje' => 'APTITUD ACADÉMICA', 'asignatura' => 'RAZONAMIENTO VERBAL', 'ingenierias' => 4, 'biomedicas' => 4, 'sociales' => 4],
            ['eje' => 'APTITUD ACADÉMICA', 'asignatura' => 'COMPRENSIÓN LECTORA', 'ingenierias' => 5, 'biomedicas' => 5, 'sociales' => 5],
            ['eje' => 'MATEMÁTICA', 'asignatura' => 'ALGEBRA', 'ingenierias' => 4, 'biomedicas' => 3, 'sociales' => 3],
            ['eje' => 'MATEMÁTICA', 'asignatura' => 'ARITMÉTICA', 'ingenierias' => 4, 'biomedicas' => 3, 'sociales' => 3],
            ['eje' => 'MATEMÁTICA', 'asignatura' => 'GEOMETRÍA', 'ingenierias' => 4, 'biomedicas' => 3, 'sociales' => 3],
            ['eje' => 'MATEMÁTICA', 'asignatura' => 'TRIGONOMETRÍA', 'ingenierias' => 3, 'biomedicas' => 3, 'sociales' => 3],
            ['eje' => 'CIENCIAS SOCIALES', 'asignatura' => 'HISTORIA', 'ingenierias' => 4, 'biomedicas' => 5, 'sociales' => 8],
            ['eje' => 'CIENCIAS SOCIALES', 'asignatura' => 'GEOGRAFÍA', 'ingenierias' => 4, 'biomedicas' => 4, 'sociales' => 5],
            ['eje' => 'CIENCIA Y TECNOLOGÍA', 'asignatura' => 'QUIMICA', 'ingenierias' => 6, 'biomedicas' => 6, 'sociales' => 3],
            ['eje' => 'CIENCIA Y TECNOLOGÍA', 'asignatura' => 'BIOLOGIA', 'ingenierias' => 5, 'biomedicas' => 9, 'sociales' => 3],
            ['eje' => 'CIENCIA Y TECNOLOGÍA', 'asignatura' => 'FISICA', 'ingenierias' => 7, 'biomedicas' => 5, 'sociales' => 3],
            ['eje' => 'DESARROLLO PERSONAL, CIUDADANÍA Y CÍVICA', 'asignatura' => 'FILOSOFÍA', 'ingenierias' => 3, 'biomedicas' => 3, 'sociales' => 3],
            ['eje' => 'DESARROLLO PERSONAL, CIUDADANÍA Y CÍVICA', 'asignatura' => 'PSICOLOGÍA', 'ingenierias' => 4, 'biomedicas' => 4, 'sociales' => 5],
            ['eje' => 'DESARROLLO PERSONAL, CIUDADANÍA Y CÍVICA', 'asignatura' => 'CÍVICA', 'ingenierias' => 3, 'biomedicas' => 3, 'sociales' => 3],
            ['eje' => 'COMUNICACIÓN', 'asignatura' => 'LENGUAJE', 'ingenierias' => 4, 'biomedicas' => 4, 'sociales' => 8],
            ['eje' => 'COMUNICACIÓN', 'asignatura' => 'LITERATURA', 'ingenierias' => 3, 'biomedicas' => 3, 'sociales' => 5],
            ['eje' => 'IDIOMA EXTRANJERO', 'asignatura' => 'INGLÉS-LECTURA', 'ingenierias' => 2, 'biomedicas' => 2, 'sociales' => 2],
            ['eje' => 'IDIOMA EXTRANJERO', 'asignatura' => 'INGLÉS-GRAMÁTICA', 'ingenierias' => 2, 'biomedicas' => 2, 'sociales' => 2],
        ];

        // 🔹 Áreas disponibles (según tu enum)
        $areas = [
            'ingenierias' => AreaEnum::INGENIERIAS,
            'biomedicas' => AreaEnum::BIOMEDICAS,
            'sociales' => AreaEnum::SOCIALES,
        ];

        foreach ($areas as $key => $areaEnum) {
            // 🔸 Crear requerimiento raíz por área
            $root = MatrixRequirement::create([
                'matrix_id' => $matrix->id,
                'area' => $areaEnum,
                'block_id' => null,
                'n_questions' => collect($data)->sum($key),
                'parent_id' => null,
            ]);

            // 🔸 Agrupar por eje
            $grouped = collect($data)->groupBy('eje');

            foreach ($grouped as $ejeName => $componentes) {
                $ejeBlock = Block::where('name', strtoupper($ejeName))->first();
                if (!$ejeBlock) continue;

                // Total de preguntas por eje
                $totalEje = $componentes->sum($key);

                $ejeReq = MatrixRequirement::create([
                    'matrix_id' => $matrix->id,
                    'area' => $areaEnum,
                    'block_id' => $ejeBlock->id,
                    'n_questions' => $totalEje,
                    'parent_id' => $root->id,
                ]);

                // 🔸 Requerimientos por componente
                foreach ($componentes as $comp) {
                    $componentBlock = Block::where('name', strtoupper($comp['asignatura']))
                        ->where('parent_block_id', $ejeBlock->id)
                        ->first();

                    if (!$componentBlock) continue;

                    MatrixRequirement::create([
                        'matrix_id' => $matrix->id,
                        'area' => $areaEnum,
                        'block_id' => $componentBlock->id,
                        'n_questions' => $comp[$key],
                        'parent_id' => $ejeReq->id,
                    ]);
                }
            }
        }
    }
}
