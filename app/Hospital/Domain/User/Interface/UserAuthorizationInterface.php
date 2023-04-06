<?php

declare(strict_types=1);

namespace App\Hospital\Domain\User\Interface;

use App\Hospital\Domain\User\DTO\AuthorizationRequest;
use App\Hospital\Domain\User\Exception\InvalidUserPasswordException;
use App\Hospital\Domain\User\Exception\UserNotAuthException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\Exception\UserSaveFailedException;
use App\Hospital\Domain\User\User;

interface UserAuthorizationInterface
{
    /**
     * @param AuthorizationRequest $request
     * @return void
     * @throws InvalidUserPasswordException
     * @throws UserNotFoundException
     * @throws UserSaveFailedException
     */
    public function auth(AuthorizationRequest $request): void;

    /**
     * @return bool
     */
    public function isAuth(): bool;

    /**
     * @return User
     * @throws UserNotAuthException
     */
    public function getUser(): User;
}
