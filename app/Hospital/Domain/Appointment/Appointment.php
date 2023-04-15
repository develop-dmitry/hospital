<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

use DateTime;

class Appointment
{
    public function __construct(
        protected ?int     $id,
        protected DateTime $visitTime,
        protected DateTime $visitDate,
        protected int      $doctorId,
        protected int      $clientId,
        protected string   $visitorName,
        protected int      $departmentId,
        protected ?string  $visitorPhone,
        protected bool     $isCanceled
    ) {
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): Appointment
    {
        $this->setId($id);
        return $this;
    }
    public function getVisitTime(): DateTime
    {
        return $this->visitTime;
    }

    /**
     * @param DateTime $visitTime
     * @return Appointment
     */
    public function setVisitTime(DateTime $visitTime): Appointment
    {
        $this->visitTime = $visitTime;
        return $this;
    }

    public function getVisitDate(): DateTime
    {
        return $this->visitDate;
    }

    /**
     * @param DateTime $visitDate
     * @return Appointment
     */
    public function setVisitDate(DateTime $visitDate): Appointment
    {
        $this->visitDate = $visitDate;
        return $this;
    }

    /**
     * @return int
     */
    public function getDoctorId(): int
    {
        return $this->doctorId;
    }

    /**
     * @param int $doctorId
     * @return Appointment
     */
    public function setDoctorId(int $doctorId): Appointment
    {
        $this->doctorId = $doctorId;
        return $this;
    }

    /**
     * @return int
     */
    public function getClientId(): int
    {
        return $this->clientId;
    }

    /**
     * @param int $clientId
     * @return Appointment
     */
    public function setClientId(int $clientId): Appointment
    {
        $this->clientId = $clientId;
        return $this;
    }

    /**
     * @return string
     */
    public function getVisitorName(): string
    {
        return $this->visitorName;
    }

    /**
     * @param string $visitorName
     * @return Appointment
     */
    public function setVisitorName(string $visitorName): Appointment
    {
        $this->visitorName = $visitorName;
        return $this;
    }

    /**
     * @return int
     */
    public function getDepartmentId(): int
    {
        return $this->departmentId;
    }

    /**
     * @param int $departmentId
     * @return Appointment
     */
    public function setDepartmentId(int $departmentId): Appointment
    {
        $this->departmentId = $departmentId;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getVisitorPhone(): ?string
    {
        return $this->visitorPhone;
    }

    /**
     * @param string|null $visitorPhone
     * @return Appointment
     */
    public function setVisitorPhone(?string $visitorPhone): Appointment
    {
        $this->visitorPhone = $visitorPhone;
        return $this;
    }

    /**
     * @return bool
     */
    public function isCanceled(): bool
    {
        return $this->isCanceled;
    }

    /**
     * @param bool $canceled
     * @return Appointment
     */
    public function setCanceled(bool $canceled): Appointment
    {
        $this->isCanceled = $canceled;
        return $this;
    }
}
