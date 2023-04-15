<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

use App\Hospital\Domain\Appointment\Appointment;
use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\FailedGenerateAppointmentFormattedRowException;

interface AppointmentListInterface
{
    /**
     * @param int $clientId
     * @return Appointment[]
     */
    public function getAppointments(int $clientId): array;

    /**
     * @param int $appointmentId
     * @return Appointment
     * @throws AppointmentNotFoundException
     */
    public function getAppointmentById(int $appointmentId): Appointment;

    /**
     * @param Appointment $appointment
     * @return string
     */
    public function getShortAppointmentFormattedRow(Appointment $appointment): string;

    /**
     * @param Appointment $appointment
     * @return string
     * @throws FailedGenerateAppointmentFormattedRowException
     */
    public function getAppointmentFormattedRow(Appointment $appointment): string;
}
