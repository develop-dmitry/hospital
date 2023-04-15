<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

use App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentInterface;
use DateTime;

class ReMakeAppointment implements ReMakeAppointmentInterface
{
    public function canReMakeAppointment(Appointment $appointment): bool
    {
        $now = new DateTime();

        return $now->getTimestamp() > ($appointment->getVisitDate()->getTimestamp() + 86400)
            || $appointment->isCanceled();
    }
}
