<?php

namespace App\Service;

use App\Entity\Task;
use App\Entity\TaskItem;
use App\Logging\LogMessages;
use App\Message\CrawlMessage;
use App\Repository\TaskRepository;
use DateTime;
use DOMDocument;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Contracts\HttpClient\Exception\ClientExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\RedirectionExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\ServerExceptionInterface;
use Symfony\Contracts\HttpClient\Exception\TransportExceptionInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

/**
 * @SuppressWarnings(PHPMD.ErrorControlOperator)
 * @SuppressWarnings(PHPMD.CouplingBetweenObjects)
 */
class AdminService extends AbstractService
{
    public const SITEMAP_FILE_NAME = '/templates/sitemap.html';

    public const SITEMAP_BASE_FILE_NAME = '/templates/sitemap_base.html';

    public const HOMEPAGE_RESULT = '/templates/homepage_result.html';

    public const SITEMAP_BASE_CONTAINER_ID = 'sitemap';

    public const EXCEPTION_MESSAGE = "Failed to request %s";

    protected HttpClientInterface $client;

    protected TaskRepository $repository;

    protected MessageBusInterface $messageBus;

    protected Filesystem $fileSystem;

    protected string $entryPoint;

    protected string $projectDir;

    public function __construct(
        LoggerInterface $logger,
        HttpClientInterface $client,
        TaskRepository $repository,
        MessageBusInterface $messageBus,
        string $entryPoint,
        string $projectDir
    ) {
        parent::__construct($logger);
        $this->client = $client;
        $this->repository = $repository;
        $this->messageBus = $messageBus;
        $this->fileSystem = new Filesystem();
        $this->entryPoint = $entryPoint;
        $this->projectDir = $projectDir;
    }

    /**
     * Returns most recent task
     * @param Request $request
     * @return Task|null
     */
    public function get(Request $request): ?Task
    {
        $this->logger->info(LogMessages::INFO_ENDPOINT_REQUESTED . $request->getPathInfo());
        return $this->repository->findMostRecent();
    }

    /**
     * Sends a message to trigger a new crawl task
     * @param Request $request
     */
    public function post(Request $request): void
    {
        $this->logger->info(LogMessages::INFO_ENDPOINT_REQUESTED . $request->getPathInfo());
        $this->messageBus->dispatch(new CrawlMessage($this->entryPoint));
    }

    /**
     * Crawls a given endpoint. Deletes old results. Creates homepage_result.html and sitemap.html
     * @param string $url
     * @throws ClientExceptionInterface
     * @throws RedirectionExceptionInterface
     * @throws ServerExceptionInterface
     * @throws TransportExceptionInterface
     * @throws Exception
     */
    public function generateCrawl(string $url): void
    {
        //get homepage content
        $response = $this->client->request('GET', $url);

        if ($response->getStatusCode() != Response::HTTP_OK) {
            throw new Exception(sprintf(self::EXCEPTION_MESSAGE, $url));
        }

        //extract content
        $homepage = new DOMDocument();
        @$homepage->loadHTML($response->getContent());

        //delete old crawl results;
        $this->repository->deleteOlderThan(new DateTime());

        //delete old sitemap.html
        if ($this->fileSystem->exists($this->projectDir . self::SITEMAP_FILE_NAME)) {
            $this->fileSystem->remove($this->projectDir . self::SITEMAP_FILE_NAME);
        }

        //dump homepage contents to a file
        $this->fileSystem->dumpFile($this->projectDir . self::HOMEPAGE_RESULT, $homepage->saveHTML());

        //parse the homepage contents
        $task = new Task();

        $sitemap = new DOMDocument();
        $sitemap->loadHTMLFile($this->projectDir . self::SITEMAP_BASE_FILE_NAME);
        $container = $sitemap->getElementById(self::SITEMAP_BASE_CONTAINER_ID);

        foreach ($homepage->getElementsByTagName('a') as $node) {
            //entity for database storage
            $item = new TaskItem($task, $node->nodeValue);
            $task->addTaskItem($item);

            //dom entities for sitemap.html
            $nodeListItem = $sitemap->createElement('li');
            $nodeLink = $sitemap->createElement('a');

            $nodeLink->setAttribute('href', $item);
            $nodeLink->textContent = $item;

            $nodeListItem->appendChild($nodeLink);
            $container->appendChild($nodeListItem);
        }

        //save sitemap.html
        $this->fileSystem->dumpFile($this->projectDir . self::SITEMAP_FILE_NAME, $sitemap->saveHTML());

        //save results
        $this->repository->persist($task);
    }
}
