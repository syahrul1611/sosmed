<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Friend extends Model
{
    use HasFactory;

    protected $guarded = ['id'];

    public function user_1()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id_1');
    }

    public function user_2()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id_2');
    }
}
