<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Analysis;
use App\Models\User;

class AnalysisFactory extends Factory
{
    protected $model = Analysis::class;
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word,
            'link' => $this->faker->url,
            'uploaded_user' => User::factory(),
        ];
    }
}
