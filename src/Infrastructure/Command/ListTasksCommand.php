<?php

namespace App\Infrastructure\Command;

use App\Application\UseCase\ListTasks\ListTasksUseCase;
use App\Domain\Service\Time\TaskTimeCalculator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class ListTasksCommand extends Command
{
    private ListTasksUseCase $listTasksUseCase;
    private TaskTimeCalculator $taskTimeCalculator;

    public function __construct(ListTasksUseCase $listTasksUseCase, TaskTimeCalculator $taskTimeCalculator)
    {
        parent::__construct();
        $this->listTasksUseCase = $listTasksUseCase;
        $this->taskTimeCalculator = $taskTimeCalculator;
    }

    protected function configure(): void
    {
        $this
            ->setName('app:list-tasks')
            ->setDescription('List all tasks with their status, start time, end time, and total time.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $listTaskResponse = $this->listTasksUseCase->execute();
        $tasksWithSessions = $listTaskResponse->getTasks();

        if (empty($tasksWithSessions)) {
            $output->writeln('<info>No tasks found.</info>');
            return Command::SUCCESS;
        }

        $tasks = $this->taskTimeCalculator->calculateTotalTimeForTasks($tasksWithSessions);

        foreach ($tasks as $task) {
            $totalTime = $task->getTotalTime() ?? 'N/A';
            $output->writeln("<info>Task: {$task->getName()}, Total Time: {$totalTime}s</info>");
        }

        return Command::SUCCESS;
    }
}
