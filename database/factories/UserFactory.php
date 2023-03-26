<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'name' => $this->faker->name,
            'login' => $this->faker->unique()->userName,
            'email' => $this->faker->unique()->email,
            'password' => Hash::make('secret')
        ];
    }
}
