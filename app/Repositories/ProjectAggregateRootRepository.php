<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Aggregates\Project\ProjectAggregateRoot;
use App\Aggregates\Project\ProjectId;
use EventSauce\EventSourcing\AggregateRootRepository;

class ProjectAggregateRootRepository
{
    public function __construct(private AggregateRootRepository $aggregateRootRepository)
    {
    }

    public function create(): ProjectAggregateRoot
    {
        return new ProjectAggregateRoot(new ProjectId());
    }

    public function get(ProjectId $projectId): ProjectAggregateRoot
    {
        return $this->aggregateRootRepository->retrieve($projectId);
    }

    public function persist(ProjectAggregateRoot $aggregateRoot): void
    {
        $this->aggregateRootRepository->persist($aggregateRoot);
    }
}
