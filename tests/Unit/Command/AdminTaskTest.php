<?php

namespace App\Tests\Unit\Command;

use App\Command\AdminTask;
use App\Service\AdminService;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/** @coversDefaultClass \App\Command\AdminTask */
class AdminTaskTest extends TestCase
{
    protected LoggerInterface $logger;

    protected AdminService $service;

    protected string $entryPoint;

    protected AdminTask $sut;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->service = $this->createMock(AdminService::class);
        $this->entryPoint = 'dummy';

        $this->sut = new AdminTask($this->logger, $this->service, $this->entryPoint);
    }

    /**
     * @covers ::__construct
     * @covers ::initialize
     */
    public function testInitialize()
    {
        $expected =  ['0','*','*','*','*'];

        $cron = $this->sut->getSchedule()->getCron()->getParts();
        $this->assertEquals($expected, $cron);
    }

    /**
     * @covers ::__construct
     * @covers ::run
     */
    public function testRunSuccess()
    {
        $this->logger->expects($this->exactly(2))
            ->method('info');

        $this->logger->expects($this->never())
            ->method('error');

        $this->service->expects($this->once())
            ->method('generateCrawl')
            ->with($this->entryPoint);

        $this->sut->run();
    }

    /**
     * @covers ::__construct
     * @covers ::run
     */
    public function testRunFailure()
    {
        $this->logger->expects($this->exactly(2))
            ->method('info');

        $this->logger->expects($this->once())
            ->method('error');

        $this->service->expects($this->once())
            ->method('generateCrawl')
            ->with($this->entryPoint)
            ->will($this->throwException(new Exception('dummy')));

        $this->sut->run();
    }
}
