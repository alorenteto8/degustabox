<?php

namespace App\Infrastructure\Doctrine\Repository;

use App\Domain\Entity\TaskSession;
use App\Domain\Repository\TaskSessionRepositoryInterface;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class DoctrineTaskSessionRepository extends ServiceEntityRepository implements TaskSessionRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TaskSession::class);
    }

    public function save(TaskSession $taskSession): void
    {
        $this->getEntityManager()->persist($taskSession);
        $this->getEntityManager()->flush();
    }

    public function findByTaskId(int $taskId): array
    {
        return $this->findBy(['task' => $taskId]);
    }

    public function findById(int $id): ?TaskSession
    {
        return $this->find($id);
    }

    public function getTodayWorkedHours():  array
    {
        $today = new \DateTime();
        $todayStart = (clone $today)->setTime(0, 0, 0);
        $todayEnd = (clone $today)->setTime(23, 59, 59);

        $qb = $this->createQueryBuilder('ts')
            ->where('ts.startTime >= :todayStart')
            ->andWhere('ts.endTime <= :todayEnd')
            ->andWhere('ts.endTime IS NOT NULL')
            ->setParameter('todayStart', $todayStart->format('Y-m-d H:i:s'))
            ->setParameter('todayEnd', $todayEnd->format('Y-m-d H:i:s'))
            ->getQuery();

        return $qb->getResult();

    }

    public function findByIdAndNotEndedTaskSession(int $taskId): ?TaskSession
    {
        return $this->findOneBy(['task' => $taskId, 'endTime' => null]);
    }

    public function remove(TaskSession $taskSession): void
    {
        $this->getEntityManager()->remove($taskSession);
        $this->getEntityManager()->flush();
    }
}
