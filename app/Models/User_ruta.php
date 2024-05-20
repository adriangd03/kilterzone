<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_ruta extends Model
{
    use HasFactory;

    protected $table = 'user_ruta';

    protected $fillable = [
        'user_id',
        'ruta',
        'nom_ruta',
        'descripcio',
        'dificultat',
        'inclinacio',
        'layout',
    ];

    
}
