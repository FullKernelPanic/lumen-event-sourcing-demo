<?php

declare(strict_types=1);


namespace App\Console\Commands;

use App\Aggregates\Project\ProjectAggregateRoot;
use App\Aggregates\Project\ProjectId;
use App\Repositories\ProjectAggregateRootRepository;
use Illuminate\Console\Command;

class ProjectRename extends Command
{
    protected $signature = 'project:rename {uuid : Project uuid} {name : Project new name}';

    protected $description = 'Rename a project';

    public function __construct(private ProjectAggregateRootRepository $aggregateRootRepository)
    {
        parent::__construct();
    }

    public function handle()
    {
        $uuid = $this->input->getArgument('uuid');
        $name = $this->input->getArgument('name');

        $projectId = ProjectId::fromString($uuid);

        /** @var ProjectAggregateRoot $projectRoot */
        $projectRoot = $this->aggregateRootRepository->get($projectId);

        $projectRoot->rename($name);

        $this->aggregateRootRepository->persist($projectRoot);
    }
}
