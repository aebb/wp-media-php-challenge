<?php

namespace App\Tests\Unit\Service;

use App\Entity\Task;
use App\Message\CrawlMessage;
use App\Repository\TaskRepository;
use App\Service\AdminService;
use Exception;
use org\bovigo\vfs\vfsStream;
use PHPUnit\Framework\TestCase;
use Psr\Log\LoggerInterface;
use ReflectionProperty;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

/** @coversDefaultClass \App\Service\AdminService */
class AdminServiceTest extends TestCase
{
    protected LoggerInterface $logger;

    protected HttpClientInterface $client;

    protected TaskRepository $repository;

    protected MessageBusInterface $messageBus;

    protected Filesystem $fileSystem;

    protected string $entryPoint;

    protected string $projectDir;

    protected AdminService $sut;

    public function setUp(): void
    {
        parent::setUp();
        $root = vfsStream::setup('root');

        $this->client = $this->createMock(HttpClientInterface::class);
        $this->logger = $this->createMock(LoggerInterface::class);
        $this->repository = $this->createMock(TaskRepository::class);
        $this->messageBus = $this->createMock(MessageBusInterface::class);
        $this->fileSystem = $this->createMock(FileSystem::class);
        $this->entryPoint = '/dummy';
        $this->projectDir =  $root->url() . '/test-files';

        $this->sut = new AdminService(
            $this->logger,
            $this->client,
            $this->repository,
            $this->messageBus,
            $this->entryPoint,
            $this->projectDir
        );

        $prop = new ReflectionProperty(AdminService::class, 'fileSystem');
        $prop->setAccessible(true);
        $prop->setValue($this->sut, $this->fileSystem);




//
        $testFileSystem = new Filesystem();
        $testFileSystem->dumpFile($this->projectDir . AdminService::SITEMAP_BASE_FILE_NAME, $this->getTemplate());
    }

    public function tearDown(): void
    {
//        if (file_exists(dirname($this->projectDir . AdminService::SITEMAP_BASE_FILE_NAME)) === true) {
//            rmdir(dirname($this->projectDir));
//        }
    }

    /**
     * @covers ::__construct
     * @covers ::get
     */
    public function testGet()
    {
        $expected = $this->createMock(Task::class);

        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getPathInfo')
            ->willReturn('/dummy');

        $this->logger->expects($this->once())->method('info');

        $this->repository->expects($this->once())
            ->method('findMostRecent')
            ->willReturn($expected);

        $this->assertEquals($expected, $this->sut->get($request));
    }

    /**
     * @covers ::__construct
     * @covers ::post
     */
    public function testPost()
    {
        $message = new CrawlMessage($this->entryPoint);
        $request = $this->createMock(Request::class);
        $request->expects($this->once())
            ->method('getPathInfo')
            ->willReturn('/dummy');

        $this->logger->expects($this->once())->method('info');

        $this->messageBus->expects($this->once())
            ->method('dispatch')
            ->with($message)
            ->willReturn(new Envelope($message, []));

        $this->sut->post($request);
    }

    /**
     * @covers ::__construct
     * @covers ::generateCrawl
     */
    public function testGenerateCrawlNot200()
    {
        $url = '/dummy';
        $response = $this->createMock(ResponseInterface::class);

        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willReturn($response);

        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_NOT_FOUND);

        try {
            $this->sut->generateCrawl($url);
        } catch (Exception $exception) {
            $this->assertEquals(sprintf(AdminService::EXCEPTION_MESSAGE, $url), $exception->getMessage());
        }
    }

    /**
     * @covers ::__construct
     * @covers ::generateCrawl
     */
    public function testGenerateCrawlSuccess()
    {
        $url = '/dummy';
        $response = $this->createMock(ResponseInterface::class);
        $task = $this->createMock(Task::class);

        $this->client->expects($this->once())
            ->method('request')
            ->with('GET', $url)
            ->willReturn($response);

        $response->expects($this->once())
            ->method('getStatusCode')
            ->willReturn(Response::HTTP_OK);

        $response->expects($this->once())
            ->method('getContent')
            ->willReturn($this->getHomepage());

        $this->repository->expects($this->once())
            ->method('deleteOlderThan');

        $this->fileSystem->expects($this->once())
            ->method('exists')
            ->with($this->projectDir . AdminService::SITEMAP_FILE_NAME)
            ->willReturn(true);

        $this->fileSystem->expects($this->once())
            ->method('remove')
            ->with($this->projectDir . AdminService::SITEMAP_FILE_NAME)
            ->willReturn(true);

        $this->fileSystem->expects($this->exactly(2))
            ->method('dumpFile')
            ->withConsecutive(
                [$this->projectDir . AdminService::HOMEPAGE_RESULT],
                [$this->projectDir . AdminService::SITEMAP_FILE_NAME]
            );

        $this->repository->expects($this->once())
            ->method('persist')
            ->with($this->callback(function ($model) {
                return $model instanceof Task;
            }))
            ->willReturn($task);

        $this->sut->generateCrawl($url);
    }

    private function getHomepage(): string
    {
        return '<!DOCTYPE html>
<html lang="en"><head><title>Homepage</title></head><body>
<h1> Homepage </h1>

<ul id="links"><li><a href="facebook.com">facebook.com</a></li>
            <li><a href="office.com">office.com</a></li>
            <li><a href="youtube.com">youtube.com</a></li>
            <li><a href="apple.com">apple.com</a></li>
            <li><a href="bing.com">bing.com</a></li>
            <li><a href="twitch.tv">twitch.tv</a></li>
            <li><a href="instagram.com">instagram.com</a></li>
            <li><a href="reddit.com">reddit.com</a></li>
            <li><a href="ebay.com">ebay.com</a></li>
            <li><a href="google.com">google.com</a></li>
            </ul>
            </body>
            </html>
';
    }

    private function getTemplate(): string
    {
        return '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sitemap</title>
</head>
<body>
<ul id="sitemap">

</ul>
</body>
</html>
';
    }
}
