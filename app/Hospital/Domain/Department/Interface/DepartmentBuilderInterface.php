<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Department\Interface;

use App\Hospital\Domain\Department\Department;

interface DepartmentBuilderInterface
{
    public function setId(int $id): static;

    public function setName(string $name): static;

    public function make(): Department;
}
