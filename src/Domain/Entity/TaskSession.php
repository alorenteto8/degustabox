<?php

namespace App\Domain\Entity;

use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Infrastructure\Doctrine\Repository\DoctrineTaskSessionRepository')]
class TaskSession
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private ?int $id = null;

    #[ORM\ManyToOne(targetEntity: Task::class, cascade: ['persist', 'remove'], inversedBy: 'sessions')]
    #[ORM\JoinColumn(name: 'task_id', referencedColumnName: 'id', onDelete: 'CASCADE')]
    private ?Task $task = null;

    #[ORM\Column(type: 'datetime')]
    private \DateTime $startTime;

    #[ORM\Column(name: "end_time", type: "datetime", nullable: true)]
    private ?\DateTime $endTime = null;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTime $createdAt;

    #[ORM\Column(type: 'datetime', nullable: false)]
    private ?\DateTime $updatedAt;

    // Getters and setters

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTask(): ?Task
    {
        return $this->task;
    }

    public function setTask(?Task $task): self
    {
        $this->task = $task;
        return $this;
    }

    public function getStartTime(): \DateTime
    {
        return $this->startTime;
    }

    public function setStartTime(): void
    {
        $this->startTime = new \DateTime;
    }

    public function getEndTime(): ?\DateTime
    {
        return $this->endTime;
    }

    public function setEndTime(): void
    {
        $this->endTime = new \DateTime();
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    #[ORM\PrePersist]
    public function setCreatedAt(): void
    {
        $this->createdAt = new \DateTime();
    }

    #[ORM\PrePersist, ORM\PreUpdate]
    public function setUpdatedAt(): void
    {
        $this->updatedAt = new \DateTime();
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }
}