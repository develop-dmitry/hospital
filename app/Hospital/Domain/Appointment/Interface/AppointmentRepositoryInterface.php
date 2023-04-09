<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

interface AppointmentRepositoryInterface
{
    public function getAppointmentsByDate($date, $doctorId): array;
}
