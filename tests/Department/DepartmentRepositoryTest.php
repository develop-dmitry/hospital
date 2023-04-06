<?php

declare(strict_types=1);

namespace Tests\Department;

use App\Hospital\Domain\Department\DepartmentBuilder;
use App\Hospital\Domain\Department\Exception\DepartmentNotFoundException;
use App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Infrastructure\Repository\DepartmentRepository;
use Tests\TestCase;

class DepartmentRepositoryTest extends TestCase
{
    protected DepartmentBuilderInterface $departmentBuilder;

    public function testSaveDepartmentSuccess(): void
    {
        $departmentRepository = new DepartmentRepository($this->departmentBuilder);
        $department = $this->departmentBuilder
            ->setName('test132')
            ->make();

        $this->expectNotToPerformAssertions();
        $departmentRepository->saveDepartment($department);
    }

    public function testFindDepartmentByIdSuccess(): void
    {
        $departmentRepository = new DepartmentRepository($this->departmentBuilder);
        $department = $this->departmentBuilder
            ->setName('test132')
            ->make();
        $departmentId = $departmentRepository->saveDepartment($department);

        $this->expectNotToPerformAssertions();
        $departmentRepository->findDepartmentById($departmentId);
    }

    public function testUpdateDepartmentSuccess(): void
    {
        $departmentRepository = new DepartmentRepository($this->departmentBuilder);
        $department = $this->departmentBuilder
            ->setName('test132')
            ->make();
        $departmentId = $departmentRepository->saveDepartment($department);

        $department
            ->setName('test345')
            ->setId($departmentId);
        $departmentRepository->saveDepartment($department);

        $updatedDepartment = $departmentRepository->findDepartmentById($departmentId);
        $this->assertEquals('test345', $updatedDepartment->getName());
    }

    public function testFindDepartmentByIdFailed(): void
    {
        $this->expectException(DepartmentNotFoundException::class);
        $departmentRepository = new DepartmentRepository($this->departmentBuilder);

        $departmentRepository->findDepartmentById(123123121);
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->departmentBuilder = $this->app->make(DepartmentBuilderInterface::class);
    }
}
