<?php

namespace App\Events;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
class NotificationEvent implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $username;
    public $message;
    public function __construct($username)
    {
        $this->username = $username;
        $this->message  = "{$username} send you a notification";
    }
    public function broadcastOn()
    {
        //it is a broadcasting channel you need to add this route in channels.php file
        return ['notification-send'];
    }
}
