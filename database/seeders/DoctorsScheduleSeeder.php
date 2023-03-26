<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorSchedule;

class DoctorsScheduleSeeder extends Seeder
{
    public function run()
    {
        DoctorSchedule::factory()->count(10)->create();
    }
}
