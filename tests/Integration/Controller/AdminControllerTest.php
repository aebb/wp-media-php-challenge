<?php

namespace App\Tests\Integration\Controller;

use App\Tests\Integration\EndpointTester;
use App\Tests\Integration\Fixtures\TestFixture;
use Symfony\Component\HttpFoundation\Response;

/** @coversDefaultClass \App\Controller\AdminController */
class AdminControllerTest extends EndpointTester
{
    /**
     * @covers ::__construct
     * @covers ::executeAdminGet
     */
    public function testExecuteAdminGet()
    {
        $this->client->request('GET', '/admin');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }

    /**
     * @covers ::__construct
     * @covers ::executeAdminGet
     */
    public function testExecuteAdminGetWithLogin()
    {
        //load fixtures
        $fixtures = new TestFixture();
        $fixtures->addAdmin($this->encoder);
        $fixtures->addTask();

        $this->loadFixture($fixtures);
        $admin =  $fixtures->getRecords()[0];

        //login and request admin page
        $this->client->loginUser($admin);
        $crawler = $this->client->request('GET', '/admin');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(3, $crawler->filter('ul > li > a')->count());
    }

    /**
     * @covers ::__construct
     * @covers ::executeAdminPost
     */
    public function testExecuteAdminPost()
    {
        //load fixtures
        $fixtures = new TestFixture();
        $fixtures->addAdmin($this->encoder);
        $fixtures->addTask();

        $this->loadFixture($fixtures);
        $admin =  $fixtures->getRecords()[0];

        //login and request admin page
        $this->client->loginUser($admin);
        $this->client->request('POST', '/admin');

        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
        $this->assertTrue($response->isRedirect());
        $this->assertCount(1, $this->transport->getSent());
    }
}
