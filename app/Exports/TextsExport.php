<?php

namespace App\Exports;

use App\Models\ConfinementText;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TextsExport implements FromCollection, WithHeadings
{
    protected $confinementId;

    public function __construct($confinementId)
    {
        $this->confinementId = $confinementId;
    }

    public function headings(): array
    {
        return ['CODIGO', 'BLOQUE', 'NRO TEXTOS', 'NRO PREGUNTAS', 'TOTAL'];
    }

    public function collection()
    {
        $rows = collect();

        $confinementTexts = ConfinementText::with('block')
            ->where('confinement_id', $this->confinementId)
            ->get();

        foreach ($confinementTexts as $cText) {
            $block = $cText->block;
            $nro_textos = $cText->texts_to_do;
            $nro_preguntas = $cText->questions_per_text;
            $total = $nro_textos * $nro_preguntas;
            $rows->push([
                $block->code,
                $block->name,
                $nro_textos,
                $nro_preguntas,
                $total,
            ]);
        }

        return $rows;
    }
}
