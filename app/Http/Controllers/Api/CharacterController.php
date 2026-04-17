<?php

namespace App\Http\Controllers\Api;

use App\Events\CombatTickEvent;
use App\Events\GlobalLootEvent;
use App\Http\Controllers\Controller;
use App\Models\Location;
use App\Models\Monster;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CharacterController extends Controller
{
    /**
     * Get the active character.
     */
    public function getActive(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $character = $user->activeCharacter()->with('currentLocation')->first();

        if (! $character) {
            return response()->json(['status' => 'error', 'message' => 'No active character'], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $character,
        ]);
    }

    /**
     * Combat heartbeat / ticker to simulate game progression.
     *
     * Broadcasts a CombatTickEvent via private channel on every tick.
     * Broadcasts a GlobalLootEvent via public channel only on significant (item) drops.
     */
    public function heartbeat(Request $request): JsonResponse
    {
        $user = $request->user();
        if (! $user) {
            return response()->json(['status' => 'error', 'message' => 'User not found'], 404);
        }

        $character = $user->activeCharacter;
        if (! $character) {
            return response()->json(['status' => 'error', 'message' => 'No active character'], 404);
        }

        $logs = [];
        $monster = null;

        $restingThreshold = $character->max_hp * 0.5;

        if ($character->hp < $restingThreshold) {
            // Resting sequence (regenerate 10% max hp)
            $recoveryAmount = max(1, (int) floor($character->max_hp * 0.10));
            $character->hp += $recoveryAmount;

            if ($character->hp > $character->max_hp) {
                $character->hp = $character->max_hp;
            }

            $flavorTexts = [
                '> You are resting and tending to your wounds...',
                "> Focus returns. Recovered {$recoveryAmount} HP.",
                '> The shadows protect you while you recover.',
            ];
            $logs[] = $flavorTexts[array_rand($flavorTexts)];

            // Player is resting — clear any active monster
            if (Cache::has("character_{$character->id}_monster")) {
                Cache::forget("character_{$character->id}_monster");
            }

            $character->save();
        } else {
            // Retrieve or spawn monster
            $monster = Cache::get("character_{$character->id}_monster");

            if (! $monster) {
                // Generate new monster based on character location
                $availableMonsters = Monster::where('location_id', $character->current_location_id)->get();
                
                if ($availableMonsters->isEmpty()) {
                    // Fallback to random if no monsters found for location (should not happen with seeders)
                    $monsterType = ['Slime', 'Goblin', 'Wolf'];
                    $monsterName = $monsterType[array_rand($monsterType)];
                    $levelMod = 0;
                } else {
                    $mModel = $availableMonsters->random();
                    $monsterName = $mModel->name;
                    $levelMod = $mModel->level_modifier;
                }

                $monsterLevel = max(1, $character->level + $levelMod + rand(-1, 1));

                $monster = [
                    'id' => uniqid(),
                    'name' => $monsterName,
                    'level' => $monsterLevel,
                    'hp' => 15 * $monsterLevel,
                    'max_hp' => 15 * $monsterLevel,
                    'attack' => 2 * $monsterLevel,
                ];

                Cache::put("character_{$character->id}_monster", $monster, now()->addMinutes(10));

                $logs[] = "A wild {$monster['name']} appeared!";
            } else {
                // Combat simulation
                $playerDamage = max(1, $character->attack + rand(-2, 2));
                $monster['hp'] -= $playerDamage;
                $logs[] = "You attacked {$monster['name']} for {$playerDamage} damage.";

                if ($monster['hp'] <= 0) {
                    $monster['hp'] = 0;
                    $logs[] = "You defeated {$monster['name']}!";

                    // Gold gain — private, stays in the tick log only
                    $goldGained = rand(2, 5) * $monster['level'];
                    $character->gold += $goldGained;
                    $logs[] = "You found {$goldGained} gold.";

                    $expGained = rand(10, 25) * $monster['level'];
                    $character->experience += $expGained;
                    $logs[] = "You gained {$expGained} experience.";

                    // Significant item drop — broadcast to the global public channel
                    if (rand(1, 100) <= 15) {
                        $item = 'Rusty Sword';
                        $logs[] = "You looted a {$item}!";

                        GlobalLootEvent::dispatch($character->name, $item);
                    }

                    $monster = null;
                    Cache::forget("character_{$character->id}_monster");
                } else {
                    // Monster fights back
                    $monsterDamage = max(1, $monster['attack'] + rand(-1, 2) - (int) floor($character->defense / 2));
                    $character->hp -= $monsterDamage;
                    $logs[] = "{$monster['name']} attacked you for {$monsterDamage} damage.";

                    Cache::put("character_{$character->id}_monster", $monster, now()->addMinutes(10));
                }
            }

            if ($character->hp < 0) {
                $character->hp = 0;
            }

            // Level-up logic
            $expNeededForNextLevel = $character->level * 1000;
            if ($character->experience >= $expNeededForNextLevel) {
                $character->level += 1;
                $character->experience -= $expNeededForNextLevel;
                $character->max_hp += 20;
                $character->hp = $character->max_hp;
                $character->attack += 2;
                $character->defense += 2;
                $logs[] = "Level up! You are now level {$character->level}!";
            }

            $character->save();
        }

        // Format logs with metadata
        $formattedLogs = array_map(
            fn (string $msg) => [
                'id' => uniqid(),
                'message' => $msg,
                'timestamp' => now()->timestamp,
            ],
            $logs
        );

        // Broadcast the full tick snapshot to this user's private channel (bypasses queue)
        CombatTickEvent::dispatch(
            $user->id,
            $character->toArray(),
            $monster,
            $formattedLogs,
        );

        return response()->json([
            'status' => 'success',
            'data' => [
                'character' => $character->load('currentLocation'),
                'monster' => $monster,
                'logs' => $formattedLogs,
            ],
        ]);
    }

    /**
     * Change the character's current location.
     */
    public function changeLocation(Request $request): JsonResponse
    {
        $user = $request->user();
        $character = $user->activeCharacter;
        
        $locationId = $request->input('location_id');
        $location = Location::find($locationId);

        if (!$location) {
            return response()->json(['status' => 'error', 'message' => 'Location not found'], 404);
        }

        if ($character->level < $location->min_level) {
            return response()->json(['status' => 'error', 'message' => "Requies Level {$location->min_level}"], 403);
        }

        $character->current_location_id = $location->id;
        $character->save();

        // Clear active monster when changing zones
        Cache::forget("character_{$character->id}_monster");

        return response()->json([
            'status' => 'success',
            'data' => [
                'character' => $character->load('currentLocation'),
            ]
        ]);
    }
}
