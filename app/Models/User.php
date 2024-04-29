<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Contracts\Auth\CanResetPassword;
use App\Notifications\ResetPasswordNotification;

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
    

    
}
