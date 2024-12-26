<?php

namespace App\Application\UseCase\ListTasks;

class ListTasksResponse
{
    private array $tasks;

    public function __construct(array $tasks)
    {
        $this->tasks = $tasks;
    }

    public function getTasks(): array
    {
        return $this->tasks;
    }
}
