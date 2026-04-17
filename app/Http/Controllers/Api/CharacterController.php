<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Character;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $character = $user->activeCharacter;

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
            $recoveryAmount = max(1, floor($character->max_hp * 0.10));
            $character->hp += $recoveryAmount;
            
            if ($character->hp > $character->max_hp) {
                $character->hp = $character->max_hp;
            }

            $flavorTexts = [
                "> You are resting and tending to your wounds...",
                "> Focus returns. Recovered {$recoveryAmount} HP.",
                "> The shadows protect you while you recover."
            ];
            $logs[] = $flavorTexts[array_rand($flavorTexts)];

            // Player is resting, ensure they aren't fighting
            if (Cache::has("character_{$character->id}_monster")) {
                Cache::forget("character_{$character->id}_monster");
            }

            $character->save();
        } else {
            // Retrieve or spawn monster
            $monster = Cache::get("character_{$character->id}_monster");

            if (!$monster) {
                // Generate new monster based on character level
                $monsterTypes = ['Slime', 'Goblin', 'Wolf', 'Skeleton', 'Orc'];
                $monsterName = $monsterTypes[array_rand($monsterTypes)];
                $monsterLevel = max(1, $character->level + rand(-1, 1));
                
                $monster = [
                    'id' => uniqid(),
                    'name' => $monsterName,
                    'level' => $monsterLevel,
                    'hp' => 15 * $monsterLevel,
                    'max_hp' => 15 * $monsterLevel,
                    'attack' => 2 * $monsterLevel,
                ];
                
                // IMPORTANT: Save newly spawned monster to Cache so we fight it next turn!
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
                    
                    // Loot logic
                    $goldGained = rand(2, 5) * $monster['level'];
                    $character->gold += $goldGained;
                    $logs[] = "You found {$goldGained} gold.";
                    
                    $expGained = rand(10, 25) * $monster['level'];
                    $character->experience += $expGained;
                    $logs[] = "You gained {$expGained} experience.";
                    
                    if (rand(1, 100) <= 15) { // 15% drop chance
                        $item = "Rusty Sword";
                        $logs[] = "You looted a {$item}!";
                    }
                    
                    $monster = null; // Cleared
                    Cache::forget("character_{$character->id}_monster");
                } else {
                    // Monster fights back
                    $monsterDamage = max(1, $monster['attack'] + rand(-1, 2) - floor($character->defense / 2));
                    $character->hp -= $monsterDamage;
                    $logs[] = "{$monster['name']} attacked you for {$monsterDamage} damage.";
                    
                    Cache::put("character_{$character->id}_monster", $monster, now()->addMinutes(10));
                }
            }

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
                $character->attack += 2;
                $character->defense += 2;
                $logs[] = "Level up! You are now level {$character->level}!";
            }

            $character->save();
        }

        // Format logs for frontend
        $formattedLogs = array_map(function($msg) {
            return [
                'id' => uniqid(),
                'message' => $msg,
                'timestamp' => now()->timestamp
            ];
        }, $logs);

        return response()->json([
            'status' => 'success',
            'data' => [
                'character' => $character,
                'monster' => $monster,
                'logs' => $formattedLogs,
            ],
        ]);
    }
}
