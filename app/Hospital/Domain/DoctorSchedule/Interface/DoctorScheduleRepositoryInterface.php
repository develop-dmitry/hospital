<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface;

use App\Hospital\Domain\DoctorSchedule\DoctorSchedule;
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
}
