<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule;

use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleListInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;

class DoctorScheduleList implements DoctorScheduleListInterface
{
    public function __construct(
        protected DoctorScheduleRepositoryInterface $doctorScheduleRepository,
        protected DoctorRepositoryInterface $doctorRepository
    ) {
    }

    public function getDoctorSchedule(int $userId): array
    {
        $doctor = $this->doctorRepository->getDoctorByUserId($userId);

        return $this->doctorScheduleRepository->getDoctorSchedule($doctor->getId());
    }
}
