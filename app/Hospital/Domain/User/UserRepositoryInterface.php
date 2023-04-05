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
     * @param string $token
     * @return User
     * @throws UserNotFoundException
     */
    public function findByToken(string $token): User;

    /**
     * @param int $id
     * @return User
     * @throws UserNotFoundException
     */
    public function findById(int $id): User;

    /**
     * @param User $user
     * @return int
     * @throws UserSaveFailedException
     */
    public function saveUser(User $user): int;

    /**
     * @param int $id
     * @return void
     * @throws UserDropFailedException
     */
    public function dropUser(int $id): void;

    /**
     * @param int $id
     * @param string $password
     * @return void
     * @throws UserSaveFailedException
     * @throws UserNotFoundException
     */
    public function changePassword(int $id, string $password): void;
}
