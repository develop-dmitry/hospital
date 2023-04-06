<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Department\Department;
use App\Hospital\Domain\Department\Exception\DepartmentNotFoundException;
use App\Hospital\Domain\Department\Exception\DepartmentSaveFailedException;
use App\Hospital\Domain\Department\Interface\DepartmentBuilderInterface;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Models\Department as DepartmentModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DepartmentRepository implements DepartmentRepositoryInterface
{
    public function __construct(
        protected DepartmentBuilderInterface $departmentBuilder
    ) {
    }

    public function saveDepartment(Department $department): int
    {
        $departmentModel = DepartmentModel::find($department->getId());

        if (!$departmentModel) {
            $departmentModel = new DepartmentModel();
        }

        $departmentModel->fill([
            'name' => $department->getName()
        ]);

        if (!$departmentModel->save()) {
            throw new DepartmentSaveFailedException('Failed to save department');
        }

        return $departmentModel->id;
    }

    public function findDepartmentById(int $departmentId): Department
    {
        try {
            $departmentModel = DepartmentModel::where('id', $departmentId)->firstOrFail();

            return $this->makeEntity($departmentModel);
        } catch (ModelNotFoundException) {
            throw new DepartmentNotFoundException("Department with id = $departmentId not found");
        }
    }

    protected function makeEntity(DepartmentModel $departmentModel): Department
    {
        return $this->departmentBuilder
            ->setName($departmentModel->name)
            ->setId($departmentModel->id)
            ->make();
    }

}
