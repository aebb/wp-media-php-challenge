<?php

namespace App\Tests\Unit\Controller;

use App\Controller\SecurityController;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

/** @coversDefaultClass \App\Controller\SecurityController */
class SecurityControllerTest extends TestCase
{

    /**
     * @covers ::executeLogout
     */
    public function testLogout()
    {
        $controller = new SecurityController();
        $this->assertNull($controller->executeLogout());
    }
}
