<?php

namespace Database\Seeders;

use App\Models\Location;
use App\Models\Monster;
use Illuminate\Database\Seeder;

class MonsterSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $forgottenFields = Location::where('slug', 'forgotten-fields')->first();
        $cursedForest = Location::where('slug', 'cursed-forest')->first();
        $theVoid = Location::where('slug', 'the-void')->first();

        $monsters = [
            // Forgotten Fields (Level 1+)
            ['name' => 'Slime', 'level_modifier' => 0, 'location_id' => $forgottenFields->id],
            ['name' => 'Goblin', 'level_modifier' => 1, 'location_id' => $forgottenFields->id],
            ['name' => 'Wolf', 'level_modifier' => 2, 'location_id' => $forgottenFields->id],

            // Cursed Forest (Level 5+)
            ['name' => 'Spectre', 'level_modifier' => 0, 'location_id' => $cursedForest->id],
            ['name' => 'Wraith', 'level_modifier' => 2, 'location_id' => $cursedForest->id],
            ['name' => 'Witch', 'level_modifier' => 4, 'location_id' => $cursedForest->id],

            // The Void (Level 10+)
            ['name' => 'Voidling', 'level_modifier' => 0, 'location_id' => $theVoid->id],
            ['name' => 'Rift Shade', 'level_modifier' => 3, 'location_id' => $theVoid->id],
            ['name' => 'Null Knight', 'level_modifier' => 6, 'location_id' => $theVoid->id],
        ];

        foreach ($monsters as $monster) {
            Monster::updateOrCreate(['name' => $monster['name'], 'location_id' => $monster['location_id']], $monster);
        }
    }
}
