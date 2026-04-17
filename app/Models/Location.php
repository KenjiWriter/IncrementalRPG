<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Location extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'min_level',
        'css_theme',
    ];

    protected $casts = [
        'css_theme' => 'array',
        'min_level' => 'integer',
    ];

    public function monsters(): HasMany
    {
        return $this->hasMany(Monster::class);
    }

    public function characters(): HasMany
    {
        return $this->hasMany(Character::class, 'current_location_id');
    }
}
