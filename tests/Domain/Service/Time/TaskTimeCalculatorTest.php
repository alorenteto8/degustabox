<?php
namespace App\Tests\Domain\Service;

use App\Domain\Entity\Task;
use App\Domain\Entity\TaskSession;
use App\Domain\Service\Time\TaskTimeCalculator;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\TestCase;

class TaskTimeCalculatorTest extends TestCase
{
    private TaskTimeCalculator $taskTimeCalculator;

    protected function setUp(): void
    {
        $this->taskTimeCalculator = new TaskTimeCalculator();
    }

    public function testCalculateTotalTimeForTasks(): void
    {
        $taskSessionMock1 = $this->createMock(TaskSession::class);
        $taskSessionMock2 = $this->createMock(TaskSession::class);

        $startTime1 = new \DateTime('2024-01-01 08:00:00');
        $endTime1 = new \DateTime('2024-01-01 09:00:00');
        $startTime2 = new \DateTime('2024-01-01 10:00:00');
        $endTime2 = new \DateTime('2024-01-01 11:00:00');

        $taskSessionMock1->method('getStartTime')->willReturn($startTime1);
        $taskSessionMock1->method('getEndTime')->willReturn($endTime1);
        $taskSessionMock2->method('getStartTime')->willReturn($startTime2);
        $taskSessionMock2->method('getEndTime')->willReturn($endTime2);

        $taskMock = $this->createMock(Task::class);
        $taskMock->method('getSessions')->willReturn(new ArrayCollection([$taskSessionMock1, $taskSessionMock2]));

        $taskMock->expects($this->once())
        ->method('setTotalTime')
        ->with($this->equalTo(7200));

        $this->taskTimeCalculator->calculateTotalTimeForTasks([$taskMock]);

        $this->assertTrue(true);
    }

    public function testCalculateTotalTimeForTasksWithNoSessions(): void
    {
        $taskMock = $this->createMock(Task::class);
        $taskMock->method('getSessions')->willReturn(new ArrayCollection());

        $tasks = $this->taskTimeCalculator->calculateTotalTimeForTasks([$taskMock]);

        $this->assertCount(1, $tasks);
        $this->assertEquals(0, $taskMock->getTotalTime());
    }
}

