<?php

namespace App\Infrastructure\Controller;

use App\Domain\Service\Time\WorkedTimeCalculator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class WorkedHoursTodayController extends AbstractController
{
    public function __construct(
        private WorkedTimeCalculator $workedTimeCalculator
    ) {}

    #[Route('/api/task/hours', name: 'worked_hours', methods: ['GET'])]
    public function workedHours(): JsonResponse
    {
        try {
            $todayWorkedHours = $this->workedTimeCalculator->getTotalWorkedHoursToday();

            return new JsonResponse($todayWorkedHours);
        } catch (\Exception $e) {
            return new JsonResponse(['error' => $e->getMessage()], JsonResponse::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}