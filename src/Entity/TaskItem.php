<?php

namespace App\Entity;

use App\Repository\TaskItemRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=TaskItemRepository::class)
 * @ORM\Table(name="task_item")
 */
class TaskItem
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected int $id;

    /**
     * @ORM\ManyToOne(targetEntity="Task", inversedBy="taskItems", cascade={"persist"})
     * @ORM\JoinColumn(name="task_id", referencedColumnName="id", nullable=false, onDelete="CASCADE")
     */
    protected Task $task;

    /**
     * @ORM\Column(type="string", name="url", nullable=false)
     */
    protected string $url;

    public function __construct(Task $task, string $url)
    {
        $this->task = $task;
        $this->url = $url;
    }

    public function __toString(): string
    {
        return $this->url;
    }
}
