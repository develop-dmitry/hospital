<?php

declare(strict_types=1);

namespace App\Hospital\Infrastructure\Repository;

use App\Hospital\Domain\DoctorSchedule\DoctorScheduleBuilder;
use App\Hospital\Domain\DoctorSchedule\Exception\ChooseDateFailedException;
use App\Hospital\Domain\DoctorSchedule\Interface\DoctorScheduleRepositoryInterface;
use App\Hospital\Domain\DoctorSchedule\DoctorSchedule;
use App\Models\DoctorSchedule as DoctorScheduleModel;
use DateTime;
use Illuminate\Support\Facades\DB;

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

    public function chooseDates(array $schedules): void
    {
        DB::beginTransaction();

        foreach ($schedules as $schedule) {
            $success = DoctorScheduleModel::create([
                'doctor_id' => $schedule->getDoctorId(),
                'date' => $schedule->getDate()
            ]);

            if (!$success) {
                DB::rollBack();

                throw new ChooseDateFailedException('Failed to choose the dates of work schedule');
            }
        }

        DB::commit();
    }

    public function getDoctorSchedule(int $doctorId): array
    {
        $doctorSchedules = DoctorScheduleModel::where('doctor_id', $doctorId)
            ->whereDate('date', '>', date('Y-m-d'))
            ->orderBy('date')
            ->get();

        $result = [];

        foreach ($doctorSchedules as $doctorSchedule) {
            $result[] = $this->makeEntity($doctorSchedule);
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
