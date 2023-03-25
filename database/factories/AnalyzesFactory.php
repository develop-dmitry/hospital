<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Analyzes;
use App\Models\User;

class AnalyzesFactory extends Factory
{
    protected $model = Analyzes::class;
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
