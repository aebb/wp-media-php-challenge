<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\TaskItem;
use PHPUnit\Framework\TestCase;

/** @coversDefaultClass \App\Entity\TaskItem */
class TaskItemTest extends TestCase
{
    /**
     * @covers ::__construct()
     * @covers ::__toString()
     */
    public function testToString()
    {
        $task = new Task();
        $url = 'www.dummy.com';

        $model = new TaskItem($task, $url);

        $this->assertEquals($url, $model);
    }
}
