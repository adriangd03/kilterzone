<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;

class UserController extends Controller
{
    public function login(Request $request)
    {
        try {
            $credentials = $request->only('email', 'password');
            if (Auth::attempt($credentials)) {
                $request->session()->regenerate();
                return redirect()->intended('/');
            }
            if (User::where('email', $credentials['email'])->exists()) {
                return back()->withErrors(['password' => 'La contrasenya és incorrecta',], 'login');
            }
            return back()->withErrors(['email' => 'Aquest correu electrònic no està registrat',], 'login');
        } catch (\Exception $e) {
            return back()->withErrors(['error' => "Hi ha ocurregut un problema en el procés d'inici de sessió, tornar a provar o prova-ho més tard",], 'login');
        }
    }

    public function registre(Request $request)
    {
        try {
            $request->validate(
                [
                    'username' => 'required|unique:users|string|max:50',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|min:6|max:25|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed',
                ],
                [
                    'username.required' => 'El camp nom d\'usuari és obligatori',
                    'username.string' => 'El camp nom d\'usuari ha de ser una cadena de caràcters',
                    'username.max' => 'El camp nom d\'usuari ha de tenir un màxim de 50 caràcters',
                    'username.unique' => 'Aquest nom d\'usuari ja està registrat',
                    'email.required' => 'El camp correu electrònic és obligatori',
                    'email.email' => 'El camp correu electrònic ha de ser una adreça de correu vàlida',
                    'email.max' => 'El camp correu electrònic ha de tenir un màxim de 255 caràcters',
                    'email.unique' => 'Aquest correu electrònic ja està registrat',
                    'password.required' => 'El camp contrasenya és obligatori',
                    'password.string' => 'El camp contrasenya ha de ser una cadena de caràcters',
                    'password.min' => 'El camp contrasenya ha de tenir un mínim de 8 caràcters',
                ]
            );
            User::create([
                'username' => $request->username,
                'email' => $request->email,
                'password' => $request->password,
            ]);
            return redirect()->route('login');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'registre')->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => "Hi ha ocurregut un problema en el procés de registre, tornar a provar o prova-ho més tard" . $e,], 'registre');
        }
    }
}
