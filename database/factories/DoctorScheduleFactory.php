<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DoctorSchedule;
use App\Models\Doctor;

class DoctorScheduleFactory extends Factory
{
    protected $model = DoctorSchedule::class;

    public function definition(): array
    {
        return [
            'doctor_id' => Doctor::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+1 month'),
        ];
    }
}
