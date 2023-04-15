<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Doctor\Interface;

use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\Doctor\Exception\DoctorSaveFailedException;
use App\Hospital\Domain\User\Exception\UserNotFoundException;

interface DoctorRepositoryInterface
{
    /**
     * @param int $id
     * @return Doctor
     * @throws DoctorNotFoundException
     */
    public function getDoctorByUserId(int $id): Doctor;

    /**
     * @param Doctor $doctor
     * @return int
     * @throws DoctorSaveFailedException
     * @throws UserNotFoundException
     */
    public function saveDoctor(Doctor $doctor): int;

    /**
     * @param int $doctorId
     * @return Doctor
     * @throws DoctorNotFoundException
     */
    public function getDoctorById(int $doctorId): Doctor;

    /**
     * @param int $departmentId
     * @return array
     */
    public function getDoctorsByDepartmentId(int $departmentId): array;
}
