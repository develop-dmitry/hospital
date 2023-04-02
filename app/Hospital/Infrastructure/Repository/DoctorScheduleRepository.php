<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Models\DoctorSchedule;
class DoctorScheduleRepository
{
    public function getById(int $id): ?DoctorSchedule
    {
        return DoctorSchedule::find($id);
    }

    public function getAll(): \Illuminate\Database\Eloquent\Collection
    {
        return DoctorSchedule::all();
    }

    public function create(array $data): DoctorSchedule
    {
        return DoctorSchedule::create($data);
    }

    public function update(int $id, array $data): bool
    {
        $doctorSchedule = DoctorSchedule::find($id);

        if (!$doctorSchedule) {
            return false;
        }

        return $doctorSchedule->update($data);
    }

    public function delete(int $id): bool
    {
        $doctorSchedule = DoctorSchedule::find($id);

        if (!$doctorSchedule) {
            return false;
        }

        return $doctorSchedule->delete();
    }

    public function getByDoctorId(int $doctorId): array
    {
        return DoctorSchedule::where('doctor_id', $doctorId)->get()->toArray();
    }
}
