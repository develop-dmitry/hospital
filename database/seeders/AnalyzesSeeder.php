<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Analyzes;

class AnalyzesSeeder extends Seeder
{
    public function run()
    {
        Analyzes::factory()->count(10)->create();
    }
}
