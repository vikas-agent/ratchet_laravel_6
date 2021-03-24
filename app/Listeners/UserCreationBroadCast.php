<?php

namespace App\Listeners;

use App\Events\UserCreationEvent;

class UserCreationBroadCast
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  object  $event
     * @return void
     */
    public function handle(UserCreationEvent $event)
    {
        $newUser = $event->user_data;
        // This is our new stuff
        $context = new \ZMQContext();
        $socket = $context->getSocket(\ZMQ::SOCKET_PUSH, 'my pusher');
        $socket->connect('tcp://localhost:5555');
        $dataInArray = $newUser->toArray();
        $dataInArray['category'] = 'kittensCategory';

        $socket->send(json_encode($dataInArray));
    }
}
