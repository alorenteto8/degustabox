<?php

namespace App\Application\UseCase\StopTaskSession;

class StopTaskSessionResponse
{
    private int $id;
    private string $taskName;
    private \DateTime $startTime;
    private \DateTime $endTime;

    public function __construct(int $id, string $taskName, \DateTime $startTime, \DateTime $endTime)
    {
        $this->id = $id;
        $this->taskName = $taskName;
        $this->startTime = $startTime;
        $this->endTime = $endTime;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTaskName(): string
    {
        return $this->taskName;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function getEndTime(): \DateTime
    {
        return $this->endTime;
    }
}