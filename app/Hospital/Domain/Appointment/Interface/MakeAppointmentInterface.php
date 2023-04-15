<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

use App\Hospital\Domain\Appointment\Exception\AppointmentPartNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentPartSaveFailedException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;
use App\Hospital\Domain\Appointment\Exception\GenerateConfirmMessageFailedException;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Department\Department;
use App\Hospital\Domain\Doctor\Doctor;
use DateTime;
use Exception;

interface MakeAppointmentInterface
{
    /**
     * @param Client $client
     * @param int $departmentId
     * @return MakeAppointmentInterface
     * @throws AppointmentPartSaveFailedException
     */
    public function saveDepartment(Client $client, int $departmentId): static;

    /**
     * @param Client $client
     * @param int $doctorId
     * @return MakeAppointmentInterface
     * @throws AppointmentPartSaveFailedException
     */
    public function saveDoctor(Client $client, int $doctorId): static;

    /**
     * @param Client $client
     * @param DateTime $date
     * @return MakeAppointmentInterface
     * @throws AppointmentPartSaveFailedException
     */
    public function saveDate(Client $client, DateTime $date): static;

    /**
     * @param Client $client
     * @param string $time
     * @return MakeAppointmentInterface
     * @throws AppointmentPartSaveFailedException
     */
    public function saveTime(Client $client, string $time): static;

    /**
     * @param Client $client
     * @param string $phone
     * @return MakeAppointmentInterface
     * @throws AppointmentPartSaveFailedException
     */
    public function savePhone(Client $client, string $phone): static;

    /**
     * @param Client $client
     * @return void
     * @throws AppointmentPartNotFoundException
     * @throws Exception
     * @throws AppointmentSaveFailedException
     */
    public function makeAppointment(Client $client): void;

    /**
     * @param Client $client
     * @return Department[]
     */
    public function getDepartments(Client $client): array;

    /**
     * @param Client $client
     * @return Doctor[]
     * @throws AppointmentPartNotFoundException
     */
    public function getDoctors(Client $client): array;

    /**
     * @param Client $client
     * @return DateTime[]
     * @throws AppointmentPartNotFoundException
     */
    public function getDates(Client $client): array;

    /**
     * @param Client $client
     * @return string[]
     * @throws AppointmentPartNotFoundException
     */
    public function getTime(Client $client): array;

    /**
     * @param Client $client
     * @return string
     * @throws GenerateConfirmMessageFailedException
     */
    public function getConfirmMessage(Client $client): string;
}
