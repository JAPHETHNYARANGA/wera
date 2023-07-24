<?php

namespace App\Listeners;

use App\Events\MessageSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Redis;

class MessageSentListener
{
    use InteractsWithQueue;
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(MessageSent $event): void
    {
        // $message = $event->message;

        $redis = Redis::connection();
        $redis->publish('private-chat', json_encode([
            'event' => 'message.sent',
            'data' => [
                // 'message' => $message,
            ]
            ]));

    }
}
