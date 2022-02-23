<?php

declare(strict_types=1);


namespace Domain\Project\Command;

use Domain\Project\ProjectAggregateRootRepository;
use Domain\Project\Aggregate\ProjectAggregateRoot;
use Domain\Project\ProjectId;

class Rename
{
    public function __construct(private ProjectAggregateRootRepository $aggregateRootRepository)
    {
    }

    public function exec(ProjectId $id, string $name): void
    {
        /** @var ProjectAggregateRoot $projectRoot */
        $projectRoot = $this->aggregateRootRepository->get($id);

        $projectRoot->rename($name);

        $this->aggregateRootRepository->persist($projectRoot);
    }
}
