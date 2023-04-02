<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Models\Doctor;

class DoctorRepository
{
    public function getById(int $id): Doctor
    {
        return Doctor::findOrFail($id);
    }

    public function getAll(): array
    {
        return Doctor::all()->toArray();
    }

    public function create(array $data): Doctor
    {
        return Doctor::create($data);
    }

    public function update(int $id, array $data): Doctor
    {
        $doctor = $this->getById($id);
        $doctor->update($data);
        return $doctor;
    }

    public function delete(int $id): void
    {
        $doctor = $this->getById($id);
        $doctor->delete();
    }

    public function getNameById(int $id): ?string
    {
        $doctor = Doctor::find($id);
        return $doctor ? $doctor->user->name : null;
    }

    public function getByDepartmentId(int $departmentId)
    {
        return Doctor::where('department_id', $departmentId)->get()->toArray();
    }
}
