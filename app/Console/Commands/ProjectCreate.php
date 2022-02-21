<?php

declare(strict_types=1);


namespace App\Console\Commands;

use App\Repositories\ProjectAggregateRootRepository;
use Illuminate\Console\Command;

class ProjectCreate extends Command
{
    protected $signature = 'project:create {name : Name of the project}';

    protected $description = 'Create a project';

    public function __construct(private ProjectAggregateRootRepository $aggregateRootRepository)
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->input->getArgument('name');

        $root = $this->aggregateRootRepository->create();

        $root->create($name);

        $this->aggregateRootRepository->persist($root);
    }
}
