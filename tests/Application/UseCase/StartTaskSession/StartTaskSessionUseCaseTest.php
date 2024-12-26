<?php

namespace App\Tests\Application\UseCase\StartTaskSession;
use App\Application\UseCase\StartTaskSession\StartTaskSessionRequest;
use App\Application\UseCase\StartTaskSession\StartTaskSessionResponse;
use App\Application\UseCase\StartTaskSession\StartTaskSessionUseCase;
use App\Domain\Entity\Task;
use App\Domain\Entity\TaskSession;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Service\Task\TaskCreator;
use App\Domain\Service\TaskSession\TaskSessionCreator;
use PHPUnit\Framework\TestCase;

class StartTaskSessionUseCaseTest extends TestCase
{
    public function testExecuteWithExistingTask(): void
    {
        $taskRepositoryMock = $this->createMock(TaskRepositoryInterface::class);
        $taskCreatorMock = $this->createMock(TaskCreator::class);
        $taskSessionCreatorMock = $this->createMock(TaskSessionCreator::class);

        $task = new Task();
        $task->setName('Test Task');
        $task->setId(1);

        $taskSession = new TaskSession();
        $taskSession->setStartTime();

        $taskRepositoryMock->method('findByName')
            ->with('Test Task')
            ->willReturn($task);

        $taskSessionCreatorMock->method('createTaskSession')
            ->with($task)
            ->willReturn($taskSession);

        $useCase = new StartTaskSessionUseCase(
            $taskCreatorMock,
            $taskSessionCreatorMock,
            $taskRepositoryMock
        );

        $request = new StartTaskSessionRequest('Test Task');

        $response = $useCase->execute($request);

        $this->assertInstanceOf(StartTaskSessionResponse::class, $response);
        $this->assertEquals($task->getId(), $response->getId());
        $this->assertEquals($task->getName(), $response->getTaskName());
        $this->assertEquals($taskSession->getStartTime(), $response->getStartTime());
    }

    public function testExecuteWithNewTask(): void
    {
        $taskRepositoryMock = $this->createMock(TaskRepositoryInterface::class);
        $taskCreatorMock = $this->createMock(TaskCreator::class);
        $taskSessionCreatorMock = $this->createMock(TaskSessionCreator::class);

        $task = new Task();
        $task->setName('New Task');
        $task->setId(2);

        $taskSession = new TaskSession();
        $taskSession->setStartTime();

        $taskRepositoryMock->method('findByName')
            ->with('New Task')
            ->willReturn(null);

        $taskCreatorMock->method('createTask')
            ->with('New Task')
            ->willReturn($task);

        $taskSessionCreatorMock->method('createTaskSession')
            ->with($task)
            ->willReturn($taskSession);

        $useCase = new StartTaskSessionUseCase(
            $taskCreatorMock,
            $taskSessionCreatorMock,
            $taskRepositoryMock
        );

        $request = new StartTaskSessionRequest('New Task');

        $response = $useCase->execute($request);

        $this->assertInstanceOf(StartTaskSessionResponse::class, $response);
        $this->assertEquals($task->getId(), $response->getId());
        $this->assertEquals($task->getName(), $response->getTaskName());
        $this->assertEquals($taskSession->getStartTime(), $response->getStartTime());
    }
}
