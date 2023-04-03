<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule;

use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseBusyDateException;
use App\Hospital\Domain\DoctorSchedule\Interface\ChooseDoctorScheduleInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleBuilderInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use DateTime;

class ChooseDoctorSchedule implements ChooseDoctorScheduleInterface
{
    public function __construct(
        protected DoctorScheduleRepositoryInterface $doctorScheduleRepository,
        protected DoctorRepositoryInterface $doctorRepository,
        protected DoctorScheduleBuilderInterface $doctorScheduleBuilder
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

    public function chooseDates(int $userId, array $dates): void
    {
        $doctor = $this->doctorRepository->getDoctorByUserId($userId);

        if ($this->hasBusyDates($userId, $dates)) {
            throw new ChooseBusyDateException('The date array contains busy dates');
        }

        $schedules = [];

        foreach ($dates as $date) {
            $schedules[] = $this->doctorScheduleBuilder
                ->setDoctorId($doctor->getId())
                ->setDate($date)
                ->make();
        }

        $this->doctorScheduleRepository->chooseDates($schedules);
    }

    /**
     * @param int $userId
     * @param DateTime[] $dates
     * @return bool
     * @throws DoctorNotFoundException
     */
    protected function hasBusyDates(int $userId, array $dates): bool
    {
        $busyDates = array_map(static fn ($date) => $date->format('Y-m-d'),  $this->getBusyDates($userId));

        foreach ($dates as $date) {
            if (in_array($date->format('Y-m-d'), $busyDates, true)) {
                return true;
            }
        }

        return false;
    }

    protected function getAvailableChooseInterval(): array
    {
        $from = new DateTime('+1 day midnight');
        $before = new DateTime('+1 day +1 month midnight');

        return [$from, $before];
    }
}
