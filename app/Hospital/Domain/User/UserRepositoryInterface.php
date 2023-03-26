<?php

declare(strict_types=1);

namespace App\Hospital\Domain\User;

use App\Hospital\Domain\User\Exception\UserDropFailedException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\Exception\UserSaveFailedException;

interface UserRepositoryInterface
{
    /**
     * @param string $email
     * @return User
     * @throws UserNotFoundException
     */
    public function findByEmail(string $email): User;

    /**
     * @param User $user
     * @return void
     * @throws UserSaveFailedException
     */
    public function saveUser(User $user): void;

    /**
     * @param int $id
     * @return void
     * @throws UserDropFailedException
     */
    public function dropUser(int $id): void;
}
