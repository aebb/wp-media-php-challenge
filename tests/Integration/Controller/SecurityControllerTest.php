<?php

namespace App\Tests\Integration\Controller;

use App\Tests\Integration\EndpointTester;
use App\Tests\Integration\Fixtures\TestFixture;
use Symfony\Component\HttpFoundation\Response;

/** @coversDefaultClass \App\Controller\SecurityController */
class SecurityControllerTest extends EndpointTester
{
    /**
     * @covers ::executeLogin
     */
    public function testExecuteLoginGet()
    {
        $crawler = $this->client->request('GET', '/login');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(3, $crawler->filter('form > input')->count());
    }

    /**
     * @covers ::executeLogin
     */
    public function testExecuteLoginGetErrors()
    {
        $fixture = new TestFixture();
        $fixture->addAdmin($this->encoder);
        $this->loadFixture($fixture);

        //get login page
        $crawler = $this->client->request('GET', '/login');
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());


        //fill and submit form
        $form = $crawler->selectButton('login')->form();
        $form->get('_username')->setValue('admin');
        $form->get('_password')->setValue('admin');

        $this->client->followRedirects();
        $crawler = $this->client->submit($form);

        //check form submission result
        $response = $this->client->getResponse();
        $this->assertEquals(Response::HTTP_OK, $response->getStatusCode());
        $this->assertEquals(1, $crawler->filter('.error')->count());
    }

    /**
     * @covers ::executeLogout
     */
    public function testExecuteLogout()
    {
        $this->client->request('GET', '/logout');
        $response = $this->client->getResponse();

        $this->assertEquals(Response::HTTP_FOUND, $response->getStatusCode());
    }
}
