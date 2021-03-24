<?php

namespace App\Console\Commands;

use App\Http\Controllers\WebSocketPushController;
use Illuminate\Console\Command;
use React\EventLoop\Factory;
use React\Socket\Server;
use React\ZMQ\Context;

class WebSocketPush extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'websocketpush:init';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to run websocket server for php.';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $loop = Factory::create();
        $pusher = new WebSocketPushController();

        // Listen for the web server to make a ZeroMQ push after an ajax request
        $context = new Context($loop);
        $pull = $context->getSocket(\ZMQ::SOCKET_PULL);
        $pull->bind('tcp://127.0.0.1:5555'); // Binding to 127.0.0.1 means the only client that can connect is itself
        $pull->on('message', [$pusher, 'onBlogEntry']);

        // Set up our WebSocket server for clients wanting real-time updates
        $webSock = new Server('0.0.0.0:8091', $loop); // Binding to 0.0.0.0 means remotes can connect
        $webServer = new \Ratchet\Server\IoServer(
            new \Ratchet\Http\HttpServer(
                new \Ratchet\WebSocket\WsServer(
                    new \Ratchet\Wamp\WampServer(
                        $pusher
                    )
                )
            ),
            $webSock
        );
        $loop->run();
    }
}
