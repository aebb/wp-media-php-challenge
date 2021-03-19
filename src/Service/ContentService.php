<?php

namespace App\Service;

use App\Logging\LogMessages;
use Psr\Log\LoggerInterface;
use Symfony\Component\HttpFoundation\Request;
use Twig\Environment;
use Twig\Loader\LoaderInterface;

class ContentService extends AbstractService
{
    public const LINKS = [
        'google.com',
        'youtube.com',
        'facebook.com',
        'amazon.com',
        'yahoo.com',
        'wikipedia.org',
        'zoom.us',
        'live.com',
        'reddit.com',
        'netflix.com',
        'microsoft.com',
        'office.com',
        'instagram.com',
        'microsoftonline.com',
        'bing.com',
        'twitch.tv',
        'adobe.com',
        'ebay.com',
        'twitter.com',
        'apple.com',
    ];

    public const BASE_SITEMAP_TEMPLATE = 'sitemap_base.html';

    public const SITEMAP_TEMPLATE = 'sitemap.html';

    protected LoaderInterface $twig;

    public function __construct(LoggerInterface $logger, Environment $twig)
    {
        parent::__construct($logger);
        $this->twig = $twig->getLoader();
    }

    /**
     * Returns an array with random links
     * @param Request $request
     * @return array
     */
    public function getContent(Request $request): array
    {
        $this->logger->info(LogMessages::INFO_ENDPOINT_REQUESTED . $request->getPathInfo());

        $links = self::LINKS;
        shuffle($links);
        return array_slice($links, 0, count($links) / 2);
    }

    /**
     * Returns the sitemap or template
     * @param Request $request
     * @return string
     */
    public function getSitemapTemplate(Request $request): string
    {
        $this->logger->info(LogMessages::INFO_ENDPOINT_REQUESTED . $request->getPathInfo());

        return $this->twig->exists(self::SITEMAP_TEMPLATE) ? self::SITEMAP_TEMPLATE : self::BASE_SITEMAP_TEMPLATE;
    }
}
