<?php

namespace App\Infrastructure\Controller;

use App\Application\UseCase\StopTaskSession\StopTaskSessionRequest;
use App\Application\UseCase\StopTaskSession\StopTaskSessionUseCase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class StopTaskController extends AbstractController
{
    public function __construct(
        private StopTaskSessionUseCase $stopTaskSessionUseCase
    ) {}

    #[Route('/api/task/stop', name: 'stop_task', methods: ['POST'])]
    public function stopTask(Request $request): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if (!isset($data['taskName'])) {
            return new JsonResponse(['error' => 'Task Name is required'], JsonResponse::HTTP_BAD_REQUEST);
        }

        try {
            $stopTaskSessionRequest = new StopTaskSessionRequest($data['taskName']);
            $stopTaskSessionResponse = $this->stopTaskSessionUseCase->execute($stopTaskSessionRequest);


            return new JsonResponse([
                'taskId' => $stopTaskSessionResponse->getId(),
                'taskName' => $stopTaskSessionResponse->getTaskName(),
                'startTime' => $stopTaskSessionResponse->getStartTime(),
                'endTime' => $stopTaskSessionResponse->getEndTime(),
            ], JsonResponse::HTTP_CREATED);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
