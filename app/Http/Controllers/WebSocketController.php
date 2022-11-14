<?php

namespace App\Http\Controllers;

use App\Models\ChatRequest;
use App\Models\User;
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
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryArray);
        if (isset($queryArray['token'])) {
            User::where('token', $queryArray['token'])->update(['connection_id' => $conn->resourceId]);
        }
    }

    function onClose(ConnectionInterface $conn)
    {
        $this->clients->detach($conn);
        $queryString = $conn->httpRequest->getUri()->getQuery();
        parse_str($queryString, $queryArray);
        if (isset($queryArray['token'])) {
            User::where('token', $queryArray['token'])->update(['connection_id' => null]);
        }
    }

    function onError(ConnectionInterface $conn, Exception $e)
    {
        echo "An error has occurred: $e";
        $conn->close();
    }

    function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg);

        if (!isset($data->type)) {
            return null;
        }

        match ($data->type) {
            'request_load_unconnected_user' => $this->getUnconnectedUsers($data),
            'request_load_unread_notification' => $this->getUnreadNotification($data),
            'request_chat_processing' => $this->processChatRequest($data)
        };
    }

    private function getUnconnectedUsers($data)
    {
        $users = User::select('id', 'name', 'user_status')->where('id', '!=', $data->from_user_id)->orderBy('name', 'ASC')->get();

        $subData = [];
        foreach ($users as $user) {
            $subData[] = ['id' => $user->id, 'name' => $user->name, 'status' => $user->user_status];
        }

        $senderConnectionId = User::select('connection_id')->where('id', $data->from_user_id)->first();
        $sendData['data'] = $subData;
        $sendData['response_load_unconnected_user'] = true;

        foreach ($this->clients as $client) {
            if ($client->resourceId == $senderConnectionId->connection_id) {
                $client->send(json_encode($sendData));
            }
        }
    }

    private function getUnreadNotification($data)
    {
        $chatRequests = ChatRequest::select('id', 'from_user_id', 'to_user_id', 'story_id', 'status')
            ->where('status', 'pending')
            ->where('to_user_id', $data->user_id)
            ->orderBy('id', 'ASC')
            ->get();

        $subData = [];
        foreach ($chatRequests as $chatRequest) {
            $user = User::select('name')->where('id', $chatRequest->from_user_id)->first();
            $subData[] = [
                'id' => $chatRequest->id,
                'from_user_id' => $chatRequest->from_user_id,
                'to_user_id' => $chatRequest->to_user_id,
                'name' => $user->name,
                'status' => $chatRequest->status
            ];
        }

        $sendData['data'] = $subData;
        $sendData['response_load_unread_notification'] = true;

        $sendingConnectionId = User::select('connection_id')->where('id', $data->user_id)->first();

        foreach ($this->clients as $client) {
            if ($client->resourceId == $sendingConnectionId->connection_id) {
                $client->send(json_encode($sendData));
            }
        }
    }

    private function processChatRequest($data)
    {
        ChatRequest::where('id', $data->chat_request_id)->update(['status' => $data->action]);
        $senderConnectionId = User::select('connection_id')->where('id', $data->from_user_id)->first();
        $receiverConnectionId = User::select('connection_id')->where('id', $data->to_user_id)->first();
        foreach ($this->clients as $client) {
            if ($client->resourceId == $senderConnectionId->connection_id) {
                $sendData['response_chat_processing'] = true;
                $sendData['data'] = $data->from_user_id;
                $client->send(json_encode($sendData));
            }
            if ($client->resourceId == $receiverConnectionId->connection_id) {
                $sendData['response_chat_processing'] = true;
                $sendData['data'] = $data->to_user_id;
                $client->send(json_encode($sendData));
            }
        }
    }
}
