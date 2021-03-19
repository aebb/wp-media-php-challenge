<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Task;
use App\Repository\TaskRepository;
use DateTime;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\QueryBuilder;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Repository\TaskRepository */
class TaskRepositoryTest extends TestCase
{
    protected EntityManagerInterface $entityManager;
    protected TaskRepository $sut;

    protected function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->sut = $this->getMockBuilder(TaskRepository::class)
            ->disableOriginalConstructor()
            ->setMethodsExcept([
                'deleteOlderThan',
                'findMostRecent',
                'persist'
            ])
            ->getMock();

        $prop = new ReflectionProperty(TaskRepository::class, 'entityManager');
        $prop->setAccessible(true);
        $prop->setValue($this->sut, $this->entityManager);
    }

    /**
     * @covers ::persist
     */
    public function testPersist()
    {
        $model = $this->createMock(Task::class);

        $this->entityManager->expects($this->once())->method('persist')->with($model);
        $this->entityManager->expects($this->once())->method('flush');

        $this->assertSame($model, $this->sut->persist($model));
    }

    /**
     * @covers ::findMostRecent
     */
    public function testFindMostRecent()
    {
        $expected = $this->createMock(Task::class);
        $query = $this->createMock(AbstractQuery::class);

        $query->expects($this->once())
            ->method('getOneOrNullResult')
            ->willReturn($expected);

        $builder = $this->createMock(QueryBuilder::class);

        $builder
            ->expects($this->once())
            ->method('orderBy')
            ->withConsecutive(['t.createdAt', 'DESC'])
            ->willReturnOnConsecutiveCalls($builder, $builder);

        $builder->expects($this->once())
            ->method('setMaxResults')
            ->with(1)
            ->willReturn($builder);

        $builder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $this->sut->expects($this->once())
            ->method('createQueryBuilder')
            ->with('t')
            ->willReturn($builder);

        $this->assertEquals($expected, $this->sut->findMostRecent());
    }

    /**
     * @covers ::deleteOlderThan
     */
    public function testDeleteOlderThan()
    {
        $date = new DateTime();

        $query = $this->createMock(AbstractQuery::class);

        $query->expects($this->once())
            ->method('execute');

        $builder = $this->createMock(QueryBuilder::class);

        $builder->expects($this->once())
            ->method('delete')
            ->willReturn($builder);

        $builder->expects($this->once())
            ->method('where')
            ->with('t.createdAt < :date')
            ->willReturn($builder);

        $builder
            ->expects($this->once())
            ->method('setParameter')
            ->withConsecutive(['date', $date])
            ->willReturnOnConsecutiveCalls($builder, $builder);

        $builder->expects($this->once())
            ->method('getQuery')
            ->willReturn($query);

        $this->sut->expects($this->once())
            ->method('createQueryBuilder')
            ->with('t')
            ->willReturn($builder);

        $this->sut->deleteOlderThan($date);
    }
}
