<?php

declare(strict_types=1);

namespace Domain\Project;

use Domain\Project\Aggregate\ProjectAggregateRoot;
use EventSauce\EventSourcing\Snapshotting\AggregateRootRepositoryWithSnapshotting;

class ProjectAggregateRootRepository
{
    public function __construct(private AggregateRootRepositoryWithSnapshotting $aggregateRootRepository)
    {
    }

    public function create(): ProjectAggregateRoot
    {
        return new ProjectAggregateRoot(new ProjectId());
    }

    public function get(ProjectId $projectId): ProjectAggregateRoot
    {
        return $this->aggregateRootRepository->retrieveFromSnapshot($projectId);
    }

    public function persist(ProjectAggregateRoot $aggregateRoot): void
    {
        $this->aggregateRootRepository->persist($aggregateRoot);
    }

    public function snapshot(ProjectId $projectId)
    {
        $this->aggregateRootRepository->storeSnapshot($this->aggregateRootRepository->retrieve($projectId));
    }
}
