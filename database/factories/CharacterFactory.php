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
            'hp' => 105,  // vitality(10)*10 + level(1)*5
            'max_hp' => 105,
            'mana' => 50,
            'max_mana' => 50,
            'attack' => 20,   // strength(10)*2
            'defense' => 5,    // floor(dexterity(10)/2)
            'speed' => 10,
            'luck' => 5,
            // Primary RPG attributes
            'strength' => 10,
            'dexterity' => 10,
            'intelligence' => 10,
            'vitality' => 10,
            'last_activity_at' => now(),
        ];
    }
}
