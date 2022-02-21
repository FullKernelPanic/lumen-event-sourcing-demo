<?php

declare(strict_types=1);


namespace App\Console\Commands;

use App\Aggregates\Project\ProjectAggregateRoot;
use App\Aggregates\Project\ProjectId;
use App\Repositories\ProjectAggregateRootRepository;
use Illuminate\Console\Command;

class ProjectDelete extends Command
{
    protected $signature = 'project:delete {uuid : Project uuid}';

    protected $description = 'Delete a project';

    public function __construct(private ProjectAggregateRootRepository $aggregateRootRepository)
    {
        parent::__construct();
    }

    public function handle()
    {
        $uuid = $this->input->getArgument('uuid');

        $projectId = ProjectId::fromString($uuid);

        /** @var ProjectAggregateRoot $projectRoot */
        $projectRoot = $this->aggregateRootRepository->get($projectId);

        $projectRoot->delete();

        $this->aggregateRootRepository->persist($projectRoot);
    }
}