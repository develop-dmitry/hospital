<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\GenerateConfirmMessageFailedException;
use App\Hospital\Domain\Appointment\Interface\AppointmentBuilderInterface;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentInterface;
use App\Hospital\Domain\Appointment\Interface\MakeAppointmentRepositoryInterface;
use App\Hospital\Domain\Client\Client;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use DateInterval;
use DatePeriod;
use DateTime;
use Exception;

class MakeAppointment implements MakeAppointmentInterface
{
    public function __construct(
        protected MakeAppointmentRepositoryInterface $makeAppointmentRepository,
        protected DepartmentRepositoryInterface $departmentRepository,
        protected DoctorScheduleRepositoryInterface $doctorScheduleRepository,
        protected DoctorRepositoryInterface $doctorRepository,
        protected AppointmentRepositoryInterface $appointmentRepository,
        protected AppointmentBuilderInterface $appointmentBuilder
    ) {
    }

    public function saveDepartment(Client $client, int $departmentId): static
    {
        $this->makeAppointmentRepository->saveDepartmentId($client, $departmentId);
        return $this;
    }

    public function saveDoctor(Client $client, int $doctorId): static
    {
       $this->makeAppointmentRepository->saveDoctorId($client, $doctorId);
        return $this;
    }

    public function saveDate(Client $client, DateTime $date): static
    {
        $this->makeAppointmentRepository->saveDate($client, $date);
        return $this;
    }

    public function saveTime(Client $client, string $time): static
    {
        $this->makeAppointmentRepository->saveTime($client, $time);
        return $this;
    }

    public function savePhone(Client $client, string $phone): static
    {
        $this->makeAppointmentRepository->savePhone($client, $phone);
        return $this;
    }

    public function makeAppointment(Client $client): void
    {
        $appointment = $this->appointmentBuilder
            ->setClientId($client->getId())
            ->setDepartmentId($this->makeAppointmentRepository->getDepartmentId($client))
            ->setDoctorId($this->makeAppointmentRepository->getDoctorId($client))
            ->setVisitDate($this->makeAppointmentRepository->getDate($client))
            ->setVisitTime(new DateTime($this->makeAppointmentRepository->getTime($client)))
            ->setVisitorName($client->getName())
            ->setVisitorPhone($this->makeAppointmentRepository->getPhone($client))
            ->make();

        $this->appointmentRepository->saveAppointment($appointment);
    }

    public function getDepartments(Client $client): array
    {
        return $this->departmentRepository->getAll();
    }

    public function getDoctors(Client $client): array
    {
        $departmentId = $this->makeAppointmentRepository->getDepartmentId($client);

        return $this->doctorRepository->getDoctorsByDepartmentId($departmentId);
    }

    public function getDates(Client $client): array
    {
        $doctorId = $this->makeAppointmentRepository->getDoctorId($client);
        $doctorSchedule = $this->doctorScheduleRepository->getDoctorSchedule($doctorId);

        $dates = [];

        foreach ($doctorSchedule as $schedule) {
            $dates[] = $schedule->getDate();
        }

        return $dates;
    }

    public function getTime(Client $client): array
    {
        try {
            $appointments = $this->appointmentRepository->getAppointmentsByDate(
                $this->makeAppointmentRepository->getDate($client),
                $this->makeAppointmentRepository->getDoctorId($client)
            );
        } catch (AppointmentNotFoundException) {
            $appointments = [];
        }

        $busyDates = [];

        foreach ($appointments as $appointment) {
            $busyDates[] = $appointment->getVisitTime()->format('H:i');
        }

        $period = new DatePeriod(
            new DateTime('09:00'),
            new DateInterval('PT1H'),
            new DateTime('18:00')
        );

        $time = [];

        foreach ($period as $item) {
            $timeFormat = $item->format('H:i');

            if (!in_array($timeFormat, $busyDates)) {
                $time[] = $timeFormat;
            }
        }

        return $time;
    }

    public function getConfirmMessage(Client $client): string
    {
        try {
            $department = $this->departmentRepository->findDepartmentById(
                $this->makeAppointmentRepository->getDepartmentId($client)
            );
            $doctor = $this->doctorRepository->getDoctorById($this->makeAppointmentRepository->getDoctorId($client));
            $date = $this->makeAppointmentRepository->getDate($client);
            $time = $this->makeAppointmentRepository->getTime($client);
            $phone = $this->makeAppointmentRepository->getPhone($client);

            $message = "Отделение: {$department->getName()}" . PHP_EOL;
            $message .= "Специалист: {$doctor->getName()}" . PHP_EOL;
            $message .= "Дата: {$date->format('m.d.Y')}" . PHP_EOL;
            $message .= "Время: {$time}" . PHP_EOL;
            $message .= "Номер телефона: {$phone}" . PHP_EOL;

            return $message;
        } catch (Exception) {
            throw new GenerateConfirmMessageFailedException('Failed to generate confirm message');
        }
    }
}
