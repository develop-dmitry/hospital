<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

use App\Hospital\Domain\Appointment\Appointment;
use App\Hospital\Domain\Appointment\Exception\AppointmentCanNotCancelException;
use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;

interface CancelAppointmentInterface
{
    /**
     * @param int $appointmentId
     * @return bool
     */
    public function canCanceledAppointment(int $appointmentId): bool;

    /**
     * @param int $appointmentId
     * @return void
     * @throws AppointmentCanNotCancelException
     * @throws AppointmentSaveFailedException
     */
    public function cancelAppointment(int $appointmentId): void;
}
