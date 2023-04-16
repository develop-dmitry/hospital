<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Client\Client;

interface ReMakeAppointmentRepositoryInterface
{
    /**
     * @param Client $client
     * @param int $appointmentId
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function saveAppointmentId(Client $client, int $appointmentId): void;

    /**
     * @param Client $client
     * @return int
     * @throws AppointmentPartNotFoundException
     */
    public function getAppointmentId(Client $client): int;
}
