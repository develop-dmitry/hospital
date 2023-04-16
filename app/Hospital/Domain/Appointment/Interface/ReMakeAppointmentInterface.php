<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

use App\Hospital\Domain\Appointment\Appointment;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;
use App\Hospital\Domain\Appointment\Exception\GenerateConfirmMessageFailedException;
use App\Hospital\Domain\Client\Client;
use DateTime;
use Exception;

interface ReMakeAppointmentInterface
{
    /**
     * @param Appointment $appointment
     * @return bool
     */
    public function canReMakeAppointment(Appointment $appointment): bool;

    /**
     * @param Client $client
     * @param DateTime $date
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function saveDate(Client $client, DateTime $date): void;

    /**
     * @param Client $client
     * @param string $time
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function saveTime(Client $client, string $time): void;

    /**
     * @param Client $client
     * @param int $appointmentId
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function saveAppointment(Client $client, int $appointmentId): void;

    /**
     * @param Client $client
     * @return void
     * @throws AppointmentPartNotFoundException
     * @throws Exception
     * @throws AppointmentSaveFailedException
     */
    public function reMakeAppointment(Client $client): void;

    /**
     * @param Client $client
     * @return string
     * @throws GenerateConfirmMessageFailedException
     */
    public function getConfirmMessage(Client $client): string;

    /**
     * @param Client $client
     * @param Appointment $appointment
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function fillAppointmentPart(Client $client, Appointment $appointment): void;

    /**
     * @param Client $client
     * @return string[]
     * @throws AppointmentPartNotFoundException
     */
    public function getTime(Client $client): array;

    /**
     * @param Client $client
     * @return DateTime[]
     * @throws AppointmentPartNotFoundException
     */
    public function getDates(Client $client): array;

    /**
     * @param Client $client
     * @return bool
     */
    public function hasDate(Client $client): bool;

    /**
     * @param Client $client
     * @return bool
     */
    public function hasAppointment(Client $client): bool;
}
