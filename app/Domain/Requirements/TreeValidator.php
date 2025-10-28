<?php

namespace App\Domain\Requirements;

use App\Models\Block;
use Illuminate\Database\Eloquent\Model;

class TreeValidator
{
    public function validateParentRelation(?Model $parent, array $data, string $ownerKey)
    {
        if (!$parent) {
            throw new \InvalidArgumentException('El requerimiento superior no está definido.');
        }

        if (!isset($data[$ownerKey]) || $parent->{$ownerKey} !== $data[$ownerKey]) {
            throw new \InvalidArgumentException('El requerimiento superior no pertenece al mismo recurso.');
        }

        if (isset($data['area']) && $parent->area && $data['area'] !== $parent->area->value) {
            throw new \InvalidArgumentException('El área no coincide con el requerimiento superior.');
        }
    }

    // $parent: requerimiento superior (puede ser null)
    // $data: datos del nuevo requerimiento (incluye block_id y difficulty)
    // $block: bloque del nuevo requerimiento
    // $modelClass: clase del modelo del requerimiento (MatrixRequirement o Confinement
    public function validateDifficultyConsistency(?Model $parent, array $data, Block $block, string $modelClass)
    {
        if (!$parent) return; // root node

        $hasChildrenWithDifficulty = $modelClass::where('parent_id', $parent->id)
            ->whereNotNull('difficulty')->exists();
        $hasChildrenWithoutDifficulty = $modelClass::where('parent_id', $parent->id)
            ->whereNull('difficulty')->exists();

        if ($data['difficulty'] === null && $hasChildrenWithDifficulty) {
            throw new \InvalidArgumentException('El requerimiento ya fue dividido por dificultad, no puede dividir por subnivel.');
        }
        if ($data['difficulty'] !== null && $hasChildrenWithoutDifficulty) {
            throw new \InvalidArgumentException('El requerimiento ya fue dividido por subnivel, no se puede dividir por dificultad.');
        }

        if ($block->id === $parent->block_id) {
            if ($parent->difficulty) {
                throw new \InvalidArgumentException('El requerimiento superior ya está dividido por dificultad.');
            }
            if ($data['difficulty'] === null) {
                throw new \InvalidArgumentException('Debe especificar dificultad para dividir en el mismo bloque.');
            }
        } elseif ($block->parent_block_id === $parent->block_id) {
            // Lower block => normal division
            if ($parent->difficulty === null && $data['difficulty'] !== null) {
                throw new \InvalidArgumentException(
                    'Un requerimiento sin dificultad solo puede dividirse por dificultad en el mismo bloque.'
                );
            }
            if (
                $parent->difficulty !== null &&
                $data['difficulty'] !== $parent->difficulty->value
            ) {
                throw new \InvalidArgumentException('La dificultad no coincide con el requerimiento superior.');
            }
        } else {
            throw new \InvalidArgumentException(
                'El bloque debe ser el mismo o un subnivel del requerimiento superior.'
            );
        }
    }

    public function validateBlockHierarchy(Model $parent, Block $block)
    {
        if ($block->parent_block_id !== $parent->block_id) {
            throw new \InvalidArgumentException('El bloque no pertenece jerárquicamente al bloque superior.');
        }
    }

    // Valida que la suma de preguntas de los hermanos mas la del nuevo no exceda las del padre
    public function validateQuestionTotals(Model $parent, array $data, string $modelClass)
    {
        $sum = $modelClass::where('parent_id', $parent->id)->sum('n_questions');
        if (($data['n_questions'] ?? 0) + $sum > $parent->n_questions) {
            throw new \InvalidArgumentException('Sobrepasa el número de preguntas permitido.');
        }
    }

    public function validateUpdateConsistency(Model $requirement, array $data, string $modelClass)
    {
        $parent = $requirement->parent_id ? $modelClass::find($requirement->parent_id) : null;
        $childrenSum = $modelClass::where('parent_id', $requirement->id)->sum('n_questions');

        if (isset($data['n_questions'])) {
            if ($parent) {
                $siblingsSum = $modelClass::where('parent_id', $parent->id)
                    ->where('id', '!=', $requirement->id)
                    ->sum('n_questions');
                if ($data['n_questions'] + $siblingsSum > $parent->n_questions) {
                    throw new \InvalidArgumentException('Sobrepasa el número de preguntas del padre.');
                }
            }

            if ($data['n_questions'] < $childrenSum) {
                throw new \InvalidArgumentException('Número de preguntas es menor a la suma de los hijos.');
            }
        }
    }
}
