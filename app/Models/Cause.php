<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

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
}
