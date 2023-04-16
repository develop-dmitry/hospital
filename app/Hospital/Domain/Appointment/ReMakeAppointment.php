<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentInterface;
use App\Hospital\Domain\Appointment\Interface\ReMakeAppointmentRepositoryInterface;
use App\Hospital\Domain\Client\Client;
use DateTime;

class ReMakeAppointment implements ReMakeAppointmentInterface
{
    public function __construct(
        protected MakeAppointmentInterface $makeAppointment,
        protected ReMakeAppointmentRepositoryInterface $reMakeAppointmentRepository
    ) {
    }

    public function canReMakeAppointment(Appointment $appointment): bool
    {
        $now = new DateTime();

        return $now->getTimestamp() > ($appointment->getVisitDate()->getTimestamp() + 86400)
            || $appointment->isCanceled();
    }

    public function saveDate(Client $client, DateTime $date):void
    {
        $this->makeAppointment->saveDate($client, $date);
    }

    public function saveTime(Client $client, string $time): void
    {
        $this->makeAppointment->saveTime($client, $time);
    }

    public function getConfirmMessage(Client $client): string
    {
        return $this->makeAppointment->getConfirmMessage($client);
    }

    public function reMakeAppointment(Client $client): void
    {
        $this->makeAppointment->makeAppointment($client);
    }

    public function fillAppointmentPart(Client $client, Appointment $appointment): void
    {
        $this->makeAppointment->saveDepartment($client, $appointment->getDepartmentId());
        $this->makeAppointment->saveDoctor($client, $appointment->getDoctorId());
        $this->makeAppointment->savePhone($client, $appointment->getVisitorPhone());
    }

    public function getTime(Client $client): array
    {
        return $this->makeAppointment->getTime($client);
    }

    public function getDates(Client $client): array
    {
        return $this->makeAppointment->getDates($client);
    }

    public function hasDate(Client $client): bool
    {
        return $this->makeAppointment->hasDate($client);
    }

    public function saveAppointment(Client $client, int $appointmentId): void
    {
        $this->reMakeAppointmentRepository->saveAppointmentId($client, $appointmentId);
    }

    public function hasAppointment(Client $client): bool
    {
        try {
            $this->reMakeAppointmentRepository->getAppointmentId($client);

            return true;
        } catch (AppointmentPartNotFoundException) {
            return false;
        }
    }
}
