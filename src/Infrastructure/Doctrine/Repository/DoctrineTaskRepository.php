<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\Task;
use App\Domain\Repository\TaskRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineTaskRepository extends ServiceEntityRepository implements TaskRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Task::class);
    }

    public function save(Task $task): void
    {
        $this->getEntityManager()->persist($task);
        $this->getEntityManager()->flush();
    }

    public function findById(int $id): ?Task
    {
        return $this->find($id);
    }

    public function findByName(string $name): ?Task
    {
        return $this->findOneBy(['name' => $name]);
    }

    public function findAll(): array
    {
        return $this->findAll();
    }

    public function findAllWithTaskSessions(): array
    {
        $queryBuilder = $this->createQueryBuilder('t')
            ->select('t', 'ts')
            ->leftJoin('t.sessions', 'ts')
            ->orderBy('t.createdAt', 'DESC');

        try {
            $query = $queryBuilder->getQuery();
            return $query->getResult();
        } catch (\Exception $e) {
            throw new \RuntimeException('Error fetching tasks with TaskSessions: ' . $e->getMessage());
        }
    }

    public function remove(Task $task): void
    {
        $this->getEntityManager()->remove($task);
        $this->getEntityManager()->flush();
    }
}