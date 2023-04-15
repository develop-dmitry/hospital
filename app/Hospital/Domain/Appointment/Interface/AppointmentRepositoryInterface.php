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

    /**
     * @param int $appointmentId
     * @return void
     * @throws AppointmentSaveFailedException
     * @throws AppointmentNotFoundException
     */
    public function cancelAppointment(int $appointmentId): void;

    /**
     * @param int $userId
     * @return array
     */
    public function getByUserId(int $userId): array;

    /**
     * @param int $appointmentId
     * @return Appointment
     * @throws AppointmentNotFoundException
     */
    public function getById(int $appointmentId): Appointment;
}
