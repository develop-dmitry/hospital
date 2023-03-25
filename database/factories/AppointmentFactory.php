<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;
use App\Models\User;
use App\Models\Department;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'user_id' => User::factory(),
            'visit_date' => $this->faker->dateTimeBetween('now', '+1 year'),
            'visitor_name' => $this->faker->name,
            'visitor_phone' => $this->faker->phoneNumber,
        ];
    }
}
