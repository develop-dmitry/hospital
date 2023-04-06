<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface\Request;

use DateTime;

interface ChooseDatesRequestInterface
{
    public function getUserId(): int;

    /**
     * @return DateTime[]
     */
    public function getDates(): array;
}
