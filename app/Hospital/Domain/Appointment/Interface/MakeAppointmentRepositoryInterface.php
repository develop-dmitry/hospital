<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Client\Client;
use DateTime;
use Exception;

interface MakeAppointmentRepositoryInterface
{
    /**
     * @param Client $client
     * @param int $departmentId
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function saveDepartmentId(Client $client, int $departmentId): void;

    /**
     * @param Client $client
     * @return int
     * @throws AppointmentPartNotFoundException
     */
    public function getDepartmentId(Client $client): int;

    /**
     * @param Client $client
     * @param int $doctorId
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function saveDoctorId(Client $client, int $doctorId): void;

    /**
     * @param Client $client
     * @return int
     * @throws AppointmentPartNotFoundException
     */
    public function getDoctorId(Client $client): int;

    /**
     * @param Client $client
     * @param DateTime $date
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function saveDate(Client $client, DateTime $date): void;

    /**
     * @param Client $client
     * @return DateTime
     * @throws AppointmentPartNotFoundException
     * @throws Exception
     */
    public function getDate(Client $client): DateTime;

    /**
     * @param Client $client
     * @param string $time
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function saveTime(Client $client, string $time): void;

    /**
     * @param Client $client
     * @return string
     * @throws AppointmentPartNotFoundException
     */
    public function getTime(Client $client): string;

    /**
     * @param Client $client
     * @param string $phone
     * @return void
     * @throws AppointmentPartSaveFailedException
     */
    public function savePhone(Client $client, string $phone): void;

    /**
     * @param Client $client
     * @return string
     * @throws AppointmentPartNotFoundException
     */
    public function getPhone(Client $client): string;
}
