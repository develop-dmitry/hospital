<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface;

use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use DateTime;

interface ChooseDoctorScheduleInterface
{
    /**
     * @param int $userId
     * @return DateTime[]
     * @throws DoctorNotFoundException
     */
    public function getBusyDates(int $userId): array;
}
