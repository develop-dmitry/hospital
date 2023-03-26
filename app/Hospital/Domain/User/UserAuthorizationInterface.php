<?php

declare(strict_types=1);

namespace App\Hospital\Domain\User;

use App\Hospital\Domain\User\DTO\AuthorizationRequest;
use App\Hospital\Domain\User\Exception\InvalidUserPasswordException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\Exception\UserSaveFailedException;

interface UserAuthorizationInterface
{
    /**
     * @param AuthorizationRequest $request
     * @return void
     * @throws InvalidUserPasswordException
     * @throws UserNotFoundException
     * @throws UserSaveFailedException
     */
    public function authorization(AuthorizationRequest $request): void;
}
