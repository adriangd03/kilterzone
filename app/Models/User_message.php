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

    /**
     * Funció que retorna els missatges enviats per l'usuari
     * @param int $user_id Id de l'usuari
     * @return User_message
     */
    public static function getUserMessages($user_id)
    {
        return User_message::where('user_id', $user_id)->get();
    }

    /**
     * Funció que retorna els missatges rebuts per l'usuari no llegits
     * @param int $user_id Id de l'usuari
     * @return User_message missatges rebuts no llegits
     */
    public static function getUserMessagesReceivedUnread($user_id)
    {
        return User_message::where('receiver_id', $user_id)->where('read', 0)->get();
    }
    
    /**
     * Funció que retorna la conversació entre dos usuaris
     * @param int $user_id Id de l'usuari
     * @param int $receiver_id Id de l'usuari receptor
     * @return User_message 
     */
    public static function getConversation($user_id, $receiver_id)
    {
        return User_message::where('user_id', $user_id)->where('receiver_id', $receiver_id)->orWhere('user_id', $receiver_id)->where('receiver_id', $user_id)->get();
    }

    /**
     * Funció que marcar els missatges com a llegits que ha enviat un usuari a un altre
     * @param int $user_id Id de l'usuari que ha rebut el missatge
     * @param int $friend_id Id de l'usuari que ha enviat el missatge
     * @return void
     */
    public static function markAsRead($user_id, $friend_id)
    {
        User_message::where('user_id', $friend_id)->where('receiver_id', $user_id)->update(['read' => 1]);
    }

    public static function deleteMessagesBetweenUsers($user_id, $friend_id)
    {
        User_message::where('user_id', $user_id)->where('receiver_id', $friend_id)->delete();
        User_message::where('user_id', $friend_id)->where('receiver_id', $user_id)->delete();
    }
}
