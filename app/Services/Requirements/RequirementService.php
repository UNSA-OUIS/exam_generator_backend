<?php

namespace App\Services\Requirements;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\QueryException;
use App\Domain\Requirements\TreeValidator;

abstract class RequirementService
{
    protected string $modelClass;
    protected string $ownerKey; // new
    protected TreeValidator $validator;

    public function __construct(TreeValidator $validator)
    {
        $this->validator = $validator;
    }

    protected function model(): Model
    {
        return app($this->modelClass);
    }

    public function store(array $data): Model
    {
        $parent = $this->model()::find($data['parent_id']);
        $block  = \App\Models\Block::findOrFail($data['block_id']);

        $this->validator->validateParentRelation($parent, $data, $this->ownerKey);
        if (array_key_exists('difficulty', $data)) {
            $this->validator->validateDifficultyConsistency($parent, $data, $block, $this->modelClass);
        } else {
            $this->validator->validateBlockHierarchy($parent, $block);
        }
        $this->validator->validateQuestionTotals($parent, $data, $this->modelClass);

        try {
            return $this->model()::create($data);
        } catch (QueryException $e) {
            if ($e->getCode() === '23505') {
                throw new \RuntimeException('Requerimiento duplicado detectado.', 409);
            }
            throw $e;
        }
    }

    public function update(Model $requirement, array $data): Model
    {
        $this->validator->validateUpdateConsistency($requirement, $data, $this->modelClass);
        $requirement->update($data);
        return $requirement;
    }
}
