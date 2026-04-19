<?php

namespace Tests\Unit;

use App\Models\Character;
use App\Models\Item;
use App\Models\Location;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class CharacterStatsTest extends TestCase
{
    use RefreshDatabase;

    public function test_calculate_stats_base_values(): void
    {
        // Must create a location first because characters have a default location_id of 1
        Location::factory()->create(['id' => 1]);

        $character = Character::factory()->create([
            'strength' => 10,
            'dexterity' => 10,
            'intelligence' => 10,
            'vitality' => 10,
            'level' => 1,
        ]);

        $stats = $character->calculateStats();

        // Max HP: (vitality * 10) + (level * 5) = 100 + 5 = 105
        $this->assertEquals(105, $stats['max_hp']);

        // Attack: (strength * 2) = 20
        $this->assertEquals(20, $stats['attack']);

        // Defense: floor(dexterity / 2) = 5
        $this->assertEquals(5, $stats['defense']);
    }

    public function test_calculate_stats_with_equipment(): void
    {
        Location::factory()->create(['id' => 1]);

        $character = Character::factory()->create([
            'strength' => 10,
            'dexterity' => 10,
            'intelligence' => 10,
            'vitality' => 10,
            'level' => 1,
        ]);

        $weapon = Item::create([
            'name' => 'Steel Sword',
            'slot' => 'weapon',
            'attack_bonus' => 10,
        ]);

        $armor = Item::create([
            'name' => 'Plate Mail',
            'slot' => 'chest',
            'defense_bonus' => 15,
            'hp_bonus' => 50,
        ]);

        $character->items()->attach($weapon->id, ['is_equipped' => true, 'slot' => 'weapon']);
        $character->items()->attach($armor->id, ['is_equipped' => true, 'slot' => 'chest']);

        $stats = $character->calculateStats();

        // Max HP: 105 (base) + 50 (armor) = 155
        $this->assertEquals(155, $stats['max_hp']);

        // Attack: 20 (base) + 10 (weapon) = 30
        $this->assertEquals(30, $stats['attack']);

        // Defense: 5 (base) + 15 (armor) = 20
        $this->assertEquals(20, $stats['defense']);
    }

    public function test_apply_stats_persists_to_database(): void
    {
        Location::factory()->create(['id' => 1]);

        $character = Character::factory()->create([
            'strength' => 10,
            'vitality' => 10,
            'level' => 1,
        ]);

        $character->applyStats();

        $character->refresh();
        $this->assertEquals(105, $character->max_hp);
        $this->assertEquals(20, $character->attack);
    }
}
