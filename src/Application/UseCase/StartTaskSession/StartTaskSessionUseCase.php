<?php
namespace App\Application\UseCase\StartTaskSession;

use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Service\Task\TaskCreator;
use App\Domain\Service\TaskSession\TaskSessionCreator;

class StartTaskSessionUseCase
{
    public function __construct(
        private TaskCreator $taskCreator,
        private TaskSessionCreator $taskSessionCreator,
        private TaskRepositoryInterface $taskRepository
    ) {}

    public function execute(StartTaskSessionRequest $request): StartTaskSessionResponse
    {
        $task = $this->taskRepository->findByName($request->getTaskName());

        if (null === $task) {
            $task = $this->taskCreator->createTask($request->getTaskName());
        }

        $taskSession = $this->taskSessionCreator->createTaskSession($task);

        return new StartTaskSessionResponse(
            $task->getId(),
            $task->getName(),
            $taskSession->getStartTime()
        );
    }
}