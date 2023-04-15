<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment\Interface;

use App\Hospital\Domain\Appointment\Appointment;
use DateTime;

interface AppointmentBuilderInterface
{
    public function setId(int $id): static;

    public function setVisitTime(DateTime $visitTime): static;

    public function setVisitDate(DateTime $visitDate): static;

    public function setDoctorId(int $doctorId): static;

    public function setClientId(int $clientId): static;

    public function setVisitorName(string $visitorName): static;

    public function setDepartmentId(int $departmentId): static;

    public function setVisitorPhone(?string $visitorPhone): static;

    public function make(): Appointment;
}
