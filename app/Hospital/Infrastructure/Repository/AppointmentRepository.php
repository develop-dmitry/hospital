<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\Appointment\Exception\AppointmentSaveFailedException;
use App\Models\Appointment;

class AppointmentRepository
{
    public function all()
    {
        return Appointment::all();
    }

    public function getByDoctorId($doctorId)
    {
        return Appointment::where('doctor_id', $doctorId)->get();
    }

    public function create($data)
    {
        return Appointment::create($data);
    }

    public function update($id, $data)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);

        return $appointment;
    }

    public function delete($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->delete();

        return $appointment;
    }

    /**
     * @throws AppointmentSaveFailedException
     */
    public function saveAppointment(\App\Hospital\Domain\Appointment\Appointment $appointment): int
    {
        $appointmentModel = new Appointment;

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
            throw new AppointmentSaveFailedException("Failed to save user with id {$appointmentModel->id}");
        }

        return $appointmentModel->id;
    }
}
