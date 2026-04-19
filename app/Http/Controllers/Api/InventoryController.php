<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

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

        $items = $character->items()->get()->map(function ($item) {
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

        $characterItem = $character->items()->wherePivot('id', $pivotId)->first();

        if (! $characterItem) {
            return response()->json(['status' => 'error', 'message' => 'Item not found in inventory'], 404);
        }

        $slot = $characterItem->slot;

        // Unequip anything already in this slot
        $character->items()
            ->wherePivot('is_equipped', true)
            ->wherePivot('slot', $slot)
            ->updateExistingPivot($character->items()->wherePivot('is_equipped', true)->wherePivot('slot', $slot)->first()?->id, [
                'is_equipped' => false,
                'slot' => null,
            ]);

        // Note: The above updateExistingPivot is a bit clunky with wherePivot.
        // Let's do it more cleanly.

        $character->equippedItems()
            ->where('slot', $slot)
            ->get()
            ->each(function ($item) use ($character) {
                $character->items()->updateExistingPivot($item->id, [
                    'is_equipped' => false,
                    'slot' => null,
                ]);
            });

        // Equip the new item
        $character->items()->updateExistingPivot($characterItem->id, [
            'is_equipped' => true,
            'slot' => $slot,
        ]);

        // Recompute stats
        $character->applyStats();

        return response()->json([
            'status' => 'success',
            'message' => "Equipped {$characterItem->name}",
            'data' => [
                'character' => $character->load('currentLocation'),
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

        $characterItem = $character->items()->wherePivot('id', $pivotId)->first();

        if (! $characterItem || ! $characterItem->pivot->is_equipped) {
            return response()->json(['status' => 'error', 'message' => 'Item is not equipped'], 400);
        }

        $character->items()->updateExistingPivot($characterItem->id, [
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
            ],
        ]);
    }
}
