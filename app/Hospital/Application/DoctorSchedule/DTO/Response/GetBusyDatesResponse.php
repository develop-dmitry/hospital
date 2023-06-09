<?php

declare(strict_types=1);

namespace App\Hospital\Application\DoctorSchedule\DTO\Response;

use App\Hospital\Domain\DoctorSchedule\Interface\Response\GetBusyDatesResponseInterface;
use DateTime;

class GetBusyDatesResponse implements GetBusyDatesResponseInterface
{
    public function __construct(
        protected array $dates
    ) {
    }

    public function getDates(): array
    {
        return $this->dates;
    }
}
