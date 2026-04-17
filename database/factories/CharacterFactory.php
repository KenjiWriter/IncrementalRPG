<?php

namespace Database\Factories;

use App\Models\Character;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Character>
 */
class CharacterFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'name' => fake()->unique()->firstName(),
            'level' => 1,
            'experience' => 0,
            'gold' => 0,
            'hp' => 100,
            'max_hp' => 100,
            'mana' => 50,
            'max_mana' => 50,
            'attack' => 10,
            'defense' => 10,
            'speed' => 10,
            'luck' => 5,
            'last_activity_at' => now(),
        ];
    }
}
