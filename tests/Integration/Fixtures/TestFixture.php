<?php

namespace App\Tests\Integration\Fixtures;

use App\Entity\Task;
use App\Entity\TaskItem;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoder;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class TestFixture extends Fixture
{
    protected array $records;

    public function __construct()
    {
        $this->records = [];
    }

    public function getRecords(): array
    {
        return $this->records;
    }

    public function load(ObjectManager $manager)
    {
        foreach ($this->records as $record) {
            $manager->persist($record);
        }

        $manager->flush();
    }

    public function addAdmin(
        UserPasswordEncoderInterface $passwordEncoder,
        $name = 'admin',
        string $password = 'admin',
        array $roles = ['ROLE_ADMIN']
    ): TestFixture {
        $user = new User();

        $user->setUsername($name);
        $user->setRoles($roles);
        $user->setPassword($passwordEncoder->encodePassword($user, $password));

        $user->setPassword($password);

        $this->records[] = $user;
        return $this;
    }

    public function addTask(): TestFixture
    {
        $task = new Task();

        $task->addTaskItem(new TaskItem($task, 'dummy.com'));
        $task->addTaskItem(new TaskItem($task, 'foo-bar.com'));
        $task->addTaskItem(new TaskItem($task, 'example.com'));

        $this->records[] = $task;
        return $this;
    }
}
