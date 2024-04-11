<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Password;


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
                    // Si s'han fet més de 3 intents de login, es requerirà el token de recaptcha
                    'g-token' => session('loginIntents') >= 3 ? 'required' : '',
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

            // Si falla la autorització, sumem un intent de login
            if (!session('loginIntents')) session(['loginIntents' => 1]);
            session(['loginIntents' => session('loginIntents') + 1]);

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
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés d\'inici de sessió amb Google, tornar a provar o prova-ho més tard' );
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
                    'password.string' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.min' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.max' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.regex' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
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
            return back()->withErrors(['error' => "Hi ha ocurregut un problema en el procés de registre, tornar a provar o prova-ho més tard" ,], 'registre');
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

    /**
     * Funció per a recuperar la contrasenya
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina de login o a la pàgina anterior
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     * @throws \Exception Si hi ha algun error en el procés de recuperar la contrasenya
     */
    public function recuperar(Request $request)
    {
        try {
            $request->validate(
                [
                    'email' => 'required|email|max:255|exists:users,email',
                ],
                [
                    'email.required' => 'El camp correu electrònic és obligatori',
                    'email.email' => 'El camp correu electrònic ha de ser una adreça de correu vàlida',
                    'email.max' => 'El camp correu electrònic ha de tenir un màxim de 255 caràcters',
                    'email.exists' => 'Aquest correu electrònic no està registrat',
                ]
            );
            $user = User::where('email', $request->email)->first();
            
            if (!$user->password) {
                return redirect()->back()->with('error', 'Aquest usuari no té permisos per a restablir la contrasenya');
            }

            $token = Password::createToken($user);

            $user->sendPasswordResetNotification($token);

            return redirect()->route('login')->with('success', 'S\'ha enviat un correu electrònic per a restablir la contrasenya');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'recuperar')->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés de recuperar la contrasenya, tornar a provar o prova-ho més tard');
        }
    }


        /**
     * Mostra el formulari per restaurar la contrasenya
     * @param Request $request dades de l'usuari
     * @return redirecció a la pàgina de restaurar contrassenya si no hi ha cap error, altrament redirigir a la pàgina de login amb error
     * @throws \Exception si no es pot mostrar el formulari de restaurar contrasenya
     */
    public function restaurarForm(Request $request)
    {
        try {
            // Mostrar el formulari per restaurar la contrasenya i passar el token
            return view('restaurar', ['token' => $request->token]);
        } catch (\Exception $e) {
            //Retornem la resposta error si ha ocorregut algun error
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés de restaurar la contrasenya, tornar a provar o prova-ho més tard');
        }
    }

    /**
     * Funció per a restablir la contrasenya
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina de login o a la pàgina anterior
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     * @throws \Exception Si hi ha algun error en el procés de restablir la contrasenya
     */
    public function restaurarContrasenya(Request $request){
        try {
            $request->validate(
                [
                    'token' => 'required',
                    'password' => 'required|min:6|max:25|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed',
                ],
                [
                    'token.required' => 'El token de restabliment de contrasenya és obligatori',
                    'password.required' => 'El camp contrasenya és obligatori',
                    'password.string' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.min' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.max' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.regex' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.confirmed' => 'Les contrasenyes no coincideixen',
                ]
            );
            // Crear un array amb les dades del usuari
            $credentials = $request->only(
                'email',
                'password',
                'password_confirmation',
                'token'
            );

            // Restaurar la contrasenya de l'usuari
            $status = Password::reset($credentials, function ($user, $password) {
                $user->password = $password;
                $user->save();
            });

            $user = User::where('email', $request->email)->first();

            // Comprovem si s'ha restaurat la contrasenya correctament
            if ($status == Password::PASSWORD_RESET) {
                // Si ha anat bé, redirigim l'usuari a la pàgina de login
                return redirect()->route('login')->with('success', 'S\'ha restablert la contrasenya correctament')->withInput(['email_username' => $user->email]);
            } else {

                // Comprovem si l'error és per un email incorrecte
                if ($status == Password::INVALID_USER) {
                    return back()->withInput()->withErrors(['error' => ['Aquest email no és correcte']], 'restaurar');
                }

                // Comprovem si l'error és per un token incorrecte
                if ($status == Password::INVALID_TOKEN) {
                    return back()->withInput()->withErrors(['error' => ['El token no és vàlid, reinicia el procés de recuperació de contrasenya']], 'restaurar');
                }

            }

            return back()->withInput()->withErrors(['error' => ['Hi ha hagut un error al restablir la contrasenya']], 'restaurar');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'restaurarContrasenya')->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés de restablir la contrasenya, tornar a provar o prova-ho més tard');
        }

    }
}
