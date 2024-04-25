<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use App\Models\User_friend;
use Illuminate\Validation\ValidationException;
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Facades\Password;
use App\Models\User_message;
use Illuminate\Database\Eloquent\Builder;
use App\Events\SendFriendRequest;
use App\Events\ChatMessage;
use App\Events\AcceptFriendRequest;



class UserController extends Controller
{

    /**
     * Funció per a mostrar la pàgina principal
     * @return \Illuminate\Contracts\View\View Retorna la vista de la pàgina principal
     */
    public function home()
    {
        try {
            // Comprovem si l'usuari està autenticat i si no ho està retornem la vista de la pàgina principal
            if (!Auth::check()) {
                return view('home');
            }
            // Agafem tots les relacions d'amistat acceptades on l'usuari autenticat estigui implicat
            $user_friends = User_friend::where(function ($query) {
                $query->where('user_id', Auth::user()->id)
                    ->orWhere('friend_id', Auth::user()->id);
            })->where('accepted', 1)->get();

            // Mapejem els usuaris amics
            $friends = $user_friends->map(function ($user_friends) {
                if ((int) $user_friends->user_id == (int) Auth::user()->id) {
                    return User::where('id', $user_friends->friend_id)->first();
                } else {
                    return User::where('id', $user_friends->user_id)->first();
                }
            });

            // Treiem els usuaris repetits
            $friends = $friends->unique();

            // Agafem les solicituds de amistat pendents
            $friendRequests = User_friend::where('friend_id', Auth::user()->id)->where('accepted', 0)->get();

            // Agafar el avatar i el nom d'usuari dels usuaris que han enviat una sol·licitud d'amistat
            $friendRequests = $friendRequests->map(function ($friendRequest) {
                $friendRequest->user = User::where('id', $friendRequest->user_id)->first();
                return $friendRequest;
            });

            // Agafem les solicituds de amistat enviades
            $sentFriendRequests = User_friend::where('user_id', Auth::user()->id)->where('accepted', 0)->get();

            // Agafem el nombre de missatges no llegits
            $unreadMessages = User_message::where('receiver_id', Auth::user()->id)->where('read', 0)->get();

            // Afegeix el nombre de missatges no llegits a cada usuari
            $friends = $friends->map(function ($friend) use ($unreadMessages) {
                $friend->unreadMessages = $unreadMessages->where('user_id', $friend->id)->count();
                return $friend;
            });

            // Agafem tots els usuaris que no siguin amics
            $notFriends = User::get()->diff($friends)->where('id', '!=', Auth::user()->id);

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

            // Agafem el total de sol·licituds d'amistat pendents
            $totalFriendRequests = $friendRequests->count();

            // Retornem la vista de la pàgina principal
            return view('home', compact('friends', 'totalUnreadMessages', 'notFriends', 'friendRequests', 'totalFriendRequests'));
        } catch (\Exception $e) {
            session()->flash('error', 'Hi ha ocurregut un problema en el procés de mostrar la pàgina principal, tornar a provar o prova-ho més tard' . $e);
            return view('home', ['friends' => []]);
        }
    }

    /**
     * Funció per a acceptar una sol·licitud d'amistat
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina principal o a la pàgina anterior
     * @throws \Exception Si hi ha algun error en el procés d'acceptar la sol·licitud d'amistat
     */
    function enviarSolicitudAmic(Request $request)
    {
        try {

            // Validem les dades de la petició
            $request->validate(
                [
                    'friend_id' => 'required|exists:users,id',
                ],
                [
                    'friend_id.required' => "No s'ha trobat l'amic a enviar la sol·licitud",
                    'friend_id.exists' => 'Aquest usuari no existeix',
                ]
            );

            // Comprovem si l'usuari ja ha enviat una sol·licitud d'amistat
            $friend = User_friend::where('user_id', Auth::user()->id)->where('friend_id', $request->friend_id)->first();

            // Comprovem si la relació ja existeix
            if ($friend) {
                // Si ja existeix la relació retornem un error
                return response()->json(['error' => 'Ja has enviat una sol·licitud d\'amistat a aquest usuari'], 403);
            }

            // Creem una nova relació d'amistat
            $friend = new User_friend();
            $friend->user_id = Auth::user()->id;
            $friend->friend_id = $request->friend_id;
            $friend->save();


            // Enviem l'esdeveniment de sol·licitud d'amistat
            event(new SendFriendRequest(Auth::user(), User::where('id', $request->friend_id)->first()));

            // Retornem un missatge de confirmació
            return response()->json(['message' => 'S\'ha enviat la sol·licitud d\'amistat']);


        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés d\'enviar la sol·licitud d\'amistat, tornar a provar o prova-ho més tard' ], 500);
        }
    }

    /**
     * Funció per a acceptar una sol·licitud d'amistat
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina principal o a la pàgina anterior
     * @throws \Exception Si hi ha algun error en el procés d'acceptar la sol·licitud d'amistat
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     */
    function acceptarSolicitudAmic(Request $request)
    {

        try {

            // Validem les dades de la petició
            $request->validate(
                [
                    'friend_id' => 'required|exists:users,id',
                ],
                [
                    'friend_id.required' => "No s'ha trobat l'amic a acceptar",
                    'friend_id.exists' => 'Aquest usuari no existeix',
                ]
            );

            // Busquem la relació d'amistat
            $friend = User_friend::where('user_id', $request->friend_id)->where('friend_id', Auth::user()->id)->first();

            // Comprovem si la relació existeix
            if ($friend) {
                // Acceptem la sol·licitud d'amistat
                $friend->accepted = 1;
                $friend->save();
            } else {
                // Si no existeix la relació retornem un error
                return response()->json(['error' => 'No s\'ha trobat la sol·licitud d\'amistat a acceptar'], 404);
            }
            // Comprovem si l'usuari han enviat anteriorment una sol·licitud d'amistat
            $friend = User_friend::where('user_id', Auth::user()->id)->where('friend_id', $request->friend_id)->first();

            // Comprovem si la relació existeix
            if ($friend) {
                // Si existeix la esborrem
                $friend->delete();
            }

            // Enviem l'esdeveniment d'acceptar sol·licitud d'amistat a l'usuari que ha enviat la sol·licitud
            event(new AcceptFriendRequest(User::where('id', $request->friend_id)->first(), Auth::user()));

            // Retornem un missatge de confirmació
            return response()->json(['message' => 'S\'ha acceptat la sol·licitud d\'amistat', 'user' => User::where('id', $request->friend_id)->first()->only('id', 'username', 'avatar')]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés d\'acceptar la sol·licitud d\'amistat, tornar a provar o prova-ho més tard' ], 500);
        }
    }

    /**
     * Funció per a rebutjar una sol·licitud d'amistat
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina principal o a la pàgina anterior
     * @throws \Exception Si hi ha algun error en el procés de rebutjar la sol·licitud d'amistat
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     */
    function rebutjarSolicitudAmic(Request $request)
    {
        try {
            // Validem les dades de la petició
            $request->validate(
                [
                    'friend_id' => 'required|exists:users,id',
                ],
                [
                    'friend_id.required' => "No s'ha trobat l'amic a rebutjar",
                    'friend_id.exists' => 'Aquest usuari no existeix',
                ]
            );

            // Busquem la relació d'amistat
            $friend = User_friend::where('user_id', $request->friend_id)->where('friend_id', Auth::user()->id)->where('accepted', 0)->first();

            // Comprovem si la relació existeix
            if ($friend) {
                // Si existeix la esborrem
                $friend->delete();
            } else {
                // Si no existeix la relació retornem un error
                return response()->json(['error' => 'No s\'ha trobat la sol·licitud d\'amistat a rebutjar'], 404);
            }

            // Retornem un missatge de confirmació
            return response()->json(['message' => 'S\'ha rebutjat la sol·licitud d\'amistat']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés de rebutjar la sol·licitud d\'amistat, tornar a provar o prova-ho més tard' ], 500);
        }
    }

    /**
     * Funció per a cancelar una sol·licitud d'amistat
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina principal o a la pàgina anterior
     * @throws \Exception Si hi ha algun error en el procés de cancelar la sol·licitud d'amistat
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     */
    function cancelarSolicitudAmic(Request $request)
    {
        try {
            // Validem les dades de la petició
            $request->validate(
                [
                    'friend_id' => 'required|exists:users,id',
                ],
                [
                    'friend_id.required' => "No s'ha trobat l'amic a cancelar",
                    'friend_id.exists' => 'Aquest usuari no existeix',
                ]
            );

            // Busquem la relació d'amistat
            $friend = User_friend::where('user_id', Auth::user()->id)->where('friend_id', $request->friend_id)->first();

            // Comprovem si la relació existeix
            if ($friend) {
                // Si existeix la esborrem
                $friend->delete();
            } else {
                // Si no existeix la relació retornem un error
                return redirect()->back()->with('error', 'No s\'ha trobat la sol·licitud d\'amistat a cancelar');
            }

            // Retornem un missatge de confirmació
            return redirect()->back()->with('success', 'S\'ha cancelat la sol·licitud d\'amistat');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'cancelarSolicitudAmic')->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés de cancelar la sol·licitud d\'amistat, tornar a provar o prova-ho més tard');
        }
    }

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
            return redirect()->back()->withErrors(['error' => "Hi ha ocurregut un problema en el procés d'inici de sessió, tornar a provar o prova-ho més tard" ], 'login')->withInput();
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
            return redirect()->route('home');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés d\'inici de sessió amb Google, tornar a provar o prova-ho més tard');
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
            return back()->withErrors(['error' => "Hi ha ocurregut un problema en el procés de registre, tornar a provar o prova-ho més tard",], 'registre');
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
    public function restaurarContrasenya(Request $request)
    {
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


    /**
     * Funció per a enviar un missatge a un usuari
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\JsonResponse Retorna un missatge en format JSON
     * @throws \Exception Si hi ha algun error en el procés d'enviar el missatge
     */
    public function sendMessageToClient(Request $request)
    {
        try {

            $request->validate(
                [
                    'message' => 'required|string|max:255',
                    'receiver' => 'required|exists:users,id',
                ],
                [
                    'message.required' => 'El camp missatge és obligatori',
                    'receiver.required' => 'El camp receptor és obligatori',
                    'receiver.exists' => 'Aquest receptor no existeix',
                    'message.string' => 'El camp missatge ha de ser una cadena de caràcters',
                    'message.max' => 'El camp missatge ha de tenir un màxim de 255 caràcters',
                ]
            );
            // Busquem l'usuari receptor
            $receiver = User::where('id', $request->receiver)->first();

            // Comprovem si l'usuari receptor és amic de l'usuari autenticat
            $friend = User_friend::where('user_id', Auth::user()->id)->where('friend_id', $receiver->id)->where('accepted', 1)->orWhere(function (Builder $query) use ($receiver) {
                $query->where('user_id', $receiver->id)->where('friend_id', Auth::user()->id)->where('accepted', 1);
            })->first();

            // Si no és amic retornem un error
            if (!$friend) {
                return response()->json(['error' => 'No pots enviar missatges a aquest usuari'], 403);
            }

            // Enviem el missatge
            event(new ChatMessage($request->message, auth::user(), $receiver));

            // Guardem el missatge a la base de dades
            $message = new User_message();
            $message->user_id = Auth::user()->id;
            $message->receiver_id = $receiver->id;
            $message->message = $request->message;
            $message->save();

            // Retornem un missatge de confirmació
            return response()->json(['message' => 'Missatge enviat correctament']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés d\'enviar el missatge, tornar a provar o prova-ho més tard' ], 500);
        }
    }

    /**
     * Funció per agafar els missatges d'un usuari amb un altre usuari
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\JsonResponse Retorna els missatges en format JSON
     * @throws \Exception Si hi ha algun error en el procés d'agafar els missatges
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     */
    public function getUserMessages(Request $request)
    {
        try {
            $request->validate(
                [
                    'userId' => 'required|exists:users,id',
                ],
                [
                    'userId.required' => 'El camp receptor és obligatori',
                    'userId.exists' => 'Aquest receptor no existeix',
                ]
            );

            // Busquem l'usuari receptor
            $receiver = User::where('id', $request->userId)->first();

            // Comprovem si l'usuari receptor és amic de l'usuari autenticat
            $friend = User_friend::where('user_id', Auth::user()->id)->where('friend_id', $receiver->id)->where('accepted', 1)->orWhere(function (Builder $query) use ($receiver) {
                $query->where('user_id', $receiver->id)->where('friend_id', Auth::user()->id)->where('accepted', 1);
            })->first();

            // Si no és amic retornem un error
            if (!$friend) {
                return response()->json(['error' => 'No pots veure els missatges d\'aquest usuari'], 403);
            }

            // Busquem els missatges de l'usuari amb l'usuari receptor
            $messages = User_message::where(function ($query) use ($receiver) {
                $query->where('user_id', Auth::user()->id)
                    ->where('receiver_id', $receiver->id);
            })->orWhere(function ($query) use ($receiver) {
                $query->where('user_id', $receiver->id)
                    ->where('receiver_id', Auth::user()->id);
            })->get();

            // Marquem els missatges com a llegits
            User_message::where('user_id', $receiver->id)->where('receiver_id', Auth::user()->id)->update(['read' => 1]);

            // Retornem els missatges
            return response()->json(['messages' => $messages]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés d\'agafar els missatges, tornar a provar o prova-ho més tard' ], 500);
        }
    }

    /**
     * Funció per a marcar els missatges de un usuari com a llegit
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\JsonResponse Retorna un missatge en format JSON
     * @throws \Exception Si hi ha algun error en el procés de marcar el missatge com a llegit
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     */
    public function marcarMissatgesComLLegits(Request $request)
    {
        try {
            $request->validate(
                [
                    'userId' => 'required|exists:users,id',
                ],
                [
                    'userId.required' => 'El camp receptor és obligatori',
                    'userId.exists' => 'Aquest receptor no existeix',
                ]
            );

            // Busquem l'usuari receptor
            $receiver = User::where('id', $request->userId)->first();

            // Comprovem si l'usuari receptor és amic de l'usuari autenticat
            $friend = User_friend::where('user_id', Auth::user()->id)->where('friend_id', $receiver->id)->where('accepted', 1)->orWhere(function (Builder $query) use ($receiver) {
                $query->where('user_id', $receiver->id)->where('friend_id', Auth::user()->id)->where('accepted', 1);
            })->first();

            // Si no és amic retornem un error
            if (!$friend) {
                return response()->json(['error' => 'No pots marcar els missatges com a llegits d\'aquest usuari'], 403);
            }

            // Marquem els missatges com a llegits
            User_message::where('user_id', $receiver->id)->where('receiver_id', Auth::user()->id)->update(['read' => 1]);

            // Retornem un missatge de confirmació
            return response()->json(['message' => 'Missatges marcats com a llegits']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés de marcar els missatges com a llegits, tornar a provar o prova-ho més tard' ], 500);
        }
    }
}
