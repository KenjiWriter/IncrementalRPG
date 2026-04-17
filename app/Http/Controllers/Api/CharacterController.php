<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CharacterController extends Controller
{
    /**
     * Get the active character.
     */
    public function getActive(Request $request): JsonResponse
    {
        // Temporary: Create a mock user & character to simulate an active session
        $user = User::firstOrCreate(
            ['email' => 'hero@eternalshard.com'],
            ['name' => 'Hero', 'password' => bcrypt('password')]
        );

        $character = Character::firstOrCreate(
            ['user_id' => $user->id],
            [
                'name' => 'Wandering Soul',
                'level' => 1,
                'experience' => 0,
                'gold' => 0,
                'hp' => 100,
                'max_hp' => 100,
                'mana' => 50,
                'max_mana' => 50,
            ]
        );

        return response()->json([
            'status' => 'success',
            'data' => $character,
        ]);
    }

    /**
     * Combat heartbeat / ticker to simulate game progression.
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $user = User::where('email', 'hero@eternalshard.com')->first();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $character = Character::where('user_id', $user->id)->first();
        if (! $character) {
            return response()->json(['status' => 'error', 'message' => 'No active character'], 404);
        }

        if ($character->hp > 0) {
            // Simulate combat: take damage and gain exp
            $damageTaken = rand(2, 8);
            $character->hp -= $damageTaken;
            $character->experience += rand(15, 45);

            if ($character->hp < 0) {
                $character->hp = 0;
            }

            // Level up logic
            $expNeededForNextLevel = $character->level * 1000;
            if ($character->experience >= $expNeededForNextLevel) {
                $character->level += 1;
                $character->experience -= $expNeededForNextLevel;
                $character->max_hp += 20;
                $character->hp = $character->max_hp;
            }

            $character->save();
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'character' => $character,
                'action_log' => 'Fought a monster in the dark fields.',
            ],
        ]);
    }
}
