<?php

declare(strict_types=1);

namespace Tests\User;

use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\User;
use App\Hospital\Domain\User\UserBuilder;
use App\Hospital\Infrastructure\Repository\UserRepository;
use Laravel\Lumen\Testing\DatabaseTransactions;
use Tests\TestCase;

class UserRepositoryTest extends TestCase
{
    use DatabaseTransactions;

    protected User $user;

    protected UserRepository $userRepository;

    protected function setUp(): void
    {
        parent::setUp();

        $userBuilder = $this->app->make(UserBuilder::class);

        $this->user = $userBuilder->setName('Ivan Volkov')
            ->setLogin('ivan_volkov312')
            ->setPassword('12345678')
            ->setEmail('ivan@email.com')
            ->make();

        $this->userRepository = new UserRepository($userBuilder);
    }

    public function testSaveUser(): void
    {
        $this->expectNotToPerformAssertions();

        $this->userRepository->saveUser($this->user);
    }

    public function testFindUserByEmail(): void
    {
        $this->userRepository->saveUser($this->user);

        $this->expectNotToPerformAssertions();

        $this->userRepository->findByEmail($this->user->getEmail());
    }

    public function testFailFindUserByEmail(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->userRepository->findByEmail('testuser123124@mail.ru');
    }

    public function testDropUser(): void
    {
        $id = $this->userRepository->saveUser($this->user);
        $this->userRepository->dropUser($id);

        $this->expectException(UserNotFoundException::class);
        $this->userRepository->findByEmail($this->user->getEmail());
    }

    public function testUpdateUserSuccess(): void
    {
        $user = clone $this->user;
        $userId = $this->userRepository->saveUser($user);

        $user
            ->setId($userId)
            ->setName('test123456');
        $this->userRepository->saveUser($user);

        $user = $this->userRepository->findByEmail($user->getEmail());
        $this->assertEquals('test123456', $user->getName());
    }

    public function testFindById(): void
    {
        $user = clone $this->user;
        $userId = $this->userRepository->saveUser($user);

        $this->expectNotToPerformAssertions();
        $this->userRepository->findById($userId);
    }

    public function testFindByIdFail(): void
    {
        $this->expectException(UserNotFoundException::class);
        $this->userRepository->findById(999999);
    }
}
