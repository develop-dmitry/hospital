<?php

declare(strict_types=1);

namespace App\Hospital\Domain\DoctorSchedule;

use DateTime;

class DoctorSchedule
{
    public function __construct(
        protected int $id,
        protected int $doctorId,
        protected DateTime $date
    ) {
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @param int $id
     * @return DoctorSchedule
     */
    public function setId(int $id): DoctorSchedule
    {
        $this->id = $id;
        return $this;
    }

    /**
     * @return int
     */
    public function getDoctorId(): int
    {
        return $this->doctorId;
    }

    /**
     * @param int $doctorId
     * @return DoctorSchedule
     */
    public function setDoctorId(int $doctorId): DoctorSchedule
    {
        $this->doctorId = $doctorId;
        return $this;
    }

    /**
     * @return DateTime
     */
    public function getDate(): DateTime
    {
        return $this->date;
    }

    /**
     * @param DateTime $date
     * @return DoctorSchedule
     */
    public function setDate(DateTime $date): DoctorSchedule
    {
        $this->date = $date;
        return $this;
    }
}
