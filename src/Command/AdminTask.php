<?php

namespace App\Command;

use App\Logging\LogMessages;
use App\Service\AdminService;
use Exception;
use Psr\Log\LoggerInterface;
use Rewieer\TaskSchedulerBundle\Task\AbstractScheduledTask;
use Rewieer\TaskSchedulerBundle\Task\Schedule;

class AdminTask extends AbstractScheduledTask
{
    public const DEFAULT_SCHEDULER = 0;

    protected LoggerInterface $logger;

    protected AdminService $service;

    protected string $entryPoint;

    public function __construct(LoggerInterface $logger, AdminService $service, string $entryPoint)
    {
        parent::__construct();
        $this->logger = $logger;
        $this->service = $service;
        $this->entryPoint = $entryPoint;
    }

    protected function initialize(Schedule $schedule): void
    {
        $schedule->minutes(self::DEFAULT_SCHEDULER);
    }

    public function run(): void
    {
        $this->logger->info(LogMessages::INFO_CRAWL_MESSAGE_CRON_STARTED);
        try {
            $this->service->generateCrawl($this->entryPoint);
        } catch (Exception $exception) {
            $this->logger->error(LogMessages::ERROR_CRAWL_MESSAGE_CRON . $exception->getMessage());
        }
        $this->logger->info(LogMessages::INFO_CRAWL_MESSAGE_CRON_FINISHED);
    }
}
