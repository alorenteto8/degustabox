<?php

namespace App\Domain\Service\Time;

class TaskTimeCalculator
{
    public function calculateTotalTimeForTasks($tasks): array
    {
        foreach ($tasks as $task) {
            $totalTime = 0;

            foreach ($task->getSessions() as $taskSession) {
                if ($taskSession->getStartTime() && $taskSession->getEndTime()) {
                    $startTime = $taskSession->getStartTime();
                    $endTime = $taskSession->getEndTime();

                    $totalTime += $endTime->getTimestamp() - $startTime->getTimestamp();
                }
            }

            $task->setTotalTime($totalTime);
        }

        return $tasks;
    }
}
