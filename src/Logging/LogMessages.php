<?php

namespace App\Logging;

class LogMessages
{
    public const INFO_ENDPOINT_REQUESTED = 'New Request - ';

    public const INFO_CRAWL_MESSAGE_CONSUMER_STARTED = 'Web Request - Started new crawl ';
    public const INFO_CRAWL_MESSAGE_CONSUMER_FINISHED = 'Web Request - Finished new crawl';
    public const ERROR_CRAWL_MESSAGE_CONSUMER = 'Web Request - Error on new crawl - ';

    public const INFO_CRAWL_MESSAGE_CRON_STARTED = 'Cron job - Started new crawl';
    public const INFO_CRAWL_MESSAGE_CRON_FINISHED = 'Cron job - Finished new crawl';
    public const ERROR_CRAWL_MESSAGE_CRON = 'Cron job - Error on new crawl - ';
}
