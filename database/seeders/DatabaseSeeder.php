<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run()
    {
         $this->call(UserSeeder::class);
         $this->call(DoctorSeeder::class);
         $this->call(DoctorsScheduleSeeder::class);
//         $this->call(DepartmentSeeder::class);
//         $this->call(AnalysisSeeder::class);
//         $this->call(AppointmentSeeder::class);
    }
}
