<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule;

use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use DateTime;

class ChooseDoctorSchedule implements ChooseDoctorScheduleInterface
{
    public function __construct(
        protected DoctorScheduleRepositoryInterface $doctorScheduleRepository,
        protected DoctorRepositoryInterface $doctorRepository
    ) {
    }

    public function getBusyDates(int $userId): array
    {
        $doctor = $this->doctorRepository->getDoctorByUserId($userId);

        [$from, $before] = $this->getAvailableChooseInterval();

        $busyDays = $this->doctorScheduleRepository->getBusyDays($doctor->getDepartmentId(), $from, $before);

        $result = [];

        foreach ($busyDays as $day) {
            $result[] = $day->getDate();
        }

        return $result;
    }

    protected function getAvailableChooseInterval(): array
    {
        $from = new DateTime('+1 day midnight');
        $before = new DateTime('+1 day +1 month midnight');

        return [$from, $before];
    }
}
