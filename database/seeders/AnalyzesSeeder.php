<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Analysis;

class AnalyzesSeeder extends Seeder
{
    public function run()
    {
        Analysis::factory()->count(10)->create();
    }
}
