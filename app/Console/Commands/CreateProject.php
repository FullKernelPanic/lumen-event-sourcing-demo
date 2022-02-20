<?php

declare(strict_types=1);


namespace App\Console\Commands;

use App\Dispatcher\RabbitMQMessageDispatcher;
use App\Aggregates\Project\ProjectRoot;
use App\Repositories\MessageRepository;
use EventSauce\EventSourcing\EventSourcedAggregateRootRepository;
use EventSauce\EventSourcing\Serialization\ConstructingMessageSerializer;
use Illuminate\Console\Command;
use Illuminate\Contracts\Events\Dispatcher;

class CreateProject extends Command
{
    protected $signature = 'project:create {name : Name of the project}';

    protected $description = 'Create a project';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        //new EventSourcedAggregateRootRepository(ProjectRoot::class, )
        $name = $this->input->getArgument('name');

        /** @var Dispatcher $dispatcher */
        $dispatcher = $this->getApplication()->getLaravel()->get('events');

        $messageSerializer = new ConstructingMessageSerializer();

        $repository = new EventSourcedAggregateRootRepository(
            ProjectRoot::class,
            new MessageRepository($messageSerializer),
            new RabbitMQMessageDispatcher($dispatcher, $messageSerializer)
        );

        $root = ProjectRoot::createProject($name);

        $repository->persist($root);
    }
}
