<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Cause extends Model
{
    use HasFactory;

    protected $fillable = [
        'story_id',
        'name',
        'description',
        'original_cause_request_id'
    ];

    public function members()
    {
        return $this->hasMany(Member::class, 'cause_id', 'id');
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class, 'cause_id', 'id');
    }

    public function isMember(User $user)
    {
        $member = $this->members()->get()->filter(fn(Member $member) => $member->user_id == $user->id)->first();

        if($member != null ) {
            return true;
        }

        return false;
    }
}
