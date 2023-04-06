<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Doctor\Interface;

use App\Hospital\Domain\Doctor\Doctor;

interface DoctorBuilderInterface
{
    public function make(): Doctor;

    public function setId(int $id): static;

    public function setUserId(int $userId): static;

    public function setDepartmentId(int $departmentId): static;

    public function setName(string $name): static;

    public function setLogin(string $login): static;

    public function setEmail(string $email): static;
}
