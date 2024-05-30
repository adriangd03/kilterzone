<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_ruta;
use App\Models\User;
use App\Http\Controllers\HomeWallController;
use App\Http\Controllers\OriginalController;
use App\Models\User_comentari;
use App\Models\User_like;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use App\Models\User_escalada;
use App\Events\NouComentari;
use App\Events\EditarComentari;
use App\Events\EliminarComentari;
use Illuminate\Support\Str;





class RutaController extends Controller
{
    public function rutaView($id)
    {
        try {
            // Comprovem que la ruta existeixi
            $ruta = User_ruta::find($id);
            if (!$ruta) {
                return redirect()->route('home')->with('error', 'La ruta no existeix');
            }

            // Agafem les dades de l'usuari
            $user = User::getUserById($ruta->user_id);

            Carbon::setLocale('ca');

            // Calculem quan fa que es va crear la ruta
            $ruta->created = now()->diffForHumans($ruta->created_at, ['syntax' => Carbon::DIFF_ABSOLUTE, 'aUnit' => true]);

            // Agafem els comentaris de la ruta
            $comentaris = User_comentari::getComentaris($ruta->id);


            if (!Auth::check()) {
                $notFriends = User::getAll();
                return view('ruta', compact('ruta', 'user', 'notFriends', 'comentaris'));
            }

            $liked = User_like::isLiked(Auth::user()->id, $ruta->id);

            $escalat = User_escalada::haEscalat(Auth::user()->id, $ruta->id);

            // Agafem les dades del chat
            $chatData = User::getChatData();
            $friends = $chatData['friends'];
            $friendRequests = $chatData['friendRequests'];
            $notFriends = $chatData['notFriends'];
            $totalUnreadMessages = $chatData['totalUnreadMessages'];
            $totalFriendRequests = $chatData['totalFriendRequests'];

            return view('ruta', compact('ruta', 'friends', 'friendRequests', 'notFriends', 'totalUnreadMessages', 'totalFriendRequests', 'user', 'liked', 'comentaris', 'escalat'));
        } catch (\Exception $e) {
            return redirect()->route('home')->with('error', 'Error al carregar la pàgina de la ruta' . $e);
        }
    }

    /**
     * Funció per carregar la vista de creació de rutes
     * @return \Illuminate\Http\Response
     * @throws \Exception 
     */
    public function crearRutaView()
    {
        try {

            $chatData = User::getChatData();
            $friends = $chatData['friends'];
            $friendRequests = $chatData['friendRequests'];
            $notFriends = $chatData['notFriends'];
            $totalUnreadMessages = $chatData['totalUnreadMessages'];
            $totalFriendRequests = $chatData['totalFriendRequests'];

            return view('crear_ruta', compact('friends', 'friendRequests', 'notFriends', 'totalUnreadMessages', 'totalFriendRequests'));
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error al carregar la pàgina de creació de rutes');
        }
    }

    /**
     * Funció per crear una ruta
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     * @throws \ValidationException
     * @throws \Exception 
     */
    public function crearRuta(Request $request)
    {
        try {

            $request->validate([
                'nom' => 'required|string|max:45|unique:user_ruta,nom_ruta',
                'descripcio' => 'required|string|max:255',
                'inclinacio' => 'required|in:0º,5º,10º,15º,20º,25º,30º,35º,40º,45º,50º,55º,60º,65º,70º',
                'dificultat' => 'required|in:4a,4b,4c,5a,5b,5c,6a,6a+,6b,6b+,6c,6c+,7a,7a+,7b,7b+,7c,7c+,8a,8a+,8b,8b+,8c,8c+',
                'peces' => 'required',
                'layout' => 'required|in:homeWall,original',
                'size' => 'required|in:7x10FullRideLedKitHomeWall,7x10MainlineLedKitHomeWall,7x10AuxiliaryLedKitHomeWall,10x10FullRideLedKitHomeWall,10x10MainlineLedKitHomeWall,10x10AuxliaryLedKitHomeWall,8x12FullRideLedKitHomeWall,8x12MainlineLedKitHomeWall,8x12AuxiliaryLedKitHomeWall,10x12FullRideLedKitHomeWall,10x12MainlineLedKitHomeWall,10x12AuxiliaryLedKitHomeWall,16x12BoltOnsScrewOns,16x12BoltOns,16x12ScrewOns,12x14BoltOnsScrewOns,12x14BoltOns,12x14ScrewOns,12x12BoltOnsScrewOns,12x12BoltOns,12x12ScrewOns,8x12BoltOnsScrewOns,8x12BoltOns,8x12ScrewOns,7x10BoltOnsScrewOns,7x10BoltOns,7x10ScrewOns',

            ], [
                'nom.required' => 'El nom de la ruta és obligatori',
                'nom.string' => 'El nom de la ruta ha de ser un text',
                'nom.max' => 'El nom de la ruta no pot superar els 45 caràcters',
                'nom.unique' => 'Ja existeix una ruta amb aquest nom',
                'descripcio.required' => 'La descripció de la ruta és obligatòria',
                'descripcio.string' => 'La descripció de la ruta ha de ser un text',
                'descripcio.max' => 'La descripció de la ruta no pot superar els 255 caràcters',
                'inclinacio.required' => 'La inclinació de la ruta és obligatòria',
                'inclinacio.in' => 'La inclinació de la ruta no és vàlida',
                'dificultat.required' => 'La dificultat de la ruta és obligatòria',
                'dificultat.in' => 'La dificultat de la ruta no és vàlida',
                'peces.required' => 'Les peçes de la ruta són obligatòries',
                'layout.required' => 'El layout de la ruta és obligatori',
                'layout.in' => 'El layout de la ruta no és vàlid',
                'size.required' => 'La mida de la ruta és obligatòria',
                'size.in' => 'La mida de la ruta no és vàlida',
            ]);

            if ($request->layout === 'homeWall') {

                $wall = HomeWallController::getSvg($request->size);
            } else {

                $wall = OriginalController::getSvg($request->size);
            }


            $peces = json_decode($request->peces);


            // Array per comprovar que no hi hagi peçes repetides
            $ids = [];
            // Variables per comprovar que hi hagi una o dues peçes de start i end
            $start = 0;
            $end = 0;


            foreach ($peces as $peca) {

                // Comprovem que no hi hagi peces repetides
                if (in_array($peca->id, $ids)) {
                    return response()->json(['error' => 'No pots repetir peçes'], 500);
                }
                $ids[] = $peca->id;

                // Busquem la id de la peça al string de wall i si no la troba retornem un error
                $position = strpos($wall, $peca->id);
                if ($position === false) {
                    return response()->json(['error' => 'Una de les peçes que has escollit no existeix', 'wall' => $wall], 500);
                }
                // Busquem la etiqueta de style de la peça al string de wall i si la troba pintem la peça de wall segons si es start, end, normal o foot 
                $positionReverse = strlen($wall) - $position - 1;

                $positionStyleReverse = strpos(strrev($wall), '"=elyts', $positionReverse);

                $positionStyle = strlen($wall) - $positionStyleReverse;

                if ($peca->tipus == 'start') {
                    $start++;
                    // Afegim el stroke y el stroke-width de la peça de start
                    $wall = substr_replace($wall, 'stroke:yellow; stroke-width:5px;', $positionStyle, 0);
                } else if ($peca->tipus == 'end') {
                    $end++;
                    // Afegim el stroke y el stroke-width de la peça de end
                    $wall = substr_replace($wall, 'stroke:red; stroke-width:5px;', $positionStyle, 0);
                } else if ($peca->tipus == 'foot') {
                    // Afegim el stroke y el stroke-width de la peça de foot
                    $wall = substr_replace($wall, 'stroke:orange; stroke-width:5px;', $positionStyle, 0);
                } else {
                    // Afegim el stroke y el stroke-width de la peça de normal
                    $wall = substr_replace($wall, 'stroke:blue; stroke-width:5px;', $positionStyle, 0);
                }
            }

            if ($start > 2 || $start === 0 || $end > 2 || $end === 0) {
                return response()->json(['error' => 'Has de tenir una o dos peces de principi i una o dos peces de final '], 500);
            }


            $ruta = new User_ruta();
            $ruta->user_id = auth()->user()->id;
            $ruta->ruta = $wall;
            $ruta->nom_ruta = $request->nom;
            $ruta->descripcio = $request->descripcio;
            $ruta->dificultat = $request->dificultat;
            $ruta->inclinacio = $request->inclinacio;
            $ruta->layout = $request->layout . ' ' . $request->size;
            if ($request->esborrany) {
                $ruta->esborrany = 1;
            }
            $ruta->save();

            return response()->json(['route' => route('ruta', ['id' => $ruta->id])], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors(),], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la ruta'], 500);
        }
    }

    /**
     * Funció per donar like a una ruta
     * @param int $id id de la ruta
     */
    public function like($id)
    {
        try {

            $ruta = User_ruta::find($id);
            if (!$ruta) {
                return response()->json(['error' => 'La ruta no existeix'], 404);
            }

            $liked = User_like::like(Auth::user()->id, $ruta->id);

            if ($liked) {
                User_ruta::sumarLike($ruta);
            } else {
                User_ruta::restarLike($ruta);
            }

            return response()->json(['liked' => $liked], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al donar like a la ruta'], 500);
        }
    }

    /**
     * Funció per marcar una escalada a una ruta
     * @param int $id id de la ruta
     */
    public function escalada($id)
    {
        try {

            $ruta = User_ruta::find($id);
            if (!$ruta) {
                return response()->json(['error' => 'La ruta no existeix'], 404);
            }

            $escalada = User_escalada::escalada(Auth::user()->id, $ruta->id);

            if ($escalada) {
                User_ruta::sumarEscalada($ruta);
            } else {
                User_ruta::restarEscalada($ruta);
            }

            return response()->json(['escalada' => $escalada], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al marcar una escalada a la ruta' . $e], 500);
        }
    }

    /**
     * Funció per comentar una ruta
     * @param Request $request
     * 
     */
    public function comentar(Request $request)
    {
        try {
            $request->validate([
                'comentari' => 'required|string|max:255|min:5',
                'rutaId' => 'required|integer',
                'comentariId' => 'integer|nullable',

            ], [
                'comentari.required' => 'El comentari és obligatori',
                'comentari.string' => 'El comentari ha de ser un text',
                'comentari.max' => 'El comentari no pot superar els 255 caràcters',
                'comentari.min' => 'El comentari ha de tenir almenys 5 caràcters',
                'rutaId.required' => 'La ruta és obligatòria',
                'rutaId.integer' => 'La ruta ha de ser un número',
            ]);

            $ruta = User_ruta::find($request->rutaId);
            if (!$ruta) {
                return response()->json(['error' => 'La ruta no existeix'], 404);
            }

            $request->comentari = strip_tags($request->comentari);
            $request->comentari = htmlspecialchars($request->comentari);


            if (strlen($request->comentari) < 5) {
                return response()->json(['error' => 'El comentari no ha de contenir caràcters html'], 500);
            }


            if ($request->comentariId) {
                $comentari = User_comentari::getComentari($request->comentariId);
                if (!$comentari) {
                    return response()->json(['error' => 'El comentari no existeix'], 404);
                }

                if ($comentari->ruta_id != $ruta->id) {
                    return response()->json(['error' => 'El comentari no pertany a la ruta'], 404);
                }

                if ($request->comentari[0] === '@') {


                    if ($comentari->comentari_id) {
                        $comentari->id = $comentari->comentari_id;
                    }

                    $user = User::getUserById($comentari->user_id);

                    if (strpos($request->comentari, '@' . $user->username) === false) {
                        $request->comentari = Str::replaceFirst('@', '<a href="' . route('perfil', ['id' => $user->id]) . '">@' . $user->username . '</a> ', $request->comentari);
                    } else {
                        $request->comentari = Str::replaceFirst('@' . $user->username, '<a href="' . route('perfil', ['id' => $user->id]) . '">@' . $user->username . '</a> ', $request->comentari);
                    }

                    $user = User::getUserById(Auth::user()->id);

                    $request->comentari = User::addLinks($request->comentari, strpos($request->comentari, '</a>'));

                    $nouComentari =  User_comentari::addComentari($user->id, $ruta->id, $request->comentari, $comentari->id);

                    event(new NouComentari($ruta->id, $nouComentari, $user, $comentari->id));
                    return response()->json(['user' => $user, 'comentariId' => $comentari->id, 'comentari' => $nouComentari], 200);
                }
            }

            $request->comentari = User::addLinks($request->comentari);

            $user = User::getUserById(Auth::user()->id);

            $nouComentari = User_comentari::addComentari($user->id, $ruta->id, $request->comentari);

            event(new NouComentari($ruta->id, $nouComentari, $user));

            return response()->json(['user' => $user, 'comentari' => $nouComentari], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors(),], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al comentar la ruta' . $e], 500);
        }
    }

    /**
     * Funció per eliminar un comentari
     * @param Request $request
     * 
     */
    public function eliminarComentari(Request $request)
    {
        try {
            $request->validate([
                'comentariId' => 'required|exists:user_comentari,id',
            ], [
                'comentariId.required' => 'La id del comentari és obligatori',
                'comentariId.exists' => 'El comentari no existeix',
            ]);

            $comentari = User_comentari::getComentari($request->comentariId);

            $isCreador = User_ruta::isCreador($comentari->ruta_id, Auth::user()->id);


            if ($comentari->user_id != Auth::user()->id && !$isCreador) {
                return response()->json(['error' => 'No pots eliminar aquest comentari'], 403);
            }

            if ($comentari->esborrat) {
                return response()->json(['error' => 'El comentari ja ha estat eliminat'], 403);
            }

            User_comentari::eliminarComentari($comentari);

            event(new EliminarComentari($comentari->ruta_id, $comentari->id, $isCreador));

            return response()->json(['comentariId' => $comentari->id], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors(),], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al eliminar el comentari' . $e], 500);
        }
    }

    /**
     * Funció per editar un comentari
     * @param Request $request
     * 
     */
    public function editarComentari(Request $request)
    {
        try {
            $request->validate([
                'comentariId' => 'required|exists:user_comentari,id',
                'comentari' => 'required|string|max:255|min:5',
            ], [
                'comentariId.required' => 'El comentari és obligatori',
                'comentariId.exists' => 'El comentari no existeix',
                'comentari.required' => 'El comentari és obligatori',
                'comentari.string' => 'El comentari ha de ser un text',
                'comentari.max' => 'El comentari no pot superar els 255 caràcters',
                'comentari.min' => 'El comentari ha de tenir almenys 5 caràcters',
            ]);

            $comentari = User_comentari::getComentari($request->comentariId);

           
            if ($comentari->user_id != Auth::user()->id) {
                return response()->json(['error' => 'No pots editar aquest comentari'], 403);
            }

            if ($comentari->esborrat) {
                return response()->json(['error' => 'El comentari ha estat eliminat'], 403);
            }

            $request->comentari = strip_tags($request->comentari);
            $request->comentari = htmlspecialchars($request->comentari);

            if (strlen($request->comentari) < 5) {
                return response()->json(['error' => 'El comentari no ha de contenir caràcters html'], 500);
            }

            if ($comentari->comentari_id) {
                if ($request->comentari[0] !== '@') {
                    $request->comentari = '@' . $request->comentari;
                }
                $comentariResposta = User_comentari::getComentari($comentari->comentari_id);   
                $user = User::getUserById($comentariResposta->user_id);
                if (strpos($request->comentari, '@' . $user->username) === false) {
                    $request->comentari = Str::replaceFirst('@', '<a href="' . route('perfil', ['id' => $user->id]) . '">@' . $user->username . '</a> ', $request->comentari);
                } else {
                    $request->comentari = Str::replaceFirst('@' . $user->username, '<a href="' . route('perfil', ['id' => $user->id]) . '">@' . $user->username . '</a> ', $request->comentari);
                }
            }

            $request->comentari = User::addLinks($request->comentari);

            User_comentari::editarComentari($comentari, $request->comentari);

            event(new EditarComentari($comentari->ruta_id, $comentari, ));

            return response()->json(['comentariId' => $comentari->id, 'comentari' => $request->comentari], 200);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors(),], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al editar el comentari' . $e], 500);
        }
    }
}
