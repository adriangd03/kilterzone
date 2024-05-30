<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;

class User_comentari extends Model
{
    use HasFactory;

    protected $table = 'user_comentari';

    protected $fillable = [
        'user_id',
        'ruta_id',
        'comentari',
        'likes',
        'comentari_id',
        'editat',
        'esborrat',
    ];


    /**
     * Funció per agafar tots els comentaris d'una ruta i afegir el nom de l'usuari i avatar de l'usuari que ha fet el comentari
     */
    public static function getComentaris($ruta_id)
    {
        $comentaris = User_comentari::where('ruta_id', $ruta_id)->where('comentari_id', null)->get();
        foreach ($comentaris as $comentari) {
            $comentari->user = User::getUserById($comentari->user_id);
            if (!$comentari->user) {
                $comentari->user = (object) [
                    'username' => 'Usuari eliminat',
                    'avatar' => Storage::url('avatar/default.png'),
                    'id' => $comentari->user_id
                ];
            }
            $comentari->created = now()->diffForHumans($comentari->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true], true);
            if($comentari->editat){
                $comentari->edited =  now()->diffForHumans($comentari->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true], true);
            }
            $comentari->respostes = User_comentari::where('comentari_id', $comentari->id)->get();

            foreach ($comentari->respostes as $resposta) {
                $resposta->user = User::getUserById($resposta->user_id);
                if (!$resposta->user) {
                    $resposta->user = (object) [
                        'username' => 'Usuari eliminat',
                        'avatar' => Storage::url('avatar/default.png'),
                        'id' => $resposta->user_id
                    ];
                }
                $resposta->created = now()->diffForHumans($resposta->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true], true);
                if($resposta->editat){
                    $resposta->edited =  now()->diffForHumans($resposta->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true], true);
                }
            }
        }
        return $comentaris;
    }

    /**
     * Funció per afegir un comentari a una ruta
     * @param int $user_id id de l'usuari
     * @param int $ruta_id id de la ruta
     * @param string $comentari comentari
     */
    public static function addComentari($user_id, $ruta_id, $comentari, $comentari_id = null)
    {
        return User_comentari::create([
            'user_id' => $user_id,
            'ruta_id' => $ruta_id,
            'comentari' => $comentari,
            'comentari_id' => $comentari_id,
        ]);
    }


    /**
     * Funció per esborrar un comentari
     * @param User_comentari $comentari objecte del comentari
     */
    public static function eliminarComentari($comentari)
    {
        $comentari->comentari = '[Comentari eliminat]';
        $comentari->esborrat = true;
        $comentari->editat = false;
        $comentari->save();
    }

    /**
     * Funció per trobar un comentari per id
     * @param int $id id del comentari
     * @return User_comentari object del comentari
     */
    public static function getComentari($id)
    {
        return User_comentari::find($id);
    }

    /**
     * Funció per editar un comentari
     * @param User_comentari $comentari objecte del comentari
     * @param string $comentari_nou nou comentari
     */

    public static function editarComentari($comentari, $comentari_nou)
    {
        $comentari->comentari = $comentari_nou;
        $comentari->editat = true;
        $comentari->save();
    }
}
