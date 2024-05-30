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


    /**
     * Funció per agafar totes les rutes d'un usuari
     */
    public static function getRutes($user_id)
    {
        return User_ruta::where('user_id', $user_id)->get();
    }

    /**
     * Funció per agafar una ruta per id
     */
    public static function getRuta($id)
    {
        return User_ruta::find($id);
    }

    /**
     * Funció per comprobar si un usuari es el creador d'una ruta
     * @param int $id id de la ruta
     * @param int $user_id id de l'usuari
     * @return bool true si l'usuari es el creador de la ruta, false si no ho es
     */
    public static function isCreador($id, $user_id)
    {
        $ruta = User_ruta::find($id);
        return $ruta->user_id == $user_id;
    }

    /**
     * Funció per esborrar una ruta per id
     */
    public static function deleteRuta($id)
    {
        return User_ruta::find($id)->delete();
    }

    /**
     * Funció per sumar un like a una ruta
     */
    public static function sumarLike($ruta)
    {
        $ruta->likes = $ruta->likes + 1;
        $ruta->save();
    }

    /**
     * Funció per restar un like a una ruta
     */
    public static function restarLike($ruta)
    {
        $ruta->likes = $ruta->likes - 1;
        $ruta->save();
    }

    /**
     * Funció per sumar una escalada a una ruta
     */
    public static function sumarEscalada($ruta)
    {
        $ruta->escalada = $ruta->escalada + 1;
        $ruta->save();
    }

    /**
     * Funció per restar una escalada a una ruta
     */
    public static function restarEscalada($ruta)
    {
        $ruta->escalada = $ruta->escalada - 1;
        $ruta->save();
    }



    
}
