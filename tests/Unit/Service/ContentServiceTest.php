<?php

namespace App\Tests\Unit\Service;

use App\Service\ContentService;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

/** @coversDefaultClass \App\Service\ContentService */
class ContentServiceTest extends TestCase
{
    protected LoggerInterface $logger;

    protected LoaderInterface $loader;

    protected ContentService $sut;

    public function setUp(): void
    {
        $this->logger = $this->createMock(LoggerInterface::class);
        $env = $this->createMock(Environment::class);
        $this->loader = $this->createMock(LoaderInterface::class);

        $env->expects($this->once())
            ->method('getLoader')
            ->willReturn($this->loader);

        $this->sut = new ContentService($this->logger, $env);
    }

    /**
     * @covers ::__construct
     * @covers ::getContent
     */
    public function testGetContent()
    {
        $url = '/';
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getPathInfo')
            ->willReturn($url);

        $this->logger->expects($this->once())
            ->method('info');

        $result = $this->sut->getContent($request);

        $this->assertNotEmpty($result);
        $this->assertNotSameSize($result, ContentService::LINKS);
        foreach ($result as $link) {
            $this->assertContains($link, ContentService::LINKS);
        }
    }

    /**
     * @covers ::__construct
     * @covers ::getSitemapTemplate
     */
    public function testGetSitemapTemplate()
    {
        $url = '/sitemap';
        $request = $this->createMock(Request::class);
        $request->expects($this->exactly(2))
            ->method('getPathInfo')
            ->willReturn($url);

        $this->logger->expects($this->exactly(2))
            ->method('info');

        $this->loader->expects($this->exactly(2))
            ->method('exists')
            ->withConsecutive([ContentService::SITEMAP_TEMPLATE], [ContentService::SITEMAP_TEMPLATE])
            ->willReturnOnConsecutiveCalls(true, false);


        $result1 = $this->sut->getSitemapTemplate($request);
        $result2 = $this->sut->getSitemapTemplate($request);

        $this->assertEquals(ContentService::SITEMAP_TEMPLATE, $result1);
        $this->assertEquals(ContentService::BASE_SITEMAP_TEMPLATE, $result2);
    }
}
