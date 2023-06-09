<?php

namespace Database\Factories;

use App\Models\Client;
use App\Models\Doctor;
use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Appointment;
use App\Models\Department;

class AppointmentFactory extends Factory
{
    protected $model = Appointment::class;

    public function definition(): array
    {
        return [
            'department_id' => Department::factory(),
            'client_id' => Client::factory(),
            'doctor_id' => Doctor::factory(),
            'visit_date' => $this->faker->dateTimeBetween('now', '+1 month'),
            'visitor_name' => $this->faker->name,
            'visitor_phone' => $this->faker->phoneNumber,
            'visit_time' => $this->faker->time
        ];
    }
}
