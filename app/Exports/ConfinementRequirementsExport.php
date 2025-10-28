<?php

namespace App\Exports;

use App\Models\ConfinementRequirement;
use App\Models\Level;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ConfinementRequirementsExport implements FromCollection, WithHeadings
{
    protected $confinementId;
    protected $levelNames;

    public function __construct($confinementId)
    {
        $this->confinementId = $confinementId;
        Level::get()->each(function ($level) {
            $this->levelNames[$level->id] = $level->name;
        });
    }

    public function headings(): array
    {
        return ['CODIGO', 'NIVEL', 'BLOQUE', 'BLOQUE SUPERIOR', 'DIFICULTAD', 'TOTAL'];
    }

    public function collection()
    {
        $rows = collect();

        $requirementRoot = ConfinementRequirement::where('confinement_id', $this->confinementId)
            ->where('parent_id', null)->firstOrFail();

        ConfinementRequirement::where('parent_id', $requirementRoot->id)
            ->with(['block'])
            ->orderBy('id')
            ->get()
            ->each(function ($requirement) use (&$rows) {
                $this->addRequirementRecursive($rows, $requirement, '');
            });

        return $rows;
    }

    protected function addRequirementRecursive(Collection &$rows, ConfinementRequirement $requirement, $parentCode)
    {
        $total = $requirement ? $requirement->n_questions : null;
        $block = $requirement->block;
        $levelName = $this->levelNames[$block->level_id] ?? '';

        $rows->push([
            $block->code,
            $levelName,
            $block->name,
            $parentCode,
            $requirement->difficulty->value ?? '',
            $total,
        ]);

        ConfinementRequirement::where('parent_id', $requirement->id)
            ->with(['block'])
            ->orderBy('id')
            ->get()
            ->each(function ($child_req) use (&$rows, $block) {
                $this->addRequirementRecursive($rows, $child_req, $block->code);
            });
    }
}
