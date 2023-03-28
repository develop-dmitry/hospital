<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\Doctor\DoctorBuilder;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Models\Doctor as DoctorModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class DoctorRepository implements DoctorRepositoryInterface
{
    public function __construct(
        protected DoctorBuilder $doctorBuilder
    ) {
    }

    public function getDoctorByUserId(int $id): Doctor
    {
        try {
            $doctorModel = DoctorModel::where('user_id', $id)->firstOrFail();

            return $this->makeEntity($doctorModel);
        } catch (ModelNotFoundException) {
            throw new DoctorNotFoundException("Doctor with $id not found");
        }
    }

    protected function makeEntity(DoctorModel $doctor): Doctor
    {
        $userModel = $doctor->user()->first();

        if ($userModel) {
            $this->doctorBuilder
                ->setUserId($doctor->user_id)
                ->setName($userModel->name)
                ->setLogin($userModel->login)
                ->setEmail($userModel->email);
        }

        return $this->doctorBuilder
            ->setId($doctor->id)
            ->setDepartmentId($doctor->department_id)
            ->make();
    }
}
