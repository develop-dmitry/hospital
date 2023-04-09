<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

class Appointment
{
public function __construct(
    protected ?int $id,
    protected $visitTime,
    protected $visitDate,
    protected int $doctorId,
    protected int $userId,
    protected string $visitorName,
    protected int $departmentId,
    protected ?string $visitorPhone,
) {}

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
    public function getVisitTime()
    {
        return $this->visitTime;
    }

    /**
     * @param $visitTime
     * @return Appointment
     */
    public function setVisitTime($visitTime): Appointment
    {
        $this->visitTime = $visitTime;
        return $this;
    }

    public function getVisitDate()
    {
        return $this->visitDate;
    }

    /**
     * @param $visitDate
     * @return Appointment
     */
    public function setVisitDate($visitDate): Appointment
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
    public function getUserId(): int
    {
        return $this->userId;
    }

    /**
     * @param int $userId
     * @return Appointment
     */
    public function setUserId(int $userId): Appointment
    {
        $this->userId = $userId;
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

}
