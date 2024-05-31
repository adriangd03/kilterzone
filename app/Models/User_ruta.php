<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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


    /**
     * Funció per obtenir les últimes 5 rutes creades per els amics d'un usuari
     * @param int $user_id Id de l'usuari
     * @return array<User_ruta> Rutes
     */
    public static function getFriendsLatestRutes($user_id)
    {
        $friends = User_friend::getFriends($user_id);
        $rutes = collect();
        foreach ($friends as $friend) {
            $rutes = $rutes->merge(User_ruta::getRutes($friend->id));
        }

        foreach ($rutes as $ruta) {
            $ruta->creador = User::getUserById($ruta->user_id);
            Carbon::setLocale('ca');
            $ruta->created = now()->diffForHumans($ruta->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true]);
        }

        return $rutes->sortByDesc('created_at')->take(15);
    }

    /**
     * Funció per obtenir les rutes més populars de la setmana
     * @return array<User_ruta> Rutes
     */
    public static function getRutesPopulars()
    {
        $rutes = User_ruta::where('created_at', '>=', now()->subWeek())->get();
        $rutes = $rutes->sortByDesc('likes');

        if ($rutes->count() >= 15) {

            foreach ($rutes as $ruta) {
                $ruta->creador = User::getUserById($ruta->user_id);
                Carbon::setLocale('ca');
                $ruta->created = now()->diffForHumans($ruta->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true]);
            }

            return $rutes->take(15);
        } else {

            $rutes = $rutes->merge(User_ruta::where('created_at', '<', now()->subweek())->get()->sortByDesc('likes')->take((15 - $rutes->count())));

            foreach ($rutes as $ruta) {
                $ruta->creador = User::getUserById($ruta->user_id);
                Carbon::setLocale('ca');
                $ruta->created = now()->diffForHumans($ruta->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true]);
            }

            return $rutes;
        }
    }

    /**
     * Funció per agafar les rutes ales que el usuari ha donat like
     * @param int $user_id Id de l'usuari
     * @return object<User_ruta> Rutes
     */
    public static function getLikedRutes($user_id)
    {
        $likes = User_like::where('user_id', $user_id)->get();
        $rutes = collect();
        foreach ($likes as $like) {
            $rutes->push(User_ruta::getRuta($like->ruta_id));
        }

        foreach ($rutes as $ruta) {
            $ruta->creador = User::getUserById($ruta->user_id);
            Carbon::setLocale('ca');
            $ruta->created = now()->diffForHumans($ruta->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true]);
        }

        return $rutes;
    }

    /**
     * Funció per agafar les rutes ales que el usuari ha escalat
     * @param int $user_id Id de l'usuari
     * @return object<User_ruta> Rutes
     */
    public static function getEscalades($user_id)
    {
        $escalades = User_escalada::where('user_id', $user_id)->get();
        $rutes = collect();
        foreach ($escalades as $escalada) {
            $rutes->push(User_ruta::getRuta($escalada->ruta_id));
        }

        foreach ($rutes as $ruta) {
            $ruta->creador = User::getUserById($ruta->user_id);
            Carbon::setLocale('ca');
            $ruta->created = now()->diffForHumans($ruta->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true]);
        }

        return $rutes;
    }

    /**
     * Funció per esborrar una ruta per id
     * @param int $id Id de la ruta
     * @return bool true si s'ha esborrat la ruta, false si no s'ha esborrat
     */
    public static function deleteRuta($id)
    {
        return User_ruta::find($id)->delete();
    }
}
