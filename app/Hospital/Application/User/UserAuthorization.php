<?php

declare(strict_types=1);

namespace App\Hospital\Application\User;

use App\Hospital\Domain\User\DTO\AuthorizationRequest;
use App\Hospital\Domain\User\Exception\InvalidUserPasswordException;
use App\Hospital\Domain\User\Exception\UserNotAuthException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\User;
use App\Hospital\Domain\User\UserAuthorizationInterface;
use App\Hospital\Domain\User\UserRepositoryInterface;
use Illuminate\Session\SessionManager;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Psr\Log\LoggerInterface;

class UserAuthorization implements UserAuthorizationInterface
{
    protected string $sessionTokenName = 'auth_token';

    protected string $token = '';

    protected ?User $user = null;

    public function __construct(
        protected UserRepositoryInterface $userRepository,
        protected SessionManager $sessionManager,
        protected LoggerInterface $logger
    ) {
        $this->token = $this->sessionManager->get($this->sessionTokenName, '');

        if ($this->token) {
            try {
                $this->user = $this->userRepository->findByToken($this->token);
            } catch (UserNotFoundException) {
                $this->user = null;
            }
        }
    }

    public function isAuth(): bool
    {
        return !is_null($this->user);
    }

    public function getUser(): User
    {
        if ($this->isAuth()) {
            throw new UserNotAuthException('User not authorized');
        }

        return $this->user;
    }

    public function auth(AuthorizationRequest $request): void
    {
        $user = $this->userRepository->findByEmail($request->getEmail());

        if (!Hash::check($request->getPassword(), $user->getPassword())) {
            throw new InvalidUserPasswordException('Invalid user password');
        }

        $this->token = $this->generateAuthToken();
        $user->setAuthToken($this->token);
        $this->userRepository->saveUser($user);

        $this->saveToken();

        $this->logger->debug("Пользователь с email {$request->getEmail()} авторизовался");
    }

    protected function saveToken(): void
    {
        $this->sessionManager->put($this->sessionTokenName, $this->token);
    }

    protected function generateAuthToken(): string
    {
        return Hash::make(Str::random());
    }
}
