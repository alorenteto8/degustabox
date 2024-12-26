<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\ListTasks\ListTasksUseCase;
use App\Infrastructure\Presenter\TaskListPresenter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ListTasksController extends AbstractController
{
    public function __construct(
        private readonly ListTasksUseCase $listTasksUseCase,
        private readonly TaskListPresenter $taskPresenter
    ) {}

    #[Route('/api/task/list', name: 'list_task', methods: ['GET'])]
    public function listTasks(): JsonResponse
    {
        try {
            $listTaskResponse = $this->listTasksUseCase->execute();
            $tasksWithSessions = $listTaskResponse->getTasks();
            $taskData = $this->taskPresenter->transform($tasksWithSessions);

            return new JsonResponse($taskData);

        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }

    }
}
