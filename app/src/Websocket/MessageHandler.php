<?php
namespace App\Websocket;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;


class MessageHandler implements MessageComponentInterface
{

    protected $connections;

    public function __construct()
    {
        $this->connections = new SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        echo("onOpen\n\n");
        $this->connections->attach($conn);
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        echo("onMessage\n");
        echo($msg);
        echo("\n\n");
        foreach($this->connections as $connection)
        {
            $connection->send($msg);
        }
    }

    public function onClose(ConnectionInterface $conn)
    {
        echo("onClose\n\n");
        $this->connections->detach($conn);
    }

    public function onError(ConnectionInterface $conn, Exception $e)
    {
        echo("onError\n\n");
        $this->connections->detach($conn);
        $conn->close();
    }
}