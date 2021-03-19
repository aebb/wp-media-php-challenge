<?php

namespace App\Tests\Unit\Message;

use App\Message\CrawlMessage;
use App\Message\CrawlMessageHandler;
use App\Service\AdminService;
use Exception;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;

/** @coversDefaultClass \App\Message\CrawlMessageHandler */
class CrawlMessageHandlerTest extends TestCase
{
    protected LoggerInterface $logger;

    protected AdminService $service;

    protected CrawlMessageHandler $sut;

    protected function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->service = $this->createMock(AdminService::class);

        $this->sut = new CrawlMessageHandler($this->service, $this->logger) ;
    }

    /**
     * @covers ::__construct
     * @covers ::__invoke
     */
    public function testInvokeSuccess()
    {
        $url = 'dummy-url.com';
        $message = new CrawlMessage($url);

        $this->logger->expects($this->exactly(2))
            ->method('info');

        $this->logger->expects($this->never())
            ->method('error');

        $this->service->expects($this->once())
            ->method('generateCrawl')
            ->with($url);

        $this->sut->__invoke($message);
    }

    /**
     * @covers ::__construct
     * @covers ::__invoke
     */
    public function testInvokeFailure()
    {
        $url = 'dummy-url.com';
        $message = new CrawlMessage($url);

        $this->logger->expects($this->exactly(2))
            ->method('info');

        $this->logger->expects($this->once())
            ->method('error');

        $this->service->expects($this->once())
            ->method('generateCrawl')
            ->with($url)
            ->will($this->throwException(new Exception('dummy')));

        $this->sut->__invoke($message);
    }
}
