<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_like extends Model
{
    use HasFactory;

    protected $table = 'user_like';

    protected $fillable = [
        'user_id',
        'ruta_id',
    ];

    public $timestamps = false;

    /**
     * Funció per afegir o treure un like a una ruta
     * @param int $user_id id de l'usuari
     * @param int $ruta_id id de la ruta
     */
    public static function like($user_id, $ruta_id)
    {
        $like = User_like::where('user_id', $user_id)->where('ruta_id', $ruta_id)->first();
        if ($like) {
            $like->delete();
            return false;
        } else {
            User_like::create([
                'user_id' => $user_id,
                'ruta_id' => $ruta_id,
            ]);
            return true;
        }
    }

    /**
     * Funció per comprovar si un usuari ha donat like a una ruta
     * @param int $user_id  id de l'usuari
     * @param int $ruta_id  id de la ruta
     */
    public static function isLiked($user_id, $ruta_id)
    {
        $like = User_like::where('user_id', $user_id)->where('ruta_id', $ruta_id)->first();
        if ($like) {
            return true;
        } else {
            return false;
        }
    }

    




}
