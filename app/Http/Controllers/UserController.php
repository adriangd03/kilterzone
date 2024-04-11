<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;


class UserController extends Controller
{
    /**
     * Funció per a iniciar sessió
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina principal o a la pàgina anterior
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     * @throws \Exception Si hi ha algun error en el procés d'inici de sessió
     */
    public function login(Request $request)
    {
        try {
            $request->validate(
                [
                    'email_username' => 'required',
                    'password' => 'required',
                ],
                [
                    'email_username.required' => 'El camp adreça de correu o nom d\'usuari és obligatori',
                    'password.required' => 'El camp contrasenya és obligatori',
                ]
            );

            // Agafem les dades de la petició
            $credentials = $request->only('email_username', 'password');

            // Comprovem si l'usuari ha introduït un correu electrònic o un nom d'usuari
            if (filter_var($credentials['email_username'], FILTER_VALIDATE_EMAIL)) {
                $credentials['email'] = $credentials['email_username'];
                unset($credentials['email_username']);
            } else {
                $credentials['username'] = $credentials['email_username'];
                unset($credentials['email_username']);
            }

            // Comprovem si les credencials són correctes
            if (Auth::attempt($credentials)) {
                // Si són correctes iniciem sessió
                $request->session()->regenerate();
                return redirect()->intended('/');
            }

            // Comprovem si el nom de usuari o correu electrònic existeixen
            if (isset($credentials['email'])) {
                // Comprovem si l'usuari existeix
                if (User::where('email', $credentials['email'])->doesntExist()) {
                    // Si no existeix retornem un error
                    return redirect()->back()->withErrors(['email_username' => 'Aquest correu electrònic no està registrat',], 'login')->withInput();
                }
            } else {
                // Comprovem si l'usuari existeix
                if (User::where('username', $credentials['username'])->doesntExist()) {
                    // Si no existeix retornem un error
                    return redirect()->back()->withErrors(['email_username' => 'Aquest nom d\'usuari no està registrat',], 'login')->withInput();
                }
            }
            // Si les credencials són incorrectes retornem un error
            return redirect()->back()->withErrors(['password' => 'Constrasenya incorrecta',], 'login')->withInput();
        } catch (ValidationException $e) {
            // Si hi ha algun error en la validació de les dades de la petició retornem els errors
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'login')->withInput();
        } catch (\Exception $e) {
            // Si hi ha algun error en el procés d'inici de sessió retornem un error
            return redirect()->back()->withErrors(['error' => "Hi ha ocurregut un problema en el procés d'inici de sessió, tornar a provar o prova-ho més tard" . $e], 'login')->withInput();
        }
    }

    /**
     * Funció per redirigir a la pàgina de login de Google
     */
    public function loginGoogle()
    {
        return Socialite::driver('google')->redirect();
    }

    /**
     * Funció per a iniciar sessió amb Google
     */
    public function googleCallback()
    {
        try {
            $user = Socialite::driver('google')->user();
            $user = User::firstOrCreate(
                ['email' => $user->email],
                [
                    'username' => $user->nickname ? User::createUsername($user->nickname) : User::createUsername($user->name),
                    'email' => $user->email,
                    'avatar' => $user->avatar,
                ]
            );

            Auth::login($user);
            return redirect()->route('home')->with('success', 'S\'ha iniciat sessió correctament');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés d\'inici de sessió amb Google, tornar a provar o prova-ho més tard' . $e);
        }
    }



    /**
     * Funció per a registrar-se
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina de login o a la pàgina anterior
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     * @throws \Exception Si hi ha algun error en el procés de registre
     */
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
            return redirect()->route('login')->with('success', 'S\'ha registrat correctament, ara pot iniciar sessió');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'registre')->withInput();
        } catch (\Exception $e) {
            return back()->withErrors(['error' => "Hi ha ocurregut un problema en el procés de registre, tornar a provar o prova-ho més tard" . $e,], 'registre');
        }
    }

    /**
     * Funció per a tancar sessió
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina principal
     * @throws \Exception Si hi ha algun error en el procés de tancar sessió
     */
    public function logout(Request $request)
    {
        try {

            Auth::logout();
            $request->session()->invalidate();
            $request->session()->regenerateToken();
            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés de tancar sessió, tornar a provar o prova-ho més tard');
        }
    }
}
