<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_message extends Model
{
    use HasFactory;

    protected $table = 'user_message';

    protected $fillable = [
        'user_id',
        'receiver_id',
        'message',
        'read',
    ];

    
}
