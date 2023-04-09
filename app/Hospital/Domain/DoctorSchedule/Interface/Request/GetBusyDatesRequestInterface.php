<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface\Request;

interface GetBusyDatesRequestInterface
{
    public function getUserId(): int;
}
