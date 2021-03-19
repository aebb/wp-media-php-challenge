<?php

namespace App\Tests\Unit\Repository;

use App\Entity\TaskItem;
use App\Repository\TaskItemRepository;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Repository\TaskItemRepository */
class TaskItemRepositoryTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected TaskItemRepository $sut;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sut = $this->getMockBuilder(TaskItemRepository::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'deleteOlderThan',
                'findMostRecent',
                'persist'
            ])
            ->getMock();

        $prop = new ReflectionProperty(TaskItemRepository::class, 'entityManager');
        $prop->setAccessible(true);
        $prop->setValue($this->sut, $this->entityManager);
    }

    /**
     * @covers ::persist
     */
    public function testPersist()
    {
        $model = $this->createMock(TaskItem::class);

        $this->entityManager->expects($this->once())->method('persist')->with($model);
        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($model, $this->sut->persist($model));
    }
}
