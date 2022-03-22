<?php

namespace App\Models;

use Laravel\Scout\Searchable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, Searchable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = ['id'];

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

    public function toSearchableArray()
    {
        return [
            'name' => $this->name,
            'username' => $this->username
        ];
    }

    public function posts()
    {
        return $this->hasMany(\App\Models\Post::class);
    }

    public function following()
    {
        return $this->hasMany(\App\Models\Friend::class, 'user_id_1');
    }

    public function followed()
    {
        return $this->hasMany(\App\Models\Friend::class, 'user_id_2');
    }

    public function likes()
    {
        return $this->hasMany(\App\Models\Like::class);
    }

    public function comments()
    {
        return $this->hasMany(\App\Models\Comment::class);
    }

    public function chats()
    {
        return $this->hasMany(\App\Models\Chat::class);
    }
}
