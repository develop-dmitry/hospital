<?php
declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Appointment\Appointment;
use App\Hospital\Domain\Appointment\Exception\AppointmentNotFoundException;
use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;
use App\Hospital\Domain\Appointment\Interface\AppointmentRepositoryInterface;
use App\Models\Appointment as AppointmentModel;
use App\Hospital\Domain\Appointment\AppointmentBuilder;

class AppointmentRepository implements AppointmentRepositoryInterface
{
    public function __construct(
        protected AppointmentBuilder $appointmentBuilder
    ) {}


    public function saveAppointment(Appointment $appointment): int
    {
        $appointmentModel = AppointmentModel::find($appointment->getId());

        if (!$appointmentModel) {
            $appointmentModel = new AppointmentModel();
        }

        $appointmentModel->fill([
            'department_id' => $appointment->getDepartmentId(),
            'user_id' => $appointment->getUserId(),
            'visit_date' => $appointment->getVisitDate(),
            'visit_time' => $appointment->getVisitTime(),
            'visitor_name' => $appointment->getVisitorName(),
            'doctor_id' => $appointment->getDoctorId(),
            'visitor_phone' => $appointment->getVisitorPhone() ?? null,
        ]);

        if (!$appointmentModel->save()) {
            throw new AppointmentSaveFailedException(
                "Failed to save appointment with id {$appointmentModel->id}"
            );
        }

        return $appointmentModel->id;
    }

    protected function makeEntity(AppointmentModel $appointmentModel): Appointment
    {
        return $this->appointmentBuilder
            ->setId($appointmentModel->id)
            ->setDepartmentId($appointmentModel->department_id)
            ->setUserId($appointmentModel->user_id)
            ->setDoctorId($appointmentModel->doctor_id)
            ->setVisitDate($appointmentModel->visit_date)
            ->setVisitTime($appointmentModel->visit_time)
            ->setVisitorName($appointmentModel->visitor_name)
            ->setVisitorPhone($appointmentModel->visitor_phone)
            ->make();
    }

    public function getAppointmentsByDate($date, $doctorId): array
    {
        $appointments = AppointmentModel::whereDate('visit_date', $date)
            ->where('doctor_id', $doctorId)
            ->get();

        if (!$appointments) {
            throw new AppointmentNotFoundException(
                "Appointments with doctor_id {$doctorId} and date {$date} not found"
            );
        }

        return $appointments->map(function(AppointmentModel $appointmentModel) {
            return $this->makeEntity($appointmentModel);
        })->toArray();
    }

    public function getByUserId(int $userId): array
    {
        $appointments = AppointmentModel::where('user_id', $userId)
            ->where('canceled', false)
            ->get();

        if (!$appointments) {
            throw new AppointmentNotFoundException(
                "Appointments with user_id {$userId} not found"
            );
        }

        return $appointments->map(function(AppointmentModel $appointmentModel) {
            return $this->makeEntity($appointmentModel);
        })->toArray();
    }

    public function cancelAppointment(int $appointmentId): void
    {
        $appointmentModel = AppointmentModel::find($appointmentId);

        if (!$appointmentModel) {
            throw new AppointmentNotFoundException(
                "Appointment with id {$appointmentId} not found"
            );
        }

        $appointmentModel->canceled = true;

        if (!$appointmentModel->save()) {
            throw new AppointmentSaveFailedException(
                "Failed to cancel appointment with id {$appointmentId}"
            );
        }
    }

    public function getById(int $appointmentId): Appointment
    {
        $appointmentModel = AppointmentModel::find($appointmentId);

        if (!$appointmentModel) {
            throw new AppointmentNotFoundException(
                "Appointment with id {$appointmentId} not found"
            );
        }

        return $this->makeEntity($appointmentModel);
    }
}
