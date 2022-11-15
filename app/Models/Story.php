<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Story extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'body', 'author_id'];

    public function author()
    {
        return $this->hasOne(User::class, 'id', 'author_id');
    }

    public function canBeSent(User $receiver)
    {
        if ($receiver->id == $this->author_id) {
            return false;
        }

        $stories = $receiver->fromChatRequests()->get()->map(function (ChatRequest $chatRequest) {
            return $chatRequest->story_id;
        });

        if ($stories->contains($this->id)) {
            return false;
        }

        $chatRequests = $receiver->chatRequests();
        foreach ($chatRequests as $chatRequest) {
            if ($chatRequest->to_user_id == $this->author_id
                || $chatRequest->from_user_id == $this->author_id) {
                return false;
            }
        }

        return true;
    }

    public function chatRequests()
    {
        return $this->hasMany(ChatRequest::class, 'story_id', 'id');
    }
}
