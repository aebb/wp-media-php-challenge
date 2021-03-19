<?php

namespace App\Message;

use App\Logging\LogMessages;
use App\Service\AdminService;
use Exception;
use Psr\Log\LoggerInterface;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class CrawlMessageHandler implements MessageHandlerInterface
{
    protected AdminService $service;

    protected LoggerInterface $logger;

    public function __construct(AdminService $service, LoggerInterface $logger)
    {
        $this->service = $service;
        $this->logger = $logger;
    }

    public function __invoke(CrawlMessage $message)
    {
        $this->logger->info(LogMessages::INFO_CRAWL_MESSAGE_CONSUMER_STARTED);
        try {
            $this->service->generateCrawl($message->getUrl());
        } catch (Exception $exception) {
            $this->logger->error(LogMessages::ERROR_CRAWL_MESSAGE_CONSUMER . $exception->getMessage());
        }
        $this->logger->info(LogMessages::INFO_CRAWL_MESSAGE_CONSUMER_FINISHED);
    }
}
