<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

use App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface;
use DateTime;

class AppointmentBuilder implements AppointmentBuilderInterface
{

    protected ?int $id = null;
    protected ?DateTime $visitTime = null;

    protected ?DateTime $visitDate = null;

    protected ?int $doctorId = null;

    protected ?int $clientId = null;

    protected ?string $visitorName = null;

    protected ?int $departmentId = null;

    protected ?string $visitorPhone = null;

    protected bool $isCanceled = false;

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function setVisitTime(DateTime $visitTime): static
    {
        $this->visitTime = $visitTime;
        return $this;
    }

    public function setVisitDate(DateTime $visitDate): static
    {
        $this->visitDate = $visitDate;
        return $this;
    }

    public function setDoctorId(int $doctorId): static
    {
        $this->doctorId = $doctorId;
        return $this;
    }

    public function setClientId(int $clientId): static
    {
        $this->clientId = $clientId;
        return $this;
    }

    public function setVisitorName(string $visitorName): static
    {
        $this->visitorName = $visitorName;
        return $this;
    }

    public function setDepartmentId(int $departmentId): static
    {
        $this->departmentId = $departmentId;
        return $this;
    }

    public function setVisitorPhone(?string $visitorPhone): static
    {
        $this->visitorPhone = $visitorPhone;
        return $this;
    }

    public function setCanceled(bool $canceled): static
    {
        $this->isCanceled = $canceled;
        return $this;
    }

    public function make(): Appointment
    {
        $appointment = new Appointment(
            $this->id,
            $this->visitTime,
            $this->visitDate,
            $this->doctorId,
            $this->clientId,
            $this->visitorName,
            $this->departmentId,
            $this->visitorPhone,
            $this->isCanceled
        );

        $this->reset();

        return $appointment;
    }

    protected function reset(): void {
        $this->visitTime = null;
        $this->visitDate = null;
        $this->doctorId = null;
        $this->clientId = null;
        $this->visitorName = null;
        $this->departmentId = null;
        $this->visitorPhone = null;
        $this->isCanceled = false;
    }
}
