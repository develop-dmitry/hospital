<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

class Appointment
{
public function __construct(
    protected int $department_id,
    protected int $user_id,
    protected string $visit_date,
    protected string $visit_time,
    protected string $visitor_name,
    protected int $doctor_id,
    protected ?string $visitor_phone,
) {}

    /**
     * @return int
     */
    public function getDepartmentId(): int
    {
        return $this->department_id;
    }

    /**
     * @param int $department_id
     * @return Appointment
     */
    public function setDepartmentId(int $department_id): static
    {
        $this->department_id = $department_id;

        return $this;
    }

    /**
     * @return int
     */
    public function getUserId(): int
    {
        return $this->user_id;
    }

    public function setUserId(int $user_id): static
    {
        $this->user_id = $user_id;

        return $this;
    }

    /**
     * @return string
     */
    public function getVisitDate(): string
    {
        return $this->visit_date;
    }

    /**
     * @param string $visit_date
     * @return Appointment
     */
    public function setVisitDate(string $visit_date): static
    {
        $this->visit_date = $visit_date;

        return $this;
    }

    /**
     * @return string
     */
    public function getVisitTime(): string
    {
        return $this->visit_time;
    }

    /**
     * @param string $visit_time
     * @return Appointment
     */
    public function setVisitTime(string $visit_time): static
    {
        $this->visit_time = $visit_time;

        return $this;
    }

    /**
     * @return string
     */
    public function getVisitorName(): string
    {
        return $this->visitor_name;
    }

    /**
     * @param string $visitor_name
     * @return Appointment
     */
    public function setVisitorName(string $visitor_name): static
    {
        $this->visitor_name = $visitor_name;

        return $this;
    }

    /**
     * @return string
     */
    public function getVisitorPhone(): ?string
    {
        return $this->visitor_phone;
    }

    /**
     * @param string $visitor_phone
     * @return Appointment
     */
    public function setVisitorPhone(string $visitor_phone): static
    {
        $this->visitor_phone = $visitor_phone;

        return $this;
    }

    /**
     * @return int
     */
    public function getDoctorId(): int
    {
        return $this->doctor_id;
    }

    /**
     * @param int $doctor_id
     * @return Appointment
     */
    public function setDoctorId(int $doctor_id): static
    {
        $this->doctor_id = $doctor_id;

        return $this;
    }
}
