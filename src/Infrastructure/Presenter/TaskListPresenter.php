<?php

namespace App\Infrastructure\Presenter;

class TaskListPresenter
{
    public function transform(array $tasks): array
    {
        return array_map(function ($task) {
            return [
                'id' => $task->getId(),
                'name' => $task->getName(),
                'totalTime' => $task->getTotalTime(),
            ];
        }, $tasks);
    }
}
