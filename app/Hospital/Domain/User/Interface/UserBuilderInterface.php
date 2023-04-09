<?php

declare(strict_types=1);

namespace App\Hospital\Domain\User\Interface;

use App\Hospital\Domain\User\User;
use App\Models\User as UserModel;

interface UserBuilderInterface
{
    public function setId(int $id): static;

    public function setName(string $name): static;

    public function setEmail(string $email): static;

    public function setPassword(string $password): static;

    public function setAuthToken(string $authToken): static;

    public function setLogin(string $login): static;

    public function setIsDoctor(bool $isDoctor): static;

    public function make(): User;

    public function makeFromModel(UserModel $model): User;
}
