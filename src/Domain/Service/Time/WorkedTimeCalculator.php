<?php

namespace App\Domain\Service\Time;

use App\Domain\Repository\TaskSessionRepositoryInterface;

class WorkedTimeCalculator
{
    private const SECONDS_IN_HOUR = 3600;
    private const SECONDS_IN_MINUTE = 60;

    public function __construct(
        private readonly TaskSessionRepositoryInterface $taskSessionRepository,
        private readonly TimeFormatter $timeFormatter
    ) {}

    public function getTotalWorkedHoursToday(): string
    {
        $taskSessions = $this->taskSessionRepository->getTodayWorkedHours();

        if (empty($taskSessions)) {
            return "0h 0m 0s";
        }

        $totalSeconds = $this->calculateTotalSeconds($taskSessions);

        if ($totalSeconds === 0) {
            return "0h 0m 0s";
        }

        return $this->timeFormatter->format($totalSeconds);
    }

    private function calculateTotalSeconds(array $taskSessions): int
    {
        $totalSeconds = 0;

        foreach ($taskSessions as $session) {
            $start = $session?->getStartTime();
            $end = $session?->getEndTime();

            if ($start && $end) {
                $interval = $start->diff($end);
                $totalSeconds += ($interval->h * self::SECONDS_IN_HOUR) + ($interval->i * self::SECONDS_IN_MINUTE) + $interval->s;
            }
        }

        return $totalSeconds;
    }
}
