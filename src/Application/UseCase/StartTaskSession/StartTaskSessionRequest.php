<?php

namespace App\Application\UseCase\StartTaskSession;


use DateTime;

class StartTaskSessionRequest
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
