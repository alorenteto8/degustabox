<?php

namespace App\Tests\Application\UseCase\StopTaskSession;

use App\Application\UseCase\StopTaskSession\StopTaskSessionRequest;
use App\Application\UseCase\StopTaskSession\StopTaskSessionResponse;
use App\Application\UseCase\StopTaskSession\StopTaskSessionUseCase;
use App\Domain\Entity\Task;
use App\Domain\Entity\TaskSession;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Service\TaskSession\TaskSessionStopper;
use PHPUnit\Framework\TestCase;

class StopTaskSessionUseCaseTest extends TestCase
{
    private TaskRepositoryInterface $taskRepositoryMock;
    private TaskSessionStopper $taskSessionStopperMock;
    private StopTaskSessionUseCase $useCase;

    protected function setUp(): void
    {
        $this->taskRepositoryMock = $this->createMock(TaskRepositoryInterface::class);
        $this->taskSessionStopperMock = $this->createMock(TaskSessionStopper::class);
        $this->useCase = new StopTaskSessionUseCase(
            $this->taskRepositoryMock,
            $this->taskSessionStopperMock
        );
    }

    public function testExecuteSuccess(): void
    {
        $taskName = 'Sample Task';

        $task = $this->createMock(Task::class);
        $task->method('getId')->willReturn(1);
        $task->method('getName')->willReturn($taskName);

        $taskSession = $this->createMock(TaskSession::class);
        $startTime = new \DateTime('2024-01-01 08:00:00');
        $endTime = new \DateTime('2024-01-01 10:00:00');

        $taskSession->method('getStartTime')->willReturn($startTime);
        $taskSession->method('getEndTime')->willReturn($endTime);

        $this->taskRepositoryMock
            ->expects($this->once())
            ->method('findByName')
            ->with($taskName)
            ->willReturn($task);

        $this->taskSessionStopperMock
            ->expects($this->once())
            ->method('stopSession')
            ->with($task)
            ->willReturn($taskSession);

        $request = new StopTaskSessionRequest($taskName);

        $response = $this->useCase->execute($request);

        $this->assertInstanceOf(StopTaskSessionResponse::class, $response);
        $this->assertSame(1, $response->getId());
        $this->assertSame($taskName, $response->getTaskName());
        $this->assertSame($startTime, $response->getStartTime());
        $this->assertSame($endTime, $response->getEndTime());
    }

    public function testExecuteTaskNotFoundThrowsException(): void
    {
        $taskName = 'Nonexistent Task';

        $this->taskRepositoryMock
            ->expects($this->once())
            ->method('findByName')
            ->with($taskName)
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('Task not found with name: Nonexistent Task');

        $request = new StopTaskSessionRequest($taskName);

        $this->useCase->execute($request);
    }
}
