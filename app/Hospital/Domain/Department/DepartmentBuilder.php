<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Department;

use App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface;

class DepartmentBuilder implements DepartmentBuilderInterface
{
    protected ?int $id = null;

    protected string $name = '';

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

    public function make(): Department
    {
        $department = new Department(
            $this->id,
            $this->name
        );

        $this->reset();

        return $department;
    }

    protected function reset(): void {
        $this->id = null;
        $this->name = '';
    }
}
