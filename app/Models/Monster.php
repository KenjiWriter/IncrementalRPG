<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Monster extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'level_modifier',
        'location_id',
        'loot_table',
    ];

    protected $casts = [
        'level_modifier' => 'integer',
        'location_id' => 'integer',
        'loot_table' => 'array',
    ];

    public function location(): BelongsTo
    {
        return $this->belongsTo(Location::class);
    }
}
