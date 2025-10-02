<?php

namespace App\Exports;

use App\Models\Block;
use App\Models\ConfinementBlock;
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

        $blocks = Block::whereNull('parent_block_id')->orderBy('code')->get();

        foreach ($blocks as $block) {
            $this->addBlockRecursive($rows, $block, null);
        }

        return $rows;
    }

    protected function addBlockRecursive(Collection &$rows, Block $block, $parentCode)
    {
        $levelNames = [
            1 => 'EJE TEMATICO',
            2 => 'COMPONENTE',
            3 => 'TEMA',
            4 => 'SUBTEMA',
            5 => 'SUBSUBTEMA',
        ];

        // 👇 Now using ConfinementBlock instead of MatrixDetail
        $confinementBlock = ConfinementBlock::where('confinement_id', $this->confinementId)
            ->where('block_id', $block->id)
            ->first();

        $total = $confinementBlock ? $confinementBlock->questions_to_do : null;

        $rows->push([
            $block->code,
            $levelNames[$block->level_id] ?? '',
            $block->name,
            $parentCode,
            $total,
        ]);

        $children = Block::where('parent_block_id', $block->id)
            ->orderBy('code')
            ->get();

        foreach ($children as $child) {
            $this->addBlockRecursive($rows, $child, $block->code);
        }
    }
}
