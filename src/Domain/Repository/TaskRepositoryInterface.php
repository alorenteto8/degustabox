<?php

namespace App\Domain\Repository;

use App\Domain\Entity\Task;

interface TaskRepositoryInterface
{
    public function save(Task $task): void;

    public function findById(int $id): ?Task;

    public function findByName(string $name): ?Task;

    public function findAll(): array;

    public function findAllWithTaskSessions(): array;

    public function remove(Task $task): void;
}
