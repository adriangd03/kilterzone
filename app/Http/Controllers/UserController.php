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
use App\Events\RemoveFriend;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;





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
                $notFriends = User::getAll();
                return view('home', compact('notFriends'));
            }


            $chatData = User::getChatData();
            $friends = $chatData['friends'];
            $friendRequests = $chatData['friendRequests'];
            $notFriends = $chatData['notFriends'];
            $totalUnreadMessages = $chatData['totalUnreadMessages'];
            $totalFriendRequests = $chatData['totalFriendRequests'];


            // Retornem la vista de la pàgina principal
            return view('home', compact('friends', 'totalUnreadMessages', 'notFriends', 'friendRequests', 'totalFriendRequests'));
        } catch (\Exception $e) {
            // Si hi ha algun error en el procés de mostrar la pàgina principal retornem un error
            session()->flash('error', 'Hi ha ocurregut un problema en el procés de mostrar la pàgina principal, tornar a provar o prova-ho més tard' . $e);
            return view('home', compact('friends',  'notFriends', 'friendRequests', 'totalFriendRequests', 'totalUnreadMessages',));
        }
    }



    /**
     * Funció per a mostrar el perfil d'un usuari
     * @param int $id Id de l'usuari
     * @return \Illuminate\Contracts\View\View Retorna la vista del perfil de l'usuari
     */
    public function perfil($id)
    {
        try {

            if (!Auth::check()) {
                $notFriends = User::getAll();
                $user = User::getUserById($id);
                $user->friends = User_friend::getFriends($user->id)->count();
                return view('perfil', compact('notFriends', 'user'));
            }

            // Agafem l'usuari per id
            $user = User::getUserById($id);

            // Comprovem si l'usuari existeix
            if (!$user) {
                // Si l'usuari no existeix retornem un error
                session()->flash('error', 'Aquest usuari no existeix');
                return redirect()->route('home');
            }

            if ($user->id == Auth::user()->id) {
                return redirect()->route('home');
            }

            $chatData = User::getChatData();

            $friends = $chatData['friends'];
            $friendRequests = $chatData['friendRequests'];
            $notFriends = $chatData['notFriends'];
            $totalUnreadMessages = $chatData['totalUnreadMessages'];
            $totalFriendRequests = $chatData['totalFriendRequests'];

            // Comprove si l'usuari ja és amic
            $isFriend = User_friend::areFriends(Auth::user()->id, $id);

            // Agafar el nombre d'amics de l'usuari
            $user->friends = User_friend::getFriends($user->id)->count();

            // Comprovem si el usuari ja li ha enviat una sol·licitud d'amistat
            $user->sentFriendRequest = User_friend::hasSentFriendRequest(Auth::user()->id, $id);

            // Retornem la vista del perfil de l'usuari
            return view('perfil', compact('user', 'friends', 'totalUnreadMessages', 'notFriends', 'friendRequests', 'totalFriendRequests', 'isFriend'));
        } catch (\Exception $e) {
            // Si hi ha algun error en el procés de mostrar el perfil de l'usuari retornem un error
            session()->flash('error', 'Hi ha ocurregut un problema en el procés de mostrar el perfil de l\'usuari, tornar a provar o prova-ho més tard');
            return redirect()->route('home');
        }
    }

    /**
     * Funció per a mostrar el perfil propi
     * @return \Illuminate\Contracts\View\View Retorna la vista del perfil propi
     */
    public function perfilPropi()
    {
        try {
            // Agafem l'usuari autenticat
            $user = Auth::user();

            $chatData = User::getChatData();

            $friends = $chatData['friends'];
            $friendRequests = $chatData['friendRequests'];
            $notFriends = $chatData['notFriends'];
            $totalUnreadMessages = $chatData['totalUnreadMessages'];
            $totalFriendRequests = $chatData['totalFriendRequests'];

            // Agafar el nombre d'amics de l'usuari
            $user->friends = User_friend::getFriends($user->id)->count();

            // Retornem la vista del perfil propi
            return view('perfil_user', compact('user', 'friends', 'totalUnreadMessages', 'notFriends', 'friendRequests', 'totalFriendRequests'));
        } catch (\Exception $e) {
            // Si hi ha algun error en el procés de mostrar el perfil propi retornem un error
            session()->flash('error', 'Hi ha ocurregut un problema en el procés de mostrar el perfil propi, tornar a provar o prova-ho més tard');
            return redirect()->route('home');
        }
    }


    /**
     * Funció per a mostrar la configuració de l'usuari
     * @return \Illuminate\Contracts\View\View Retorna la vista de la configuració de l'usuari
     */
    public function configuracio()
    {
        try {
            // Agafem l'usuari autenticat
            $user = Auth::user();

            $chatData = User::getChatData();

            $friends = $chatData['friends'];
            $friendRequests = $chatData['friendRequests'];
            $notFriends = $chatData['notFriends'];
            $totalUnreadMessages = $chatData['totalUnreadMessages'];
            $totalFriendRequests = $chatData['totalFriendRequests'];

            // Agafar el nombre d'amics de l'usuari
            $user->friends = User_friend::getFriends($user->id)->count();

            // Retornem la vista de la configuració de l'usuari
            return view('configuracio', compact('user', 'friends', 'totalUnreadMessages', 'notFriends', 'friendRequests', 'totalFriendRequests'));
        } catch (\Exception $e) {
            // Si hi ha algun error en el procés de mostrar la configuració de l'usuari retornem un error
            session()->flash('error', 'Hi ha ocurregut un problema en el procés de mostrar la configuració de l\'usuari, tornar a provar o prova-ho més tard');
            return redirect()->route('home');
        }
    }



    /**
     * Funció per enviar una sol·licitud d'amistat
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina principal o a la pàgina anterior
     * @throws \Exception Si hi ha algun error en el procés d'enviar la sol·licitud d'amistat
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
            if (User_friend::hasSentFriendRequest(Auth::user()->id, $request->friend_id)) {
                // Si ja existeix la relació retornem un error
                return response()->json(['error' => 'Ja has enviat una sol·licitud d\'amistat a aquest usuari'], 403);
            }
            // Comprovem si els dos usuaris ja són amics
            if (User_friend::areFriends(Auth::user()->id, $request->friend_id)) {
                // Si ja existeix la relació retornem un error
                return response()->json(['error' => 'Ja sou amics'], 403);
            }

            // Creem una nova relació d'amistat
            $friend = new User_friend();
            $friend->user_id = Auth::user()->id;
            $friend->friend_id = $request->friend_id;
            $friend->save();


            // Enviem l'esdeveniment de sol·licitud d'amistat
            event(new SendFriendRequest(Auth::user(), User::getUserById($request->friend_id)));

            // Retornem un missatge de confirmació
            return response()->json(['message' => 'S\'ha enviat la sol·licitud d\'amistat']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés d\'enviar la sol·licitud d\'amistat, tornar a provar o prova-ho més tard'], 500);
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
            $friend = User_friend::getFriendRequest($request->friend_id, Auth::user()->id);

            // Comprovem si la relació existeix
            if ($friend) {
                // Comprovem si els usuaris ja són amics
                $friendship = User_friend::areFriends(Auth::user()->id, $request->friend_id);
                if ($friendship) {
                    // Si ja existeix la relació retornem un error
                    return response()->json(['error' => 'Ja sou amics'], 403);
                }
                // Acceptem la sol·licitud d'amistat
                $friend->accepted = 1;
                $friend->save();
            } else {
                // Si no existeix la relació retornem un error
                return response()->json(['error' => 'No s\'ha trobat la sol·licitud d\'amistat a acceptar'], 403);
            }
            // Comprovem si l'usuari han enviat anteriorment una sol·licitud d'amistat
            $friend = User_friend::getFriendRequest(Auth::user()->id, $request->friend_id);

            // Comprovem si la relació existeix
            if ($friend) {
                // Si existeix la esborrem
                $friend->delete();
            }

            // Enviem l'esdeveniment d'acceptar sol·licitud d'amistat a l'usuari que ha enviat la sol·licitud
            event(new AcceptFriendRequest(User::getUserById($request->friend_id), Auth::user()));

            // Retornem un missatge de confirmació
            return response()->json(['message' => 'S\'ha acceptat la sol·licitud d\'amistat', 'user' => User::where('id', $request->friend_id)->first()->only('id', 'username', 'avatar')]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés d\'acceptar la sol·licitud d\'amistat, tornar a provar o prova-ho més tard'], 500);
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
            $friend = User_friend::getFriendRequest($request->friend_id, Auth::user()->id);

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
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés de rebutjar la sol·licitud d\'amistat, tornar a provar o prova-ho més tard'], 500);
        }
    }

    function eliminarAmic(Request $request)
    {
        try {
            // Validem les dades de la petició
            $request->validate(
                [
                    'friend_id' => 'required|exists:users,id',
                ],
                [
                    'friend_id.required' => "No s'ha trobat l'amic a eliminar",
                    'friend_id.exists' => 'Aquest usuari no existeix',
                ]
            );

            // Busquem la relació d'amistat
            $friends = User_friend::areFriends(Auth::user()->id, $request->friend_id);

            // Comprovem si la relació existeix
            if ($friends) {
                User_friend::deleteFriendship(Auth::user()->id, $request->friend_id);
            } else {
                // Si no existeix la relació retornem un error
                return response()->json(['error' => 'No s\'ha trobat la relació d\'amistat a eliminar'], 404);
            }

            // Enviem l'esdeveniment d'eliminar amic
            event(new RemoveFriend(Auth::user(), User::getUserById($request->friend_id)));

            // Retornem un missatge de confirmació
            return response()->json(['message' => 'S\'ha eliminat la relació d\'amistat', 'friend' => User::getUserById($request->friend_id)->only('id', 'username', 'avatar')]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés d\'eliminar la relació d\'amistat, tornar a provar o prova-ho més tard'], 500);
        }
    }


    /**
     * Funció per mostrar la vista de login
     * @return \Illuminate\Contracts\View\View Retorna la vista de login
     */
    public function loginView()
    {
        $notFriends = User::getAll();
        return view('login', compact('notFriends'));
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
                if (User::getUserByEmail($credentials['email']) === null) {
                    // Si no existeix retornem un error
                    return redirect()->back()->withErrors(['email_username' => 'Aquest correu electrònic no està registrat',], 'login')->withInput();
                }
                // Comprovem si l'usuari existeix per nom d'usuari
            } else if (User::getUserByUsername($credentials['username']) === null) {
                // Si no existeix retornem un error
                return redirect()->back()->withErrors(['email_username' => 'Aquest nom d\'usuari no està registrat',], 'login')->withInput();
            }
            // Si les credencials són incorrectes retornem un error
            return redirect()->back()->withErrors(['password' => 'Constrasenya incorrecta',], 'login')->withInput();
        } catch (ValidationException $e) {
            // Si hi ha algun error en la validació de les dades de la petició retornem els errors
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'login')->withInput();
        } catch (\Exception $e) {
            // Si hi ha algun error en el procés d'inici de sessió retornem un error
            return redirect()->back()->withErrors(['error' => "Hi ha ocurregut un problema en el procés d'inici de sessió, tornar a provar o prova-ho més tard"], 'login')->withInput();
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
     * Funció per retornar la vista del registre
     * @return \Illuminate\Contracts\View\View Retorna la vista del registre
     */
    public function registreView()
    {
        $notFriends = User::getAll();
        return view('registre', compact('notFriends'));
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
                    'username' => 'required|unique:users|string|max:15',
                    'email' => 'required|email|max:255|unique:users',
                    'password' => 'required|min:6|max:25|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed',
                ],
                [
                    'username.required' => 'El camp nom d\'usuari és obligatori',
                    'username.string' => 'El camp nom d\'usuari ha de ser una cadena de caràcters',
                    'username.max' => 'El camp nom d\'usuari ha de tenir un màxim de 15 caràcters',
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
            $user = User::getUserByEmail($request->email)->first();

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

            $user = User::getUserByEmail($request->email)->first();

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
            $receiver = User::getUserById($request->receiver);

            // Si no és amic retornem un error
            if (User_friend::areFriends(Auth::user()->id, $receiver->id) == false) {
                return response()->json(['error' => 'No pots enviar missatges a aquest usuari, perquè no sou amics'], 403);
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
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés d\'enviar el missatge, tornar a provar o prova-ho més tard'], 500);
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
                    'friendId' => 'required|exists:users,id',
                ],
                [
                    'userId.required' => 'El camp receptor és obligatori',
                    'userId.exists' => 'Aquest receptor no existeix',
                ]
            );

            // Busquem l'usuari amic
            $friend = User::getUserById($request->friendId);

            // Comprovem que el amic no sigui l'usuari autenticat
            if ($friend->id == Auth::user()->id) {
                return response()->json(['error' => 'No pots veure una conversació amb tu mateix'], 403);
            }

            // Si no és amic retornem un error
            if (!User_friend::areFriends(Auth::user()->id, $friend->id)) {
                return response()->json(['error' => 'No pots veure la conversació amb aquest usuari perquè no sou amics'], 403);
            }
            // Busquem els missatges de l'usuari amb l'usuari receptor
            $messages = User_message::getConversation(Auth::user()->id, $friend->id);

            // Marquem els missatges com a llegits
            User_message::markAsRead(Auth::user()->id, $friend->id);

            // Retornem els missatges
            return response()->json(['messages' => $messages]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés d\'agafar els missatges, tornar a provar o prova-ho més tard'], 500);
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
                    'friendId' => 'required|exists:users,id',
                ],
                [
                    'friendId.required' => 'El camp receptor és obligatori',
                    'friendId.exists' => 'Aquest receptor no existeix',
                ]
            );

            // Busquem l'usuari receptor
            $friend = User::getUserById($request->friendId);

            // Comprovem si els usuari són amics
            if (User_friend::areFriends(Auth::user()->id, $friend->id) == false) {
                // Si no són amics retornem un error
                return response()->json(['error' => 'No pots marcar els missatges com a llegits d\'aquest usuari'], 403);
            }

            // Marquem els missatges com a llegits
            User_message::markAsRead(Auth::user()->id, $friend->id);

            // Retornem un missatge de confirmació
            return response()->json(['message' => 'Missatges marcats com a llegits']);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->validator->getMessageBag()], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Hi ha ocurregut un problema en el procés de marcar els missatges com a llegits, tornar a provar o prova-ho més tard'], 500);
        }
    }


    /**
     * Funció per actualitzar la imatge de perfil de l'usuari
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\JsonResponse Retorna un missatge en format JSON
     * @throws \Exception Si hi ha algun error en el procés d'actualitzar la imatge de perfil
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     */
    public function actualitzarImatgePerfil(Request $request)
    {
        try {
            $request->validate(
                [
                    'avatar' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                ],
                [
                    'avatar.required' => 'La imatge és obligatoria',
                    'avatar.image' => 'Has de seleccionar una imatge vàlida',
                    'avatar.mimes' => "L'imatge ha de ser una imatge de tipus: jpeg, png, jpg, gif",
                    'avatar.max' => "L'imatge ha de ser d'un màxim de 2MB",
                ]
            );

            $user = User::getUserById(Auth::user()->id);

            // Si l'usuari ja té una imatge de perfil i no es la default, l'esborrem
            if (Str::contains($user->avatar, ['default', 'https://lh3.googleusercontent.com/a/']) == false) {
                $nomImatge = explode('/', $user->avatar);
                unlink(storage_path('app/public/avatars/' . end($nomImatge)));
            }

            // Guardem la imatge a la base de dades
            $user->avatar = env('APP_URL') . '/' . 'storage/' . $request->file('avatar')->store('avatars', 'public');
            $user->save();

            // Retornem un missatge de confirmació
            return redirect()->back()->with('success', 'Imatge de perfil actualitzada correctament');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'actualitzarImatgePerfil');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés d\'actualitzar la imatge de perfil, tornar a provar o prova-ho més tard');
        }
    }


    /**
     * Funció per esborrar la imatge de perfil actual de l'usuari i posar la imatge per defecte
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina anterior
     * @throws \Exception Si hi ha algun error en el procés d'esborrar la imatge de perfil
     */
    public function eliminarImatgePerfil()
    {
        try {
            $user = User::getUserById(Auth::user()->id);

            // Si l'usuari ja té una imatge de perfil i no es la default, l'esborrem
            if (Str::contains($user->avatar, ['default', 'https://lh3.googleusercontent.com/a/']) == false) {
                $nomImatge = explode('/', $user->avatar);
                unlink(storage_path('app/public/avatars/' . end($nomImatge)));
            }

            // Guardem la imatge per defecte a la base de dades
            $user->avatar = env('APP_URL') . '/storage/avatars/default.png';
            $user->save();

            // Retornem un missatge de confirmació
            return redirect()->back()->with('success', 'Imatge de perfil eliminada correctament');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés d\'eliminar la imatge de perfil, tornar a provar o prova-ho més tard');
        }
    }

    /**
     * Funció per a actualitzar les dades de l'usuari
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina anterior
     * @throws \Exception Si hi ha algun error en el procés d'actualitzar les dades de l'usuari
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     */
    public function actualitzarDades(Request $request)
    {
        try {
            $request->validate(
                [
                    'username' => 'required|string|max:15|unique:users,username,' . Auth::user()->id,
                    'description' => 'required|string|max:255|',
                ],
                [
                    'username.required' => 'El camp nom d\'usuari és obligatori',
                    'username.string' => 'El camp nom d\'usuari ha de ser una cadena de caràcters',
                    'username.max' => 'El camp nom d\'usuari ha de tenir un màxim de 15 caràcters',
                    'username.unique' => 'Aquest nom d\'usuari ja està registrat',
                    'description.required' => 'El camp descripció és obligatori',
                    'description.string' => 'El camp descripció ha de ser una cadena de caràcters',
                    'description.max' => 'El camp descripció ha de tenir un màxim de 255 caràcters',
                ]
            );

            // Actualitzem les dades de l'usuari
            $user = User::getUserById(Auth::user()->id);
            $user->username = $request->username;
            $user->description = $request->description;
            $user->save();

            // Retornem un missatge de confirmació
            return redirect()->back()->with('success', 'Dades actualitzades correctament');

        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'actualitzarDades')->withInput();
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés d\'actualitzar les dades, tornar a provar o prova-ho més tard');}
    }


    /**
     * Funció per a actualitzar la contrasenya de l'usuari
     * @param Request $request Dades de la petició
     * @return \Illuminate\Http\RedirectResponse Redirecciona a la pàgina anterior
     * @throws \Exception Si hi ha algun error en el procés d'actualitzar la contrasenya de l'usuari
     * @throws ValidationException Si hi ha algun error en la validació de les dades de la petició
     */
    public function actualitzarContrasenya(Request $request)
    {
        try {
            $request->validate(
                [
                    'old_password' => 'required',
                    'password' => 'required|min:6|max:25|regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).+$/|confirmed',
                    'password_confirmation' => 'required',
                ],
                [
                    'old_password.required' => 'El camp contrasenya actual és obligatori',
                    'password.required' => 'El camp nova contrasenya és obligatori',
                    'password.string' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.min' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.max' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.regex' => 'La contrasenya ha de tenir com a mínim 6 caràcters, un número, una lletra majúscula i una minúscula',
                    'password.confirmed' => 'Les contrasenyes no coincideixen',
                    'password_confirmation.required' => 'El camp confirmar contrasenya és obligatori',
                ]
            );
            // Agafem les dades de l'usuari
            $user = User::getUserById(Auth::user()->id);

            // Comprovem si l'usuari té una contrasenya
            if (!$user->password) {
                return redirect()->back()->with('error', 'Aquest usuari no té permisos per a canviar la contrasenya');
            }

            // Comprovem si la contrasenya actual és correcta
            if (!Hash::check($request->old_password, $user->password)) {
                return redirect()->back()->withErrors(['old_password' => 'La contrasenya actual no és correcta'], 'actualitzarContrasenya');
            }

            // Actualitzem la contrasenya de l'usuari
            $user->password = $request->password;
            $user->save();

            // Retornem un missatge de confirmació
            return redirect()->back()->with('success', 'Contrasenya actualitzada correctament');
        } catch (ValidationException $e) {
            return redirect()->back()->withErrors($e->validator->getMessageBag(), 'actualitzarContrasenya');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Hi ha ocurregut un problema en el procés d\'actualitzar la contrasenya, tornar a provar o prova-ho més tard');
        }
    }
}
