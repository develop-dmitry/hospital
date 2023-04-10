<?php

declare(strict_types=1);

namespace App\Hospital\Domain\User;

use App\Hospital\Domain\User\Interface\UserBuilderInterface;
use App\Models\User as UserModel;

class UserBuilder implements UserBuilderInterface
{
    protected string $name = '';

    protected string $email = '';

    protected string $password = '';

    protected string $authToken = '';

    protected string $login = '';

    protected bool $isDoctor = false;

    protected int $id = 0;

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;
        return $this;
    }

    public function setAuthToken(string $authToken): static
    {
        $this->authToken = $authToken;
        return $this;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function setIsDoctor(bool $isDoctor): static
    {
        $this->isDoctor = $isDoctor;
        return $this;
    }

    public function make(): User
    {
        $user = new User(
            $this->id,
            $this->name,
            $this->login,
            $this->email,
            $this->password,
            $this->authToken,
            $this->isDoctor
        );

        $this->reset();

        return $user;
    }

    protected function reset(): void
    {
        $this->id = 0;
        $this->email = '';
        $this->password = '';
        $this->login = '';
        $this->authToken = '';
        $this->name = '';
    }
}
