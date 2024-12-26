<?php

namespace App\Application\UseCase\StopTaskSession;

use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Service\TaskSession\TaskSessionStopper;

class StopTaskSessionUseCase
{
    public function __construct(
        private TaskRepositoryInterface $taskRepository,
        private TaskSessionStopper $taskSessionStopper
    ) {}

    public function execute(StopTaskSessionRequest $request): StopTaskSessionResponse
    {
        $task = $this->taskRepository->findByName($request->getTaskName());

        if (null === $task) {
            throw new \RuntimeException('Task not found with name: ' . $request->getTaskName());
        }

        $taskSession = $this->taskSessionStopper->stopSession($task);

        return new StopTaskSessionResponse(
            $task->getId(),
            $task->getName(),
            $taskSession->getStartTime(),
            $taskSession->getEndTime()
        );
    }
}
