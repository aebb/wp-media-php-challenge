<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\TaskItem;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \App\Entity\Task */
class TaskTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::addTaskItem
     * @covers ::getTaskItems
     */
    public function testAddTaskItems()
    {
        $model = new Task();
        $size = 5;

        for ($i = 0; $i < $size; $i++) {
            $model->addTaskItem($this->createMock(TaskItem::class));
            $this->assertTrue($model->getTaskItems()->toArray()[$i] instanceof TaskItem);
        }

        $this->assertEquals($size, count($model->getTaskItems()));
    }

    /**
     * @covers ::__construct()
     * @covers ::__toString()
     */
    public function testToString()
    {
        $model = new Task();
        $this->assertTrue(strpos($model, 'Crawl on') === 0);
    }
}
