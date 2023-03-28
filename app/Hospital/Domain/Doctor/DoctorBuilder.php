<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Doctor;

use App\Hospital\Domain\Doctor\Interface\DoctorBuilderInterface;

class DoctorBuilder implements DoctorBuilderInterface
{
    protected int $id = 0;

    protected int $userId = 0;

    protected int $departmentId = 0;

    protected string $name = '';

    protected string $login = '';

    protected string $email = '';

    public function make(): Doctor
    {
        $doctor = new Doctor(
            $this->id,
            $this->userId,
            $this->departmentId,
            $this->name,
            $this->login,
            $this->email
        );

        $this->reset();

        return $doctor;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
        return $this;
    }

    public function setDepartmentId(int $departmentId): static
    {
        $this->departmentId = $departmentId;
        return $this;
    }

    public function setName(string $name): static
    {
        $this->name = $name;
        return $this;
    }

    public function setLogin(string $login): static
    {
        $this->login = $login;
        return $this;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;
        return $this;
    }

    protected function reset(): void
    {
        $this->id = 0;
        $this->userId = 0;
        $this->departmentId = 0;
        $this->name = '';
        $this->login = '';
        $this->email = '';
    }
}
