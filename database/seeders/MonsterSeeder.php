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

        $items = \App\Models\Item::all()->pluck('id', 'name');

        $monsters = [
            // Forgotten Fields (Level 1+)
            [
                'name' => 'Slime',
                'level_modifier' => 0,
                'location_id' => $forgottenFields->id,
                'loot_table' => [
                    ['item_id' => $items['Rusty Sword'], 'chance' => 15],
                    ['item_id' => $items['Tattered Cloth'], 'chance' => 25],
                ],
            ],
            [
                'name' => 'Goblin',
                'level_modifier' => 1,
                'location_id' => $forgottenFields->id,
                'loot_table' => [
                    ['item_id' => $items['Rusty Shield'], 'chance' => 12],
                    ['item_id' => $items['Leather Cap'], 'chance' => 10],
                ],
            ],
            [
                'name' => 'Wolf',
                'level_modifier' => 2,
                'location_id' => $forgottenFields->id,
                'loot_table' => [
                    ['item_id' => $items['Leather Boots'], 'chance' => 10],
                    ['item_id' => $items['Wolf Pelt'], 'chance' => 20],
                    ['item_id' => $items['Iron Longsword'], 'chance' => 5], // Rare drop from Wolf
                ],
            ],

            // Cursed Forest (Level 5+)
            [
                'name' => 'Spectre', 
                'level_modifier' => 0, 
                'location_id' => $cursedForest->id,
                'loot_table' => [
                    ['item_id' => $items['Tattered Robe'], 'chance' => 15],
                    ['item_id' => $items['Shadow Dagger'], 'chance' => 5],
                ],
            ],
            [
                'name' => 'Wraith', 
                'level_modifier' => 2, 
                'location_id' => $cursedForest->id,
                'loot_table' => [
                    ['item_id' => $items['Iron Helm'], 'chance' => 8],
                    ['item_id' => $items['Copper Ring'], 'chance' => 12],
                ],
            ],
            [
                'name' => 'Witch', 
                'level_modifier' => 4, 
                'location_id' => $cursedForest->id,
                'loot_table' => [
                    ['item_id' => $items['Adventurer\'s Amulet'], 'chance' => 10],
                    ['item_id' => $items['Shadow Dagger'], 'chance' => 8],
                ],
            ],

            // The Void (Level 10+)
            [
                'name' => 'Voidling', 
                'level_modifier' => 0, 
                'location_id' => $theVoid->id,
                'loot_table' => [
                    ['item_id' => $items['Copper Ring'], 'chance' => 20],
                ],
            ],
            [
                'name' => 'Rift Shade', 
                'level_modifier' => 3, 
                'location_id' => $theVoid->id,
                'loot_table' => [
                    ['item_id' => $items['Chainmail Vest'], 'chance' => 10],
                ],
            ],
            [
                'name' => 'Null Knight', 
                'level_modifier' => 6, 
                'location_id' => $theVoid->id,
                'loot_table' => [
                    ['item_id' => $items['Iron Buckler'], 'chance' => 15],
                    ['item_id' => $items['Iron Longsword'], 'chance' => 15],
                ],
            ],
        ];

        foreach ($monsters as $monster) {
            Monster::updateOrCreate(['name' => $monster['name'], 'location_id' => $monster['location_id']], $monster);
        }
    }
}
