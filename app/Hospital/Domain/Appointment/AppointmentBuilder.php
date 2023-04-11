<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

use App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface;

class AppointmentBuilder implements AppointmentBuilderInterface
{

    protected ?int $id = null;
    protected $visitTime = null;

    protected $visitDate = null;

    protected ?int $doctorId = null;

    protected ?int $userId = null;

    protected ?string $visitorName = null;

    protected ?int $departmentId = null;

    protected ?string $visitorPhone = null;

    public function setId(int $id): static
    {
        $this->id = $id;
        return $this;
    }

    public function setVisitTime($visitTime): static
    {
        $this->visitTime = $visitTime;
        return $this;
    }

    public function setVisitDate($visitDate): static
    {
        $this->visitDate = $visitDate;
        return $this;
    }

    public function setDoctorId(int $doctorId): static
    {
        $this->doctorId = $doctorId;
        return $this;
    }

    public function setUserId(int $userId): static
    {
        $this->userId = $userId;
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

    public function make(): Appointment
    {
        $appointment = new Appointment(
            $this->id,
            $this->visitTime,
            $this->visitDate,
            $this->doctorId,
            $this->userId,
            $this->visitorName,
            $this->departmentId,
            $this->visitorPhone
        );

        $this->reset();

        return $appointment;
    }

    protected function reset(): void {
        $this->visitTime = null;
        $this->visitDate = null;
        $this->doctorId = null;
        $this->userId = null;
        $this->visitorName = null;
        $this->departmentId = null;
        $this->visitorPhone = null;
    }
}
