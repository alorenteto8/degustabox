<?php

namespace App\Domain\Service\TaskSession;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskSession;
use App\Domain\Repository\TaskSessionRepositoryInterface;

class TaskSessionCreator
{
    public function __construct(
        private TaskSessionRepositoryInterface $taskSessionRepository
    ) {}

    public function createTaskSession(Task $task): TaskSession
    {
        $taskSession = new TaskSession();
        $taskSession->setTask($task);
        $taskSession->setStartTime();
        $taskSession->setCreatedAt();
        $taskSession->setUpdatedAt();

        $this->taskSessionRepository->save($taskSession);

        return $taskSession;
    }
}