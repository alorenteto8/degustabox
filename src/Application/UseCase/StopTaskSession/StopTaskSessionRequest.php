<?php

namespace App\Application\UseCase\StopTaskSession;

class StopTaskSessionRequest
{
    private string $taskName;

    public function __construct(string $taskName)
    {
        $this->taskName = $taskName;
    }

    public function getTaskName(): string
    {
        return $this->taskName;
    }
}