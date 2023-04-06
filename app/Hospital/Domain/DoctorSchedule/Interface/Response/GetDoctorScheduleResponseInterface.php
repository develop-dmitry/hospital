<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface\Response;

use DateTime;

interface GetDoctorScheduleResponseInterface
{
    /**
     * @return DateTime[]
     */
    public function getDates(): array;
}
