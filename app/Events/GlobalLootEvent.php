<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class GlobalLootEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  string  $characterName  The name of the character who looted the item.
     * @param  string  $itemName  The name of the item that was found.
     */
    public function __construct(
        public readonly string $characterName,
        public readonly string $itemName,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * Public channel — no auth required, every connected client receives this.
     *
     * @return array<int, Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('global-loot'),
        ];
    }

    /**
     * The data to broadcast.
     *
     * @return array<string, string>
     */
    public function broadcastWith(): array
    {
        return [
            'character_name' => $this->characterName,
            'item_name' => $this->itemName,
        ];
    }
}
