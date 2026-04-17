<?php

namespace App\Events;

use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CombatTickEvent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param  int  $userId  The owning user's ID (used to scope the private channel).
     * @param  array<string, mixed>  $character  Serialised character snapshot.
     * @param  array<string, mixed>|null  $monster  Current monster state, or null when cleared.
     * @param  array<int, array<string, mixed>>  $logs  Formatted combat log entries for this tick.
     */
    public function __construct(
        public readonly int $userId,
        public readonly array $character,
        public readonly ?array $monster,
        public readonly array $logs,
    ) {}

    /**
     * Get the channels the event should broadcast on.
     *
     * Private channel — only the authenticated user whose ID matches can subscribe.
     *
     * @return array<int, PrivateChannel>
     */
    public function broadcastOn(): array
    {
        return [
            new PrivateChannel("App.Models.User.{$this->userId}"),
        ];
    }

    /**
     * The data to broadcast.
     *
     * @return array<string, mixed>
     */
    public function broadcastWith(): array
    {
        return [
            'character' => $this->character,
            'monster' => $this->monster,
            'logs' => $this->logs,
        ];
    }
}
