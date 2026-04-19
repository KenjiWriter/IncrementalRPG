<?php

namespace Database\Seeders;

use App\Models\Item;
use Illuminate\Database\Seeder;

class ItemSeeder extends Seeder
{
    /**
     * Seed starter items covering all equipment slots.
     */
    public function run(): void
    {
        $items = [
            // ── Weapons (slot: weapon) ──────────────────────────────────────
            [
                'name' => 'Rusty Sword',
                'description' => 'A battered blade, but it still cuts.',
                'slot' => 'weapon',
                'rarity' => 'common',
                'attack_bonus' => 5,
            ],
            [
                'name' => 'Iron Longsword',
                'description' => 'Reliable and well-balanced.',
                'slot' => 'weapon',
                'rarity' => 'uncommon',
                'attack_bonus' => 12,
                'str_bonus' => 2,
            ],
            [
                'name' => 'Shadow Dagger',
                'description' => 'Favoured by scouts and assassins.',
                'slot' => 'weapon',
                'rarity' => 'rare',
                'attack_bonus' => 9,
                'dex_bonus' => 4,
            ],

            // ── Head (slot: head) ────────────────────────────────────────────
            [
                'name' => 'Leather Cap',
                'description' => 'Light head protection stitched from boar hide.',
                'slot' => 'head',
                'rarity' => 'common',
                'defense_bonus' => 2,
                'hp_bonus' => 10,
            ],
            [
                'name' => 'Iron Helm',
                'description' => 'Heavy but solid protection.',
                'slot' => 'head',
                'rarity' => 'uncommon',
                'defense_bonus' => 5,
                'vit_bonus' => 2,
                'hp_bonus' => 20,
            ],

            // ── Chest (slot: chest) ─────────────────────────────────────────
            [
                'name' => 'Tattered Robe',
                'description' => 'Barely holds together, but it\'s all you have.',
                'slot' => 'chest',
                'rarity' => 'common',
                'defense_bonus' => 1,
                'int_bonus' => 2,
            ],
            [
                'name' => 'Chainmail Vest',
                'description' => 'Interlocking rings provide solid coverage.',
                'slot' => 'chest',
                'rarity' => 'uncommon',
                'defense_bonus' => 8,
                'hp_bonus' => 15,
            ],

            // ── Off-Hand (slot: off_hand) ───────────────────────────────────
            [
                'name' => 'Wooden Shield',
                'description' => 'Splinters, but absorbs blows.',
                'slot' => 'off_hand',
                'rarity' => 'common',
                'defense_bonus' => 4,
            ],
            [
                'name' => 'Iron Buckler',
                'description' => 'A compact shield, quick to raise.',
                'slot' => 'off_hand',
                'rarity' => 'uncommon',
                'defense_bonus' => 7,
                'dex_bonus' => 1,
            ],

            // ── Ring (slot: ring) ───────────────────────────────────────────
            [
                'name' => 'Copper Ring',
                'description' => 'A plain band with a faint magical resonance.',
                'slot' => 'ring',
                'rarity' => 'common',
                'str_bonus' => 1,
                'dex_bonus' => 1,
            ],

            // ── Accessory (slot: accessory) ─────────────────────────────────
            [
                'name' => 'Adventurer\'s Amulet',
                'description' => 'Worn by countless travellers before you.',
                'slot' => 'accessory',
                'rarity' => 'uncommon',
                'hp_bonus' => 25,
                'vit_bonus' => 2,
            ],

            // ── New Loot Items ──────────────────────────────────────────────
            [
                'name' => 'Tattered Cloth',
                'description' => 'A scrap of fabric from a defeated foe. Maybe useful for something...',
                'slot' => 'accessory',
                'rarity' => 'common',
            ],
            [
                'name' => 'Leather Boots',
                'description' => 'Simple protection for your feet.',
                'slot' => 'feet',
                'rarity' => 'common',
                'defense_bonus' => 1,
            ],
            [
                'name' => 'Wolf Pelt',
                'description' => 'A thick, coarse pelt from a wild wolf.',
                'slot' => 'accessory',
                'rarity' => 'common',
            ],
            [
                'name' => 'Rusty Shield',
                'description' => 'A discarded militia shield. Heavy and awkward.',
                'slot' => 'off_hand',
                'rarity' => 'common',
                'defense_bonus' => 3,
            ],
        ];

        foreach ($items as $item) {
            Item::firstOrCreate(['name' => $item['name']], $item);
        }
    }
}
