<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\DoctorSchedule\DoctorScheduleBuilder;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\DoctorSchedule;
use App\Models\DoctorSchedule as DoctorScheduleModel;
use DateTime;

class DoctorScheduleRepository implements DoctorScheduleRepositoryInterface
{
    public function __construct(
        protected DoctorScheduleBuilder $doctorScheduleBuilder
    ) {
    }

    public function getBusyDays(int $departmentId, DateTime $from, DateTime $before): array
    {
        $busyDays = DoctorScheduleModel::whereBetween('date', [$from, $before])
            ->whereHas('doctor', function ($builder) use ($departmentId) {
                $builder->where('department_id', $departmentId);
            })
            ->get();

        $result = [];

        foreach ($busyDays as $day) {
            $result[] = $this->makeEntity($day);
        }

        return $result;
    }

    protected function makeEntity(DoctorScheduleModel $doctorScheduleModel): DoctorSchedule
    {
        return $this->doctorScheduleBuilder
            ->setId($doctorScheduleModel->id)
            ->setDoctorId($doctorScheduleModel->doctor_id)
            ->setDate($doctorScheduleModel->date)
            ->make();
    }
}
