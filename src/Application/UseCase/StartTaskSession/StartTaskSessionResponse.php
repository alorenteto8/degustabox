<?php

namespace App\Application\UseCase\StartTaskSession;

use Cassandra\Date;

class StartTaskSessionResponse
{
    private int $id;
    private string $taskName;
    private \DateTime $startTime;

    public function __construct(int $id, string $taskName, \DateTime $startTime)
    {
        $this->id = $id;
        $this->taskName = $taskName;
        $this->startTime = $startTime;
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
}
