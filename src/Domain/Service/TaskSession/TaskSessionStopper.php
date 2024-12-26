<?php

namespace App\Domain\Service\TaskSession;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskSession;
use App\Domain\Repository\TaskSessionRepositoryInterface;

class TaskSessionStopper
{
    public function __construct(private TaskSessionRepositoryInterface $taskSessionRepository) {}

    public function stopSession(Task $task): TaskSession
    {
        $taskSession = $this->taskSessionRepository->findByIdAndNotEndedTaskSession($task->getId());

        if (null === $taskSession) {
            throw new \RuntimeException('No active session found for task: ' . $task->getId());
        }

        $taskSession->setEndTime();
        $this->taskSessionRepository->save($taskSession);

        return $taskSession;
    }
}
