<?php

namespace App\tests\Domain\Service\TaskSession;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskSession;
use App\Domain\Repository\TaskSessionRepositoryInterface;
use App\Domain\Service\TaskSession\TaskSessionCreator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TaskSessionCreatorTest extends TestCase
{
    private TaskSessionCreator $taskSessionCreator;
    private MockObject $taskSessionRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskSessionRepositoryMock = $this->createMock(TaskSessionRepositoryInterface::class);
        $this->taskSessionCreator = new TaskSessionCreator($this->taskSessionRepositoryMock);
    }

    public function testCreateTaskSession(): void
    {
        $taskMock = $this->createMock(Task::class);

        $this->taskSessionRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->callback(function (TaskSession $taskSession) use ($taskMock) {
                return $taskSession->getTask() === $taskMock
                    && $taskSession->getStartTime() instanceof \DateTime
                    && $taskSession->getCreatedAt() instanceof \DateTime
                    && $taskSession->getUpdatedAt() instanceof \DateTime;
            }));

        $createdTaskSession = $this->taskSessionCreator->createTaskSession($taskMock);

        $this->assertInstanceOf(TaskSession::class, $createdTaskSession);
        $this->assertSame($taskMock, $createdTaskSession->getTask());
        $this->assertInstanceOf(\DateTime::class, $createdTaskSession->getStartTime());
        $this->assertInstanceOf(\DateTime::class, $createdTaskSession->getCreatedAt());
        $this->assertInstanceOf(\DateTime::class, $createdTaskSession->getUpdatedAt());
    }
}
