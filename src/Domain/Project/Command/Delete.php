<?php

declare(strict_types=1);


namespace Domain\Project\Command;

use Domain\Project\ProjectAggregateRootRepository;
use Domain\Project\Aggregate\ProjectAggregateRoot;
use Domain\Project\ProjectId;

class Delete
{
    public function __construct(private ProjectAggregateRootRepository $aggregateRootRepository)
    {
    }

    public function exec(ProjectId $id): void
    {
        /** @var ProjectAggregateRoot $projectRoot */
        $projectRoot = $this->aggregateRootRepository->get($id);

        $projectRoot->delete();

        $this->aggregateRootRepository->persist($projectRoot);
    }
}
