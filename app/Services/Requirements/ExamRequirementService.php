<?php

namespace App\Services\Requirements;

use App\Models\ExamRequirement;

class ExamRequirementService extends RequirementService
{
    protected string $modelClass = ExamRequirement::class;
    protected string $ownerKey = 'exam_id';
}
