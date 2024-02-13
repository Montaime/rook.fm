<?php

namespace App\Events;

use App\Models\ChatMessage;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ChatMessageCreated implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public array $user;
//    public ChatMessage $message;
    public string $crap = 'default crap';

    /**
     * Create a new event instance.
     */
//    public function __construct(User $user, ChatMessage $message)
    public function __construct(string $crap, User $user)
    {
        $this->user = [
            'id' => $user->id,
            'name' => $user->name,
        ];
//        $this->message = $message;
        $this->crap = $crap;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return array<int, \Illuminate\Broadcasting\Channel>
     */
    public function broadcastOn(): array
    {
        return [
            new Channel('chatroom'),
        ];
    }
}
