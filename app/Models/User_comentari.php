<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;

class User_comentari extends Model
{
    use HasFactory;

    protected $table = 'user_comentari';

    protected $fillable = [
        'user_id',
        'ruta_id',
        'comentari',
    ];


    /**
     * Funció per agafar tots els comentaris d'una ruta i afegir el nom de l'usuari i avatar de l'usuari que ha fet el comentari
     */
    public static function getComentaris($ruta_id)
    {
        $comentaris = User_comentari::where('ruta_id', $ruta_id)->get();
        foreach ($comentaris as $comentari) {
            $comentari->user = User::getUserById($comentari->user_id);
            $comentari->created = now()->diffForHumans($comentari->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true], true);
        }
        return $comentaris;
    }

    /**
     * Funció per afegir un comentari a una ruta
     * @param int $user_id id de l'usuari
     * @param int $ruta_id id de la ruta
     * @param string $comentari comentari
     */
    public static function addComentari($user_id, $ruta_id, $comentari)
    {
        User_comentari::create([
            'user_id' => $user_id,
            'ruta_id' => $ruta_id,
            'comentari' => $comentari,
        ]);
    }

    /**
     * Funció per esborrar un comentari per id
     */
    public static function deleteComentari($id)
    {
        return User_comentari::find($id)->delete();
    }


}
