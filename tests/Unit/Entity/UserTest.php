<?php

namespace App\Tests\Unit\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;
use ReflectionProperty;

/** @coversDefaultClass \App\Entity\User */
class UserTest extends TestCase
{
    /**
     * @covers ::getId
     * @covers ::getPassword
     * @covers ::getRoles
     * @covers ::getSalt
     * @covers ::getUsername
     * @covers ::setPassword
     * @covers ::setRoles
     * @covers ::setUsername
     * @covers ::eraseCredentials
     */
    public function testUser()
    {
        $id = 1;
        $password = 'dummy_password';
        $roles = ['ROLE_USER'];
        $username = 'dummy_username';

        $model = new User();

        $model->setPassword($password);
        $model->setRoles($roles);
        $model->setUsername($username);

        $propId = new ReflectionProperty(User::class, 'id');
        $propId->setAccessible(true);
        $propId->setValue($model, $id);

        $this->assertEquals($id, $model->getId());
        $this->assertNull($model->getSalt());
        $this->assertEquals($username, $model->getUsername());
        $this->assertEquals($password, $model->getPassword());
        $this->assertEquals($roles, $model->getRoles());
        $this->assertNull($model->eraseCredentials());
    }
}
