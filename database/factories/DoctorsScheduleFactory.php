<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\DoctorsSchedule;
use App\Models\User;

class DoctorsScheduleFactory extends Factory
{
    protected $model = DoctorsSchedule::class;

    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'date' => $this->faker->dateTimeBetween('now', '+1 year'),
        ];
    }
}
