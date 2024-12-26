<?php

namespace App\Domain\Repository;

use App\Domain\Entity\TaskSession;

interface TaskSessionRepositoryInterface
{
    public function save(TaskSession $taskSession): void;

    public function findByTaskId(int $taskId): array;

    public function findById(int $id): ?TaskSession;

    public function findByIdAndNotEndedTaskSession(int $taskId): ?TaskSession;

    public function getTodayWorkedHours(): array;

    public function remove(TaskSession $taskSession): void;
}
