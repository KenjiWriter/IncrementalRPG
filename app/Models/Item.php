<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Item extends Model
{
    protected $fillable = [
        'name',
        'description',
        'slot',
        'rarity',
        'str_bonus',
        'dex_bonus',
        'int_bonus',
        'vit_bonus',
        'attack_bonus',
        'defense_bonus',
        'hp_bonus',
    ];

    protected $casts = [
        'str_bonus' => 'integer',
        'dex_bonus' => 'integer',
        'int_bonus' => 'integer',
        'vit_bonus' => 'integer',
        'attack_bonus' => 'integer',
        'defense_bonus' => 'integer',
        'hp_bonus' => 'integer',
    ];

    /** Characters who have this item in their inventory. */
    public function characters(): BelongsToMany
    {
        return $this->belongsToMany(Character::class, 'character_item')
            ->withPivot(['id', 'is_equipped', 'slot'])
            ->withTimestamps();
    }

    /**
     * Return an array of bonus stats suitable for display.
     *
     * @return array<string, int>
     */
    public function bonuses(): array
    {
        return array_filter([
            'STR' => $this->str_bonus,
            'DEX' => $this->dex_bonus,
            'INT' => $this->int_bonus,
            'VIT' => $this->vit_bonus,
            'ATK' => $this->attack_bonus,
            'DEF' => $this->defense_bonus,
            'HP' => $this->hp_bonus,
        ]);
    }
}
