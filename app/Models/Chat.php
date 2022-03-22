<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user_1()
    {
        return $this->belongsTo(\App\Models\User::class, 'from');
    }

    public function user_2()
    {
        return $this->belongsTo(\App\Models\User::class, 'to');
    }
}
