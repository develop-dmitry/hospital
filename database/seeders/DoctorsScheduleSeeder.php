<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\DoctorsSchedule;

class DoctorsScheduleSeeder extends Seeder
{
    public function run()
    {
        DoctorsSchedule::factory()->count(10)->create();
    }
}
