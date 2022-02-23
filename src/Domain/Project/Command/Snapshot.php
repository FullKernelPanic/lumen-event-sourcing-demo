<?php

declare(strict_types=1);

namespace Domain\Project\Command;

use Domain\Project\ProjectAggregateRootRepository;
use Domain\Project\ProjectId;

class Snapshot
{
    public function __construct(private ProjectAggregateRootRepository $aggregateRootRepository)
    {

    }

    public function exec(ProjectId $projectId): void
    {
        $this->aggregateRootRepository->snapshot($projectId);
    }
}
