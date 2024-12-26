<?php

namespace App\Domain\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: 'App\Infrastructure\Doctrine\Repository\DoctrineTaskRepository')]
class Task
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: 'AUTO')]
    #[ORM\Column(type: 'integer')]
    private int $id;

    #[ORM\Column(type: 'string', length: 255)]
    private string $name;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $createdAt;

    #[ORM\Column(type: 'datetime')]
    private ?\DateTime $updatedAt;

    #[ORM\OneToMany(targetEntity: TaskSession::class, mappedBy: 'task', cascade: ['persist', 'remove'], orphanRemoval: true)]
    private Collection $sessions;

    private float $totalTime;

    public function __construct()
    {
        $this->sessions = new ArrayCollection();
    }

    public function setId(int $id): void
    {
        $this->id = $id;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;
        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): ?\DateTime
    {
        return $this->updatedAt;
    }

    public function getSessions(): Collection
    {
        return $this->sessions;
    }

    public function addSession(TaskSession $session): self
    {
        if (!$this->sessions->contains($session)) {
            $this->sessions->add($session);
            $session->setTask($this);
        }
        return $this;
    }

    public function removeSession(TaskSession $session): self
    {
        if ($this->sessions->removeElement($session)) {
            // Set the owning side to null (unless already changed)
            if ($session->getTask() === $this) {
                $session->setTask(null);
            }
        }
        return $this;
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

    public function setTotalTime(float $totalTime): void
    {
        $this->totalTime = $totalTime;
    }

    public function getTotalTime(): float
    {
        return $this->totalTime;
    }
}
