<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\StartTaskSession\StartTaskSessionRequest;
use App\Application\UseCase\StartTaskSession\StartTaskSessionUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StartTaskController extends AbstractController
{
    public function __construct(
        private StartTaskSessionUseCase $startTaskSessionUseCase
    ) {}

    #[Route('/api/task/start', name: 'start_task', methods: ['POST'])]
    public function startTask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['taskName'])) {
            return new JsonResponse(['error' => 'Task Name is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $startTaskSessionRequest = new StartTaskSessionRequest($data['taskName']);
            $startTaskSessionResponse = $this->startTaskSessionUseCase->execute($startTaskSessionRequest);

            return new JsonResponse([
                'taskId' => $startTaskSessionResponse->getId(),
                'taskName' => $startTaskSessionResponse->getTaskName(),
                'startTime' => $startTaskSessionResponse->getStartTime(),
            ], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
