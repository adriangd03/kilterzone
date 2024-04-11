<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
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

    
}
