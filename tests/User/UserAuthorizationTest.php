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
use App\Hospital\Infrastructure\Repository\UserRepository;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class UserAuthorizationTest extends TestCase
{
    protected UserBuilderInterface $userBuilder;

    protected SessionManager $sessionManager;

    protected User $user;

    public function testAuthorizationSuccess(): void
    {
        $user = (clone $this->user)->setPassword(Hash::make($this->user->getPassword()));
        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setConstructorArgs([$this->userBuilder])
            ->getMock();
        $userRepository->method('findByEmail')->willReturn($user);

        $userAuthorization = new UserAuthorization($userRepository, $this->sessionManager);
        $authorizationRequest = new AuthorizationRequest('test_user@email.com', '12345678');

        $this->expectNotToPerformAssertions();
        $userAuthorization->auth($authorizationRequest);
    }

    public function testAuthorizationInvalidPassword(): void
    {
        $user = (clone $this->user)->setPassword(Hash::make($this->user->getPassword()));
        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setConstructorArgs([$this->userBuilder])
            ->getMock();
        $userRepository->method('findByEmail')->willReturn($user);

        $userAuthorization = new UserAuthorization($userRepository, $this->sessionManager);
        $authorizationRequest = new AuthorizationRequest('test_user@email.com', '1234545678');

        $this->expectException(InvalidUserPasswordException::class);
        $userAuthorization->auth($authorizationRequest);
    }

    public function testAuthorizationUserNotFound(): void
    {
        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setConstructorArgs([$this->userBuilder])
            ->getMock();
        $userRepository->method('findByEmail')->willThrowException(new UserNotFoundException());

        $userAuthorization = new UserAuthorization($userRepository, $this->sessionManager);
        $authorizationRequest = new AuthorizationRequest('test_ser@email.com', '1234545678');

        $this->expectException(UserNotFoundException::class);
        $userAuthorization->auth($authorizationRequest);
    }

    public function testAuthorizationUserSaveFailed(): void
    {
        $user = clone $this->user;
        $user->setPassword(Hash::make('12345678'));
        $userRepository = $this->getMockBuilder(UserRepository::class)
            ->setConstructorArgs([$this->userBuilder])
            ->getMock();
        $userRepository->method('findByEmail')->willReturn($user);
        $userRepository->method('saveUser')->willThrowException(new UserSaveFailedException());

        $userAuthorization = new UserAuthorization($userRepository, $this->sessionManager);
        $authorizationRequest = new AuthorizationRequest('test_user@email.com', '12345678');

        $this->expectException(UserSaveFailedException::class);
        $userAuthorization->auth($authorizationRequest);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->sessionManager = $this->app->get(SessionManager::class);

        $this->userBuilder = $this->app->make(UserBuilderInterface::class);

        $this->user =  $this->userBuilder
            ->setEmail('test_user@email.com')
            ->setLogin('test.user')
            ->setPassword('12345678')
            ->setName('test')
            ->make();
    }
}
