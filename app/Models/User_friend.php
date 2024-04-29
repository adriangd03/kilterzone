<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class User_friend extends Model
{
    use HasFactory;

    protected $table = 'user_friend';


    protected $fillable = [
        'user_id',
        'friend_id',
        'accepted',
    ];

    /**
     * Funció que retorna l'usuari que ha enviat la sol·licitud d'amistat
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Funció que retorna l'usuari que ha rebut la sol·licitud d'amistat
     */
    public function friend()
    {
        return $this->belongsTo(User::class, 'friend_id');
    }

    /**
     * Funció que retorna les sol·licituds d'amistat rebudes per l'usuari
     * @param int $user_id Id de l'usuari
     * @return User_friend
     */
    public static function getFriendRequests($user_id)
    {
        return User_friend::where('friend_id', $user_id)->where('accepted', 0)->get();
    }

    /**
     * Funció que retorna les sol·licituds d'amistat enviades per l'usuari
     * @param int $user_id Id de l'usuari
     * @return User_friend
     */
    public static function getFriendRequestsSent($user_id)
    {
        return User_friend::where('user_id', $user_id)->where('accepted', 0)->get();
    }

    /**
     * Funció que retorna les relacions d'amistat de l'usuari
     * @param int $user_id Id de l'usuari
     * @return User_friend
     */
    public static function getRelationships($user_id)
    {
        return User_friend::where(function ($query) use ($user_id) {
            $query->where('user_id', $user_id)
                ->orWhere('friend_id', $user_id);
        })->where('accepted', 1)->get();
    }

    /**
     * Funció que retorna les amistats de l'usuari
     * @param int $user_id Id de l'usuari
     */
    public static function getFriends($user_id)
    {
        $user_friends = User_friend::getRelationships($user_id);

        $friends = $user_friends->map(function ($user_friends) use ($user_id){
            if ($user_friends->user_id == $user_id) {
                return User::where('id', $user_friends->friend_id)->first();
            } else {
                return User::where('id', $user_friends->user_id)->first();
            }
        });

        return $friends;
    }

    /**
     * Funció que retorna si dos usuaris són amics
     * @param int $user_id Id de l'usuari
     * @param int $friend_id Id de l'amic
     * @return bool Si són amics
     */
    public static function areFriends($user_id, $friend_id)
    {
        return User_friend::where('user_id', $user_id)->where('friend_id', $friend_id)->where('accepted', 1)->orWhere(function (Builder $query) use ($user_id, $friend_id) {
            $query->where('user_id', $friend_id)->where('friend_id', $user_id)->where('accepted', 1);
        })->exists();
    }

    /**
     * Funció que retorna la relació d'amistat entre dos usuaris no acceptada
     * @param int $user_id Id de l'usuari
     * @param int $friend_id Id de l'amic
     * @return User_friend  Sol·licitud d'amistat
     */
    public static function getFriendRequest($user_id, $friend_id)
    {
        return User_friend::where('user_id', $user_id)->where('friend_id', $friend_id)->where('accepted', 0)->first();
    }


    /**
     * Funció que retorna si el usuari ha enviat una sol·licitud d'amistat a un altre usuari i aquesta encara no ha estat acceptada
     * @param int $user_id Id de l'usuari
     * @param int $friend_id Id de l'amic
     * @return bool
     */
    public static function hasSentFriendRequest($user_id, $friend_id)
    {
        return User_friend::where('user_id', $user_id)
            ->where('friend_id', $friend_id)
            ->where('accepted', 0)
            ->exists();
    }

    /**
     * Funció que retorna els usuaris que no són amics de l'usuari
     * @param int $user_id Id de l'usuari
     * @return User $not_friends Usuaris que no són amics de l'usuari
     */
    public static function getNotFriends($user_id)
    {
        $friends = User_friend::getFriends($user_id);
        $users = User::all();

        $not_friends = $users->filter(function ($user) use ($friends, $user_id) {
            if ($user->id != $user_id) {
                return !$friends->contains($user);
            }
        });

        return $not_friends;
    }

    

}
