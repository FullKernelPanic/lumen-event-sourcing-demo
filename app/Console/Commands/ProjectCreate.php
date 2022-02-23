<?php

declare(strict_types=1);


namespace App\Console\Commands;

use Domain\Project\Command\Create;
use Illuminate\Console\Command;

class ProjectCreate extends Command
{
    protected $signature = 'project:create {name : Name of the project}';

    protected $description = 'Create a project';

    public function __construct(private Create $createCommand)
    {
        parent::__construct();
    }

    public function handle()
    {
        $name = $this->input->getArgument('name');

        $uuid = $this->createCommand->exec($name);

        $this->info('Project created : ' . $uuid->toString());
    }
}
