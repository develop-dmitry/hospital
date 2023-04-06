<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface;

use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\DoctorSchedule\DoctorSchedule;

interface DoctorScheduleListInterface
{
    /**
     * @param int $userId
     * @return DoctorSchedule[]
     * @throws DoctorNotFoundException
     */
    public function getDoctorSchedule(int $userId): array;
}
