<?php

namespace App\Repository;

use App\Entity\TaskItem;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TaskItem|null find($id, $lockMode = null, $lockVersion = null)
 * @method TaskItem|null findOneBy(array $criteria, array $orderBy = null)
 * @method TaskItem[]    findAll()
 * @method TaskItem[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TaskItemRepository extends ServiceEntityRepository
{
    protected EntityManagerInterface $entityManager;

    /** @codeCoverageIgnore */
    public function __construct(ManagerRegistry $registry, EntityManagerInterface $entityManager)
    {
        parent::__construct($registry, TaskItem::class);
        $this->entityManager = $entityManager;
    }

    public function persist(TaskItem $entity): TaskItem
    {
        $this->entityManager->persist($entity);
        $this->entityManager->flush();
        return $entity;
    }
}
