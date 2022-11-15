<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreChatRequest;
use App\Models\ChatRequest;
use App\Models\Story;
use App\Models\User;

class ChatRequestController extends Controller
{
    public function store(StoreChatRequest $request)
    {
        $attributes = $request->validated();
        $from = User::find($attributes['from_user_id']);
        $to = User::find($attributes['to_user_id']);
        $story = Story::find($attributes['story_id']);

        if ($from == null || $to == null || $story == null) {
            abort(404);
        }

        ChatRequest::create([
            'from_user_id' => $attributes['from_user_id'],
            'to_user_id' => $attributes['to_user_id'],
            'story_id' => $attributes['story_id'],
            'status' => 'pending'
        ]);

        return redirect()->route('chat.index');
    }
}
