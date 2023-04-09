<?php

declare(strict_types=1);

namespace App\Hospital\Application\DoctorSchedule\DTO\Request;

use App\Hospital\Domain\DoctorSchedule\Interface\Request\ChooseDatesRequestInterface;
use DateTime;

class ChooseDatesRequest implements ChooseDatesRequestInterface
{
    /**
     * @param int $userId
     * @param DateTime[] $dates
     */
    public function __construct(
        protected int $userId,
        protected array $dates
    ) {
    }

    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @return DateTime[]
     */
    public function getDates(): array
    {
        return $this->dates;
    }
}
