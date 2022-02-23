<?php

declare(strict_types=1);

namespace Domain\Project\Command;

use Domain\Project\ProjectAggregateRootRepository;
use Domain\Project\Contract\UniqueProjectNameGuardInterface;
use Domain\Project\Exception\ProjectNameNotUnique;
use Domain\Project\ProjectId;

class Create
{
    public function __construct(
        private ProjectAggregateRootRepository $aggregateRootRepository,
        private UniqueProjectNameGuardInterface $uniqueProjectNameGuard
    ) {
    }

    public function exec(string $name): ProjectId
    {
        $this->assureProjectNameIsUnique($name);

        $projectRoot = $this->aggregateRootRepository->create();
        $projectRoot->create($name);
        $this->aggregateRootRepository->persist($projectRoot);

        return $projectRoot->aggregateRootId();
    }

    private function assureProjectNameIsUnique(string $name): void
    {
        if (!$this->uniqueProjectNameGuard->isUnique($name)) {
            throw new ProjectNameNotUnique(sprintf('Project with name `%s` already exists', $name));
        }
    }
}
