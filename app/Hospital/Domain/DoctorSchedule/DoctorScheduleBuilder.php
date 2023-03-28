<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule;

use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleBuilderInterface;
use DateTime;

class DoctorScheduleBuilder implements DoctorScheduleBuilderInterface
{
    protected int $id = 0;

    protected int $doctorId = 0;

    protected ?DateTime $date = null;

    public function make(): DoctorSchedule
    {
        $schedule = new DoctorSchedule(
            $this->id,
            $this->doctorId,
            $this->date
        );

        $this->reset();

        return $schedule;
    }

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function setDoctorId(int $id): static
    {
        $this->doctorId = $id;
        return $this;
    }

    public function setDate(DateTime $date): static
    {
        $this->date = $date;
    }

    public function reset(): void
    {
        $this->id = 0;
        $this->doctorId = 0;
        $this->date = null;
    }
}
