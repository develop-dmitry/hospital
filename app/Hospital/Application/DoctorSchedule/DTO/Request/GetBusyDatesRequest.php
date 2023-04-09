<?php

declare(strict_types=1);

namespace App\Hospital\Application\DoctorSchedule\DTO\Request;

use App\Hospital\Domain\DoctorSchedule\Interface\Request\GetBusyDatesRequestInterface;

class GetBusyDatesRequest implements GetBusyDatesRequestInterface
{
    public function __construct(
        protected int $userId
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }
}
