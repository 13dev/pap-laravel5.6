<?php

namespace App\Events;

use Cmgmyr\Messenger\Models\Thread;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class NewMessageThread implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    private $thread;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Thread $thread)
    {
        $this->thread = $thread;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        foreach ($this->thread->users as $user) {
            $channels[] = new PrivateChannel('messages.'.$user->id);
        }

        return $channels;
    }

    /**
     * Alias event name.
     * @return string
     */
    public function broadcastAs()
    {
        return 'new.message.thread';
    }

    /**
     * Data sould pass on event.
     * @return array
     */
    public function broadcastWith()
    {
        return [
            'convid' => $this->thread->id,
        ];
    }
}
