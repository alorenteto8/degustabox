<?php

namespace App\Tests\Infrastructure\Presenter;

use App\Domain\Entity\Task;
use App\Infrastructure\Presenter\TaskListPresenter;
use PHPUnit\Framework\TestCase;

class TaskListPresenterTest extends TestCase
{
    public function testTransform(): void
    {
        $taskMock1 = $this->createMock(Task::class);
        $taskMock2 = $this->createMock(Task::class);

        $taskMock1->method('getId')->willReturn(1);
        $taskMock1->method('getName')->willReturn('Task 1');
        $taskMock1->method('getTotalTime')->willReturn(3600.00);

        $taskMock2->method('getId')->willReturn(2);
        $taskMock2->method('getName')->willReturn('Task 2');
        $taskMock2->method('getTotalTime')->willReturn(7200.00);

        $tasks = [$taskMock1, $taskMock2];

        $presenter = new TaskListPresenter();

        $result = $presenter->transform($tasks);

        $expected = [
            [
                'id' => 1,
                'name' => 'Task 1',
                'totalTime' => 3600.00,
            ],
            [
                'id' => 2,
                'name' => 'Task 2',
                'totalTime' => 7200.00,
            ],
        ];

        $this->assertEquals($expected, $result);
    }
}
