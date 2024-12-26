<?php

namespace App\Tests\Infrastructure\Controller;

use App\Application\UseCase\ListTasks\ListTasksResponse;
use App\Infrastructure\Controller\ListTasksController;
use App\Application\UseCase\ListTasks\ListTasksUseCase;
use App\Infrastructure\Presenter\TaskListPresenter;
use Symfony\Component\HttpFoundation\JsonResponse;
use PHPUnit\Framework\TestCase;
use PHPUnit\Framework\MockObject\MockObject;

class ListTasksControllerTest extends TestCase
{
    private MockObject|ListTasksUseCase $mockListTasksUseCase;
    private MockObject|TaskListPresenter $mockTaskListPresenter;
    private ListTasksController $controller;

    protected function setUp(): void
    {
        $this->mockListTasksUseCase = $this->createMock(ListTasksUseCase::class);
        $this->mockTaskListPresenter = $this->createMock(TaskListPresenter::class);

        $this->controller = new ListTasksController(
            $this->mockListTasksUseCase,
            $this->mockTaskListPresenter
        );
    }

    public function testListTasksSuccess(): void
    {
        $taskResponseMock = $this->createMock(ListTasksResponse::class);
        $taskResponseMock->method('getTasks')->willReturn([
            (object)[
                'getId' => 1,
                'getName' => 'Test Task',
                'getTotalTime' => 3600
            ]
        ]);

        $this->mockListTasksUseCase->method('execute')->willReturn($taskResponseMock);

        $this->mockTaskListPresenter->method('transform')->willReturn([
            [
                'id' => 1,
                'name' => 'Test Task',
                'totalTime' => 3600
            ]
        ]);

        $response = $this->controller->listTasks();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertCount(1, $responseData);
        $this->assertEquals(1, $responseData[0]['id']);
        $this->assertEquals('Test Task', $responseData[0]['name']);
    }

    public function testListTasksFailure(): void
    {
        $this->mockListTasksUseCase->method('execute')->willThrowException(new \Exception('An unexpected error occurred.'));

        $response = $this->controller->listTasks();

        $this->assertInstanceOf(JsonResponse::class, $response);
        $this->assertEquals(500, $response->getStatusCode());

        $responseData = json_decode($response->getContent(), true);
        $this->assertEquals('An unexpected error occurred.', $responseData['error']);
    }
}
