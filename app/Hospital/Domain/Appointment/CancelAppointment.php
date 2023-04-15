<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

use App\Hospital\Domain\Appointment\Exception\AppointmentCanNotCancelException;
use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\Interface\CancelAppointmentInterface;
use DateTime;

class CancelAppointment implements CancelAppointmentInterface
{
    public function __construct(
        protected AppointmentRepositoryInterface $appointmentRepository
    ) {
    }

    public function canCanceledAppointment(int $appointmentId): bool
    {
        try {
            $appointment = $this->appointmentRepository->getAppointmentById($appointmentId);
            $now = new DateTime();

            return !$appointment->isCanceled() && $now->getTimestamp() < $appointment->getVisitDate()->getTimestamp();
        } catch (AppointmentNotFoundException) {
            return false;
        }
    }

    public function cancelAppointment(int $appointmentId): void
    {
        if (!$this->canCanceledAppointment($appointmentId)) {
            throw new AppointmentCanNotCancelException("Appointment with id $appointmentId can not canceled");
        }

        try {
            $appointment = $this->appointmentRepository->getAppointmentById($appointmentId);

            $appointment->setCanceled(true);

            $this->appointmentRepository->saveAppointment($appointment);
        } catch (AppointmentNotFoundException) {
            throw new AppointmentCanNotCancelException("Appointment with id $appointmentId can not canceled");
        }
    }
}
