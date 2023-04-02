<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

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
}
