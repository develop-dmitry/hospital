<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface\Response;

use DateTime;

interface GetBusyDatesResponseInterface
{
    public function getDates(): array;
}
