<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class User_escalada extends Model
{
    use HasFactory;

    protected $table = 'user_escalada';

    protected $fillable = [
        'user_id',
        'ruta_id',
    ];

    public $timestamps = false;

    /**
     * Funció per afegir una escalada a una ruta
     * @param int $user_id id de l'usuari
     * @param int $ruta_id id de la ruta
     */
    public static function escalada($user_id, $ruta_id)
    {
        $escalada = User_escalada::where('user_id', $user_id)->where('ruta_id', $ruta_id)->first();
        if ($escalada) {
            $escalada->delete();
            return false;
        } else {
            User_escalada::create([
                'user_id' => $user_id,
                'ruta_id' => $ruta_id,
            ]);
            return true;
        }
    }

    /**
     * Funció per comprovar si un usuari ha escalat una ruta
     * @param int $user_id  id de l'usuari
     * @param int $ruta_id  id de la ruta
     */
    public static function haEscalat($user_id, $ruta_id)
    {
        $escalada = User_escalada::where('user_id', $user_id)->where('ruta_id', $ruta_id)->first();
        if ($escalada) {
            return true;
        } else {
            return false;
        }
    }
}
