<?php

namespace App\tests\Domain\Service\TaskSession;
use App\Domain\Entity\Task;
use App\Domain\Entity\TaskSession;
use App\Domain\Repository\TaskSessionRepositoryInterface;
use App\Domain\Service\TaskSession\TaskSessionStopper;
use PHPUnit\Framework\TestCase;

class TaskSessionStopperTest extends TestCase
{
    private TaskSessionRepositoryInterface $taskSessionRepositoryMock;
    private TaskSessionStopper $taskSessionStopper;

    protected function setUp(): void
    {
        $this->taskSessionRepositoryMock = $this->createMock(TaskSessionRepositoryInterface::class);
        $this->taskSessionStopper = new TaskSessionStopper($this->taskSessionRepositoryMock);
    }

    public function testStopSessionSuccess(): void
    {
        $task = $this->createMock(Task::class);
        $task->method('getId')->willReturn(1);

        $taskSession = $this->createMock(TaskSession::class);

        $this->taskSessionRepositoryMock
            ->expects($this->once())
            ->method('findByIdAndNotEndedTaskSession')
            ->with(1)
            ->willReturn($taskSession);

        $taskSession->expects($this->once())
            ->method('setEndTime');

        $this->taskSessionRepositoryMock
            ->expects($this->once())
            ->method('save')
            ->with($taskSession);

        $result = $this->taskSessionStopper->stopSession($task);

        $this->assertSame($taskSession, $result);
    }

    public function testStopSessionThrowsExceptionWhenNoActiveSession(): void
    {
        $task = $this->createMock(Task::class);
        $task->method('getId')->willReturn(1);

        $this->taskSessionRepositoryMock
            ->expects($this->once())
            ->method('findByIdAndNotEndedTaskSession')
            ->with(1)
            ->willReturn(null);

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('No active session found for task: 1');

        $this->taskSessionStopper->stopSession($task);
    }
}
