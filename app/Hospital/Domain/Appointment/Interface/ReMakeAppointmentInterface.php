<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

use App\Hospital\Domain\Appointment\Appointment;

interface ReMakeAppointmentInterface
{
    public function canReMakeAppointment(Appointment $appointment): bool;
}
