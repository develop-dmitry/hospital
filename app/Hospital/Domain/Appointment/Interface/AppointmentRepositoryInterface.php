<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

use App\Hospital\Domain\Appointment\Appointment;
use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;

interface AppointmentRepositoryInterface
{
    /**
     * @param $date
     * @param $doctorId
     * @return Appointment[]
     */
    public function getAppointmentsByDate($date, $doctorId): array;

    /**
     * @param Appointment $appointment
     * @return int
     * @throws AppointmentSaveFailedException
     */
    public function saveAppointment(Appointment $appointment): int;
}
