<?php

namespace App\Domain\Service\Task;

use App\Domain\Entity\Task;
use App\Domain\Repository\TaskRepositoryInterface;

class TaskCreator
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function createTask(string $taskName): Task
    {
        $task = new Task();
        $task->setName($taskName);
        $task->setCreatedAt();
        $task->setUpdatedAt();

        $this->taskRepository->save($task);

        return $task;
    }
}
