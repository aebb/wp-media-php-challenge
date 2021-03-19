<?php

namespace App\Service;

use Psr\Log\LoggerInterface;

abstract class AbstractService
{
    protected LoggerInterface $logger;

    public function __construct(LoggerInterface $logger)
    {
        $this->logger = $logger;
    }
}
