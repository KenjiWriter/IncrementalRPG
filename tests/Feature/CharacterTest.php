<?php

use App\Models\Character;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('can create a character through the factory', function () {
    $character = Character::factory()->create([
        'name' => 'Hero',
        'level' => 10,
    ]);

    expect($character)
        ->name->toBe('Hero')
        ->level->toBe(10)
        ->hp->toBe(100)
        ->user_id->not->toBeNull();
});

it('belongs to a user', function () {
    $user = User::factory()->create();
    $character = Character::factory()->for($user)->create();

    expect($character->user->id)->toBe($user->id);
    expect($user->characters)->toHaveCount(1);
});

it('can set an active character for a user', function () {
    $user = User::factory()->create();
    $character = Character::factory()->for($user)->create();

    $user->update(['active_character_id' => $character->id]);

    expect($user->activeCharacter->id)->toBe($character->id);
});
