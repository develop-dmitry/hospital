<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface;

use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseBusyDateException;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseDateFailedException;
use DateTime;

interface ChooseDoctorScheduleInterface
{
    /**
     * @param int $userId
     * @return DateTime[]
     * @throws DoctorNotFoundException
     */
    public function getBusyDates(int $userId): array;

    /**
     * @param int $userId
     * @param DateTime[] $dates
     * @return void
     * @throws DoctorNotFoundException
     * @throws ChooseBusyDateException
     * @throws ChooseDateFailedException
     */
    public function chooseDates(int $userId, array $dates): void;
}
