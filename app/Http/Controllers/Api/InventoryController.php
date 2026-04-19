<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class InventoryController extends Controller
{
    /**
     * Get the authenticated user's character inventory.
     */
    public function index(Request $request): JsonResponse
    {
        $character = $request->user()->activeCharacter;

        if (! $character) {
            return response()->json(['status' => 'error', 'message' => 'No active character'], 404);
        }

        $items = $character->getFormattedInventory();

        return response()->json([
            'status' => 'success',
            'data' => $items,
        ]);
    }

    /**
     * Equip an item from the inventory.
     */
    public function equip(Request $request): JsonResponse
    {
        $character = $request->user()->activeCharacter;
        $pivotId = $request->input('character_item_id');

        // Find the specific item in the character's inventory by its pivot ID
        $characterItem = $character->items()->wherePivot('id', $pivotId)->first();

        if (! $characterItem) {
            return response()->json(['status' => 'error', 'message' => 'Item not found in inventory'], 404);
        }

        $slot = $characterItem->slot;

        // 1. Unequip anything already in this slot
        DB::table('character_item')
            ->where('character_id', $character->id)
            ->where('slot', $slot)
            ->where('is_equipped', true)
            ->update([
                'is_equipped' => false,
                'slot' => null,
            ]);

        // 2. Equip the new item instance by updating its pivot record
        DB::table('character_item')
            ->where('id', $pivotId)
            ->update([
                'is_equipped' => true,
                'slot' => $slot,
            ]);

        // 3. Recompute and persist character stats
        $character->applyStats();

        return response()->json([
            'status' => 'success',
            'message' => "Equipped {$characterItem->name}",
            'data' => [
                'character' => $character->load('currentLocation'),
                'inventory' => $character->getFormattedInventory(),
            ],
        ]);
    }

    /**
     * Unequip an item.
     */
    public function unequip(Request $request): JsonResponse
    {
        $character = $request->user()->activeCharacter;
        $pivotId = $request->input('character_item_id');

        // Verify the item is actually equipped to this character
        $characterItem = $character->items()->wherePivot('id', $pivotId)->wherePivot('is_equipped', true)->first();

        if (! $characterItem) {
            return response()->json(['status' => 'error', 'message' => 'Item is not equipped'], 400);
        }

        // Update the pivot record directly
        DB::table('character_item')
            ->where('id', $pivotId)
            ->update([
                'is_equipped' => false,
                'slot' => null,
            ]);

        // Recompute stats
        $character->applyStats();

        return response()->json([
            'status' => 'success',
            'message' => "Unequipped {$characterItem->name}",
            'data' => [
                'character' => $character->load('currentLocation'),
                'inventory' => $character->getFormattedInventory(),
            ],
        ]);
    }
}
