<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface;

use App\Hospital\Domain\Doctor\Doctor;
use App\Hospital\Domain\DoctorSchedule\DoctorSchedule;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseDateFailedException;
use DateTime;

interface DoctorScheduleRepositoryInterface
{
    /**
     * @param int $departmentId
     * @param DateTime $from
     * @param DateTime $before
     * @return DoctorSchedule[]
     */
    public function getBusyDays(int $departmentId, DateTime $from, DateTime $before): array;

    /**
     * @param DoctorSchedule[] $schedules
     * @return void
     * @throws ChooseDateFailedException
     */
    public function chooseDates(array $schedules): void;

    /**
     * @param int $doctorId
     * @return DoctorSchedule[]
     */
    public function getDoctorSchedule(int $doctorId): array;
}
