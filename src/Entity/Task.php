<?php

namespace App\Entity;

use App\Entity\TaskItem;
use App\Repository\TaskRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskRepository::class)
 * @ORM\Table(name="task")
 */
class Task
{
    public const TO_STRING = 'Crawl on %s';

    /**
     * @ORM\Id()
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\OneToMany(targetEntity="TaskItem", mappedBy="task", cascade={"all"}, orphanRemoval=true)
     */
    protected Collection $taskItems;

    /**
     * @ORM\Column(type="datetime", name="created_at")
     */
    protected DateTime $createdAt;

    public function __construct()
    {
        $this->taskItems = new ArrayCollection();
        $this->createdAt = new DateTime();
    }

    public function addTaskItem(TaskItem $item): bool
    {
        return $this->taskItems->add($item);
    }

    public function getTaskItems(): Collection
    {
        return $this->taskItems;
    }

    public function __toString(): string
    {
        return sprintf(self::TO_STRING, $this->createdAt->format('Y-m-d H:i:s'));
    }
}
