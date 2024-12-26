<?php

namespace App\Application\UseCase\ListTasks;

use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Service\Time\TaskTimeCalculator;

class ListTasksUseCase
{
    public function __construct(
        private readonly TaskRepositoryInterface $taskRepository,
        private readonly TaskTimeCalculator $taskTimeCalculator
    ) {}

    public function execute(): ListTasksResponse
    {
        $tasks = $this->taskRepository->findAllWithTaskSessions();
        $calculatedTasks = $this->taskTimeCalculator->calculateTotalTimeForTasks($tasks);

        return new ListTasksResponse($calculatedTasks);
    }
}
