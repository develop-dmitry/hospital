<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Department\Interface;

use App\Hospital\Domain\Department\Department;
use App\Hospital\Domain\Department\Exception\DepartmentNotFoundException;
use App\Hospital\Domain\Department\Exception\DepartmentSaveFailedException;

interface DepartmentRepositoryInterface
{
    /**
     * @param Department $department
     * @return int
     * @throws DepartmentSaveFailedException
     */
    public function saveDepartment(Department $department): int;

    /**
     * @param int $departmentId
     * @return Department
     * @throws DepartmentNotFoundException
     */
    public function findDepartmentById(int $departmentId): Department;
}
