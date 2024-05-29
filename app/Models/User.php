<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPasswordNotification;
use App\Models\User_friend;
use App\Models\User_message;

class User extends Authenticatable implements CanResetPassword
{
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'avatar',
        'username'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    /**
     * Crear un nom d'usuari unic a partir de un nom
     * @param string $name Nom de l'usuari
     * @return string Nom d'usuari únic
     */
    public static function createUsername($name)
    {
        $username = strtolower(explode(' ', $name)[0]);
        $username = str_replace(' ', '', $username);

        $i = 1;
        while (User::where('username', $username)->exists()) {
            $username = $username . $i;
            $i++;
        }
            return $username;
    }

    /**
     * Envia una notificació de restabliment de contrasenya
     * @param string $token Token de restabliment de contrasenya
     * @return void
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new ResetPasswordNotification($token));
    }
    
    /**
     * Funció per obtenir un usuari a partir de la seva id
     * @param int $id Id de l'usuari
     * @return User Usuari amb la id especificada
     */
    public static function getUserById($id)
    {
        return User::where('id', $id)->first();
    }

    /**
     * Funció per obtenir tots els usuaris
     * @return array<User> Usuaris
     */
    public static function getAll()
    {
        return User::all();
    }

    /**
     * Funció per obtenir un usuari a partir del seu nom d'usuari
     * @param string $username Nom d'usuari
     * @return User Usuari amb el nom d'usuari especificat
     */
    public static function getUserByUsername($username)
    {
        return User::where('username', $username)->first();
    }

    /**
     * Funció per obtenir un usuari a partir del seu correu electrònic
     * @param string $email Correu electrònic
     * @return User Usuari amb el correu electrònic especificat
     */
    public static function getUserByEmail($email)
    {
        return User::where('email', $email)->first();
    }
    

    /**
     * Funció per agafar les dades necessàries per les funcions del chat i amistats
     * @return array<string, mixed> Dades de l'usuari
     */
    public static function getChatData(){

        $user = User::getUserById(auth()->user()->id);
        // Agafem el amics de l'usuari
        $friends = User_friend::getFriends($user->id);
        // Agafem les sol·licituds d'amistat de l'usuari
        $friendRequests = User_friend::getFriendRequests($user->id);
        // Afegim la informació de l'usuari a les sol·licituds d'amistat
        $friendRequests = $friendRequests->map(function ($friendRequest) {
            $friendRequest->user = User::getUserById($friendRequest->user_id);
            return $friendRequest;
        });
        // Agafem les sol·licituds d'amistat enviades per l'usuari
        $sentFriendRequests = User_friend::getFriendRequestsSent($user->id);
        // Agafem els missatges no llegits de l'usuari
        $unreadMessages = User_message::getUserMessagesReceivedUnread($user->id);

         // Afegeix el nombre de missatges no llegits a cada usuari
         $friends = $friends->map(function ($friend) use ($unreadMessages) {
            $friend->unreadMessages = $unreadMessages->where('user_id', $friend->id)->count();
            return $friend;
        });

        // Agafem els usuaris no amics de l'usuari
        $notFriends = User_friend::getNotFriends($user->id);

        // Afegim als usuaris no amics les sol·licituds d'amistat enviades
        $notFriends = $notFriends->map(function ($notFriend) use ($sentFriendRequests) {
            $sentFriendRequest = $sentFriendRequests->where('friend_id', $notFriend->id)->first();
            if ($sentFriendRequest) {
                $notFriend->sentFriendRequest = true;
            }
            return $notFriend;
        });

        // Agafem el total de missatges no llegits
        $totalUnreadMessages = $unreadMessages->count();

        // Agafem el total de sol·licituds d'amistats pendents
        $totalFriendRequests = $friendRequests->count();

        return [
            'friends' => $friends,
            'friendRequests' => $friendRequests,
            'notFriends' => $notFriends,
            'totalUnreadMessages' => $totalUnreadMessages,
            'totalFriendRequests' => $totalFriendRequests
        ];
    }

    /**
     * Funció per afegir link si es menciona a un usuari amb @
     * @param string $text Text amb mencions
     * @return string Text amb links
     */
    public static function addLinks($text, $startingPos = 0)
    {
        $pos = strpos($text, '@', $startingPos);
        while ($pos !== false) {
            $pos2 = strpos($text, ' ', $pos);
            if ($pos2 === false) {
                $pos2 = strlen($text);
            }
            $username = substr($text, $pos + 1, $pos2 - $pos - 1);
            $user = User::getUserByUsername($username);
            if ($user) {
                $text = substr($text, 0, $pos) . '<a href="/profile/' . $user->id . '"> @' . $username . '</a>' . substr($text, $pos2);
            }
            $pos = strpos($text, '@', $pos2);
        }
        return $text;
    }

    
}
