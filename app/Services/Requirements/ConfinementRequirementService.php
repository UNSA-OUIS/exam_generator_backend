<?php

namespace App\Services\Requirements;

use App\Models\ConfinementRequirement;

class ConfinementRequirementService extends RequirementService
{
    protected string $modelClass = ConfinementRequirement::class;
    protected string $ownerKey = 'confinement_id';
}
