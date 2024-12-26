<?php
namespace App\tests\Domain\Service\Task;

use App\Domain\Entity\Task;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Service\Task\TaskCreator;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class TaskCreatorTest extends TestCase
{
    private TaskCreator $taskCreator;
    private MockObject $taskRepositoryMock;

    protected function setUp(): void
    {
        parent::setUp();

        $this->taskRepositoryMock = $this->createMock(TaskRepositoryInterface::class);
        $this->taskCreator = new TaskCreator($this->taskRepositoryMock);
    }

    public function testCreateTask(): void
    {
        $taskName = 'Test Task';

        $this->taskRepositoryMock->expects($this->once())
            ->method('save')
            ->with($this->callback(function (Task $task) use ($taskName) {
                return $task->getName() === $taskName;
            }));

        $createdTask = $this->taskCreator->createTask($taskName);

        $this->assertInstanceOf(Task::class, $createdTask);
        $this->assertEquals($taskName, $createdTask->getName());
    }
}


