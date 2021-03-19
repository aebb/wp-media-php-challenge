<?php

namespace App\Tests\Unit\Service;

use App\Service\AbstractService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionProperty;

/** @coversDefaultClass \App\Service\AbstractService */
class AbstractServiceTest extends TestCase
{
    /**
     * @covers ::__construct
     */
    public function testConstructor()
    {
        $logger = $this->createMock(LoggerInterface::class);
        $service = $this->getMockForAbstractClass(AbstractService::class, [$logger]);

        $prop = new ReflectionProperty(AbstractService::class, 'logger');
        $prop->setAccessible(true);

        $this->assertEquals($logger, $prop->getValue($service));
    }
}
