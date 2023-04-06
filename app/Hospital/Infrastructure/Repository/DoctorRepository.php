<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\Doctor\DoctorBuilder;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\Doctor\Exception\DoctorSaveFailedException;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\User\Exception\UserNotFoundException;
use App\Hospital\Domain\User\Interface\UserRepositoryInterface;
use App\Models\Doctor as DoctorModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\DB;

class DoctorRepository implements DoctorRepositoryInterface
{
    public function __construct(
        protected DoctorBuilder $doctorBuilder,
        protected UserRepositoryInterface $userRepository
    ) {
    }

    public function getDoctorByUserId(int $id): Doctor
    {
        try {
            $doctorModel = DoctorModel::where('user_id', $id)->firstOrFail();

            return $this->makeEntity($doctorModel);
        } catch (ModelNotFoundException) {
            throw new DoctorNotFoundException("Doctor with id = $id not found");
        }
    }

    public function saveDoctor(Doctor $doctor): int
    {
        DB::beginTransaction();

        try {
            $user = $this->userRepository->findById($doctor->getUserId());

            $user
                ->setEmail($doctor->getEmail())
                ->setName($doctor->getName())
                ->setLogin($doctor->getLogin());

            $this->userRepository->saveUser($user);

            $doctorModel = DoctorModel::find($doctor->getId());

            if (!$doctorModel) {
                $doctorModel = new DoctorModel();
            }

            $doctorModel->fill([
                'user_id' => $doctor->getUserId(),
                'department_id' => $doctor->getDepartmentId()
            ]);

            if (!$doctorModel->save()) {
                throw new DoctorSaveFailedException('Failed to save doctor');
            }
        } catch (UserNotFoundException|DoctorSaveFailedException $exception) {
            DB::rollBack();

            throw $exception;
        }

        DB::commit();

        return $doctorModel->id;
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
