<?php

declare(strict_types=1);

namespace Tests\User;

use App\Hospital\Application\User\UserAuthorization;
use App\Hospital\Domain\User\DTO\AuthorizationRequest;
use App\Hospital\Domain\User\Exception\InvalidUserPasswordException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\Exception\UserSaveFailedException;
use App\Hospital\Domain\User\User;
use App\Hospital\Domain\User\UserBuilderInterface;
use App\Hospital\Domain\User\UserRepositoryInterface;
use App\Hospital\Infrastructure\Repository\UserRepository;
use Tests\TestCase;
use Laravel\Lumen\Testing\DatabaseTransactions;

class UserAuthorizationTest extends TestCase
{
    use DatabaseTransactions;

    protected UserRepositoryInterface $userRepository;

    protected UserBuilderInterface $userBuilder;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->userBuilder = $this->app->make(UserBuilderInterface::class);

        $this->userRepository = $this->app->make(UserRepositoryInterface::class);
        $this->user =  $this->userBuilder
            ->setEmail('test_user@email.com')
            ->setLogin('test.user')
            ->setPassword('12345678')
            ->setName('test')
            ->make();
    }

    public function testAuthorizationSuccess(): void
    {
        $this->userRepository->saveUser($this->user);

        $userAuthorization = new UserAuthorization($this->userRepository);
        $authorizationRequest = new AuthorizationRequest('test_user@email.com', '12345678');

        $this->expectNotToPerformAssertions();
        $userAuthorization->authorization($authorizationRequest);
    }

    public function testAuthorizationInvalidPassword(): void
    {
        $this->userRepository->saveUser($this->user);

        $userAuthorization = new UserAuthorization($this->userRepository);
        $authorizationRequest = new AuthorizationRequest('test_user@email.com', '1234545678');

        $this->expectException(InvalidUserPasswordException::class);
        $userAuthorization->authorization($authorizationRequest);
    }

    public function testAuthorizationUserNotFound(): void
    {
        $userAuthorization = new UserAuthorization($this->userRepository);
        $authorizationRequest = new AuthorizationRequest('test_user@email.com', '1234545678');

        $this->expectException(UserNotFoundException::class);
        $userAuthorization->authorization($authorizationRequest);
    }

    public function testAuthorizationUserSaveFailed(): void
    {
        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setConstructorArgs([$this->userBuilder])
            ->getMock();
        $userRepository->method('findByEmail')->willReturn($this->user);
        $userRepository->method('saveUser')->willThrowException(new UserSaveFailedException());

        $userAuthorization = new UserAuthorization($userRepository);
        $authorizationRequest = new AuthorizationRequest('test_user@email.com', '12345678');

        $this->expectException(UserSaveFailedException::class);
        $userAuthorization->authorization($authorizationRequest);
    }
}
