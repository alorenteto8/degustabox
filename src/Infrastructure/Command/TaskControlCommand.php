<?php

namespace App\Infrastructure\Command;

use App\Application\UseCase\StartTaskSession\StartTaskSessionRequest;
use App\Application\UseCase\StartTaskSession\StartTaskSessionUseCase;
use App\Application\UseCase\StopTaskSession\StopTaskSessionRequest;
use App\Application\UseCase\StopTaskSession\StopTaskSessionUseCase;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;

class TaskControlCommand extends Command
{
    private StartTaskSessionUseCase $startTaskSessionUseCase;
    private StopTaskSessionUseCase $stopTaskSessionUseCase;

    public function __construct(
        StartTaskSessionUseCase $startTaskSessionUseCase,
        StopTaskSessionUseCase $stopTaskSessionUseCase
    ) {
        parent::__construct();
        $this->startTaskSessionUseCase = $startTaskSessionUseCase;
        $this->stopTaskSessionUseCase = $stopTaskSessionUseCase;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:task-control')
            ->setDescription('Start or stop a task.')
            ->addArgument('action', InputArgument::REQUIRED, 'Action to perform (start, end)')
            ->addArgument('taskName', InputArgument::REQUIRED, 'Name of the task')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $action = $input->getArgument('action');
        $taskName = $input->getArgument('taskName');

        switch ($action) {
            case 'start':
                $startTaskSessionRequest = new StartTaskSessionRequest($taskName);
                $this->startTaskSessionUseCase->execute($startTaskSessionRequest);
                $output->writeln("<info>Task '{$taskName}' started successfully!</info>");
                return Command::SUCCESS;

            case 'end':
                $stopTaskSessionRequest = new StopTaskSessionRequest($taskName);
                $this->stopTaskSessionUseCase->execute($stopTaskSessionRequest);
                $output->writeln("<info>Task '{$taskName}' stopped successfully!</info>");
                return Command::SUCCESS;

            default:
                $output->writeln('<error>Invalid action. Use "start" or "end".</error>');
                return Command::INVALID;
        }
    }
}
