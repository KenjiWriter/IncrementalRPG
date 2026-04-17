<?php

use Illuminate\Support\Facades\Broadcast;

Broadcast::channel('App.Models.User.{id}', function ($user, $id) {
    return (int) $user->id === (int) $id;
});

Broadcast::channel('location.{locationId}', function ($user, $locationId) {
    $character = $user->activeCharacter;
    if (! $character || $character->current_location_id != $locationId) {
        return false;
    }
    return ['id' => $user->id, 'name' => $character->name, 'level' => $character->level];
});
