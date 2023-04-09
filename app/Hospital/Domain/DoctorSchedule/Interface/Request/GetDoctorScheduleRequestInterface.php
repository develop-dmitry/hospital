<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface\Request;

interface GetDoctorScheduleRequestInterface
{
    public function getUserId(): int;
}
