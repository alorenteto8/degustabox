<?php

namespace App\Tests\Application\UseCase\ListTasks;

use App\Application\UseCase\ListTasks\ListTasksResponse;
use App\Application\UseCase\ListTasks\ListTasksUseCase;
use App\Domain\Repository\TaskRepositoryInterface;
use App\Domain\Service\Time\TaskTimeCalculator;
use PHPUnit\Framework\TestCase;

class ListTasksUseCaseTest extends TestCase
{
    private $taskRepositoryMock;
    private $taskTimeCalculatorMock;
    private $listTasksUseCase;

    protected function setUp(): void
    {
        $this->taskRepositoryMock = $this->createMock(TaskRepositoryInterface::class);
        $this->taskTimeCalculatorMock = $this->createMock(TaskTimeCalculator::class);

        $this->listTasksUseCase = new ListTasksUseCase(
            $this->taskRepositoryMock,
            $this->taskTimeCalculatorMock
        );
    }

    public function testExecute()
    {
        $mockedTasks = ['task1', 'task2'];
        $this->taskRepositoryMock
            ->expects($this->once())
            ->method('findAllWithTaskSessions')
            ->willReturn($mockedTasks);

        $this->taskTimeCalculatorMock
            ->expects($this->once())
            ->method('calculateTotalTimeForTasks')
            ->with($mockedTasks)
            ->willReturn($mockedTasks);

        $response = $this->listTasksUseCase->execute();

        $this->assertInstanceOf(ListTasksResponse::class, $response);
        $this->assertSame($mockedTasks, $response->getTasks());
    }
}
