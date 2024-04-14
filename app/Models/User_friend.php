<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_friend extends Model
{
    use HasFactory;

    protected $table = 'user_friend';

    protected $fillable = [
        'user_id',
        'friend_id',
        'accepted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }









}
