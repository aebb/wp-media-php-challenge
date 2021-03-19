<?php

namespace App\Tests\Integration\Controller;

use App\Tests\Integration\EndpointTester;
use Symfony\Component\HttpFoundation\Response;

/** @coversDefaultClass \App\Controller\BaseController */
class BaseControllerTest extends EndpointTester
{
    /**
     * @covers ::__construct
     * @covers ::executeHomeGet
     */
    public function testExecuteHomeGet()
    {
        $crawler = $this->client->request('GET', '/');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertGreaterThan(0, $crawler->filter('ul')->children()->count());
    }

    /**
     * @covers ::__construct
     * @covers ::executeSitemapGet
     */
    public function testExecuteSitemapGet()
    {
        $this->client->request('GET', '/sitemap');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
    }
}
