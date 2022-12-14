<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'token',
        'connection_id',
        'user_status'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function fromChatRequests(): HasMany
    {
        return $this->hasMany(ChatRequest::class, 'from_user_id', 'id');
    }

    public function getStoryByContact(User $contact)
    {
        $chatRequest = $this->chatRequests()
            ->filter(function (ChatRequest $chatRequest) use ($contact) {
                if ($chatRequest->from_user_id == $contact->id
                    || $chatRequest->to_user_id == $contact->id) {
                    return $chatRequest;
                }
            })->first();

        return $chatRequest->story;
    }

    public function chatRequests()
    {
        return ChatRequest::where('from_user_id', $this->id)->orWhere('to_user_id', $this->id)->get();
    }

    public function members(): HasMany
    {
        return $this->hasMany(Member::class, 'user_id', 'id');
    }
}
