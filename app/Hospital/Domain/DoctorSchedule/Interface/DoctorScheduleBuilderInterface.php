<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule\Interface;

use App\Hospital\Domain\DoctorSchedule\DoctorSchedule;
use DateTime;

interface DoctorScheduleBuilderInterface
{
    public function make(): DoctorSchedule;

    public function setId(int $id): static;

    public function setDoctorId(int $id): static;

    public function setDate(DateTime $date): static;
}
