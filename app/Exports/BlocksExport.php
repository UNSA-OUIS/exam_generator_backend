<?php

namespace App\Exports;

use App\Models\Block;
use App\Models\ConfinementBlock;
use App\Models\MatrixDetail;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class BlocksExport implements FromCollection, WithHeadings
{
    protected $confinementId;

    public function __construct($confinementId)
    {
        $this->confinementId = $confinementId;
    }

    public function headings(): array
    {
        return ['CODIGO', 'NIVEL', 'BLOQUE', 'BLOQUE SUPERIOR', 'TOTAL'];
    }

    public function collection()
    {
        $rows = collect();

        $levelNames = [
            1 => 'EJE TEMATICO',
            2 => 'COMPONENTE',
            3 => 'TEMA',
            4 => 'SUBTEMA',
            5 => 'SUBSUBTEMA',
        ];

        $confinementBlocks = ConfinementBlock::with('block.parentBlock')
            ->where('confinement_id', $this->confinementId)
            ->get();

        foreach ($confinementBlocks as $cblock) {
            $block = $cblock->block;
            $total = $cblock->questions_to_do;
            $rows->push([
                $block->code,
                $levelNames[$block->level_id] ?? '',
                $block->name,
                $block->parentBlock ? $block->parentBlock->code : '',
                $total,
            ]);
        }

        return $rows;
    }
}
