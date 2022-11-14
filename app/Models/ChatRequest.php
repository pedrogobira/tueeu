<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChatRequest extends Model
{
    use HasFactory;

    protected $fillable = ['from_user_id', 'to_user_id', 'story_id', 'status'];

    public function fromUser()
    {
        return $this->hasOne(User::class, 'id', 'from_user_id');
    }
}
