<?php

namespace App\Services\Requirements;

use App\Models\MatrixRequirement;

class MatrixRequirementService extends RequirementService
{
    protected string $modelClass = MatrixRequirement::class;
    protected string $ownerKey = 'matrix_id';
}
