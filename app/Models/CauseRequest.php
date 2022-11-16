<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CauseRequest extends Model
{
    use HasFactory;

    protected $fillable = ['from_user_id', 'to_user_id', 'story_id', 'status', 'chat_id'];

    public function story()
    {
        return $this->hasOne(Story::class, 'id', 'story_id');
    }
}
