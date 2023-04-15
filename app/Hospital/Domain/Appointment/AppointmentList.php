<?php

declare(strict_types=1);

namespace App\Hospital\Domain\Appointment;

use App\Hospital\Domain\Appointment\Exception\FailedGenerateAppointmentFormattedRowException;
use App\Hospital\Domain\Appointment\Interface\AppointmentListInterface;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Hospital\Domain\Department\Exception\DepartmentNotFoundException;
use App\Hospital\Domain\Department\Interface\DepartmentRepositoryInterface;
use App\Hospital\Domain\Doctor\Exception\DoctorNotFoundException;
use App\Hospital\Domain\Doctor\Interface\DoctorRepositoryInterface;

class AppointmentList implements AppointmentListInterface
{
    public function __construct(
        private readonly AppointmentRepositoryInterface $appointmentRepository,
        private readonly DoctorRepositoryInterface      $doctorRepository,
        private readonly DepartmentRepositoryInterface  $departmentRepository
    ) {
    }

    public function getAppointments(int $clientId): array
    {
        return $this->appointmentRepository->getAppointmentsByClientId($clientId);
    }

    public function getAppointmentFormattedRow(Appointment $appointment): string
    {
        try {
            $department = $this->departmentRepository->findDepartmentById($appointment->getDepartmentId());
            $doctor = $this->doctorRepository->getDoctorById($appointment->getDoctorId());
            $status = $appointment->isCanceled() ? 'отменена' : 'обработана';

            $message = "Отделение: {$department->getName()}" . PHP_EOL;
            $message .= "Специалист: {$doctor->getName()}" . PHP_EOL;
            $message .= "Дата: {$appointment->getVisitDate()->format('d.m.Y')}" . PHP_EOL;
            $message .= "Время: {$appointment->getVisitTime()->format('H:i')}" . PHP_EOL;
            $message .= "Статус: $status";

            return $message;
        } catch (DoctorNotFoundException|DepartmentNotFoundException $exception) {
            throw new FailedGenerateAppointmentFormattedRowException($exception->getMessage());
        }
    }

    public function getAppointmentById(int $appointmentId): Appointment
    {
        return $this->appointmentRepository->getAppointmentById($appointmentId);
    }

    public function getShortAppointmentFormattedRow(Appointment $appointment): string
    {
        return sprintf(
            'Запись %s в %s',
            $appointment->getVisitDate()->format('d.m.Y'),
            $appointment->getVisitTime()->format('H:i')
        );
    }
}
