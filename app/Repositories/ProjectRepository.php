<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Models\Project;
use Domain\Project\Contract\UniqueProjectNameGuardInterface;

class ProjectRepository implements UniqueProjectNameGuardInterface
{
    public function isUnique(string $name): bool
    {
        return Project::where('name', $name)->get()->isEmpty();
    }
}
