<?php

namespace App\Models;

use Database\Factories\CharacterFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Character extends Model
{
    /** @use HasFactory<CharacterFactory> */
    use HasFactory;

    protected $guarded = ['id'];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'level' => 'integer',
            'experience' => 'integer',
            'gold' => 'integer',
            'hp' => 'integer',
            'max_hp' => 'integer',
            'mana' => 'integer',
            'max_mana' => 'integer',
            'attack' => 'integer',
            'defense' => 'integer',
            'speed' => 'integer',
            'luck' => 'integer',
            // Primary RPG attributes
            'strength' => 'integer',
            'dexterity' => 'integer',
            'intelligence' => 'integer',
            'vitality' => 'integer',
            'current_location_id' => 'integer',
            'last_activity_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function currentLocation(): BelongsTo
    {
        return $this->belongsTo(Location::class, 'current_location_id');
    }

    /**
     * All items in this character's inventory (bag + equipped).
     */
    public function items(): BelongsToMany
    {
        return $this->belongsToMany(Item::class, 'character_item')
            ->withPivot(['id', 'is_equipped', 'slot'])
            ->withTimestamps();
    }

    /**
     * Get all items in a format suitable for the frontend.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getFormattedInventory()
    {
        return $this->items()->get()->map(function ($item) {
            return [
                'id' => $item->pivot->id,
                'item_id' => $item->id,
                'name' => $item->name,
                'description' => $item->description,
                'slot' => $item->slot,
                'rarity' => $item->rarity,
                'is_equipped' => (bool) $item->pivot->is_equipped,
                'bonuses' => $item->bonuses(),
            ];
        });
    }

    /**
     * Only the currently equipped items.
     */
    public function equippedItems(): BelongsToMany
    {
        return $this->items()->wherePivot('is_equipped', true);
    }

    /**
     * Compute derived stats from primary attributes + equipped items.
     *
     * @return array{max_hp: int, attack: int, defense: int}
     */
    public function calculateStats(): array
    {
        $equipped = $this->equippedItems()->get();

        $weaponAttackBonus = 0;
        $totalDefenseBonus = 0;
        $totalHpBonus = 0;

        foreach ($equipped as $item) {
            if ($item->slot === 'weapon') {
                $weaponAttackBonus += $item->attack_bonus;
            }
            $totalDefenseBonus += $item->defense_bonus;
            $totalHpBonus += $item->hp_bonus;
        }

        return [
            'max_hp' => ($this->vitality * 10) + ($this->level * 5) + $totalHpBonus,
            'attack' => ($this->strength * 2) + $weaponAttackBonus,
            'defense' => (int) floor($this->dexterity / 2) + $totalDefenseBonus,
        ];
    }

    /**
     * Persist derived stats back to the characters table so the heartbeat
     * can read pre-computed values without joining items on every tick.
     * Call this every time an item is equipped or unequipped.
     */
    public function applyStats(): void
    {
        $stats = $this->calculateStats();

        $this->max_hp = $stats['max_hp'];
        $this->attack = $stats['attack'];
        $this->defense = $stats['defense'];

        // Clamp current HP so it never exceeds the new max
        if ($this->hp > $this->max_hp) {
            $this->hp = $this->max_hp;
        }

        $this->save();
    }
}
