<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Models\Department;

class DepartmentRepository
{
    public function getAll(): array
    {
        return Department::all()->toArray();
    }
}
