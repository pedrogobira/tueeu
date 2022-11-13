<?php

namespace App\Http\Controllers;

use Exception;
use Ratchet\ConnectionInterface;
use Ratchet\MessageComponentInterface;
use SplObjectStorage;

class WebSocketController extends Controller implements MessageComponentInterface
{
    protected SplObjectStorage $clients;

    public function __construct(SplObjectStorage $splObjectStorage)
    {
        $this->clients = $splObjectStorage;
    }

    function onOpen(ConnectionInterface $conn)
    {
        $this->clients->attach($conn);
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
    }

    function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: $e->getMessage()";
        $conn->close();
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
    }
}
