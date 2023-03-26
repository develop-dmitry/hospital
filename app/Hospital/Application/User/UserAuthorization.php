<?php

declare(strict_types=1);

namespace App\Hospital\Application\User;

use App\Hospital\Domain\User\DTO\AuthorizationRequest;
use App\Hospital\Domain\User\Exception\InvalidUserPasswordException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\Exception\UserSaveFailedException;
use App\Hospital\Domain\User\User;
use App\Hospital\Domain\User\UserAuthorizationInterface;
use App\Hospital\Domain\User\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserAuthorization implements UserAuthorizationInterface
{
    public function __construct(
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function authorization(AuthorizationRequest $request): void
    {
        $user = $this->userRepository->findByEmail($request->getEmail());

        if (!Hash::check($request->getPassword(), $user->getPassword())) {
            throw new InvalidUserPasswordException('Invalid user password');
        }

        $user->setAuthToken($this->generateAuthToken());

        $this->userRepository->saveUser($user);
    }

    protected function generateAuthToken(): string
    {
        return Hash::make(Str::random());
    }
}
