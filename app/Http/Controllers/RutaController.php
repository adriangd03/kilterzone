<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User_ruta;
use App\Models\User;
use App\Http\Controllers\HomeWallController;
use Illuminate\Validation\ValidationException;




class RutaController extends Controller
{
    public function index()
    {
        return view('rutes.index');
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
        $wall = HomeWallController::homeWall7x10fullRideLedKit();
        try {

            $request->validate(
                [
                    'nom' => 'required|string|max:45',
                    'descripcio' => 'required|string|max:255',
                    'inclinacio' => 'required|in:'.['0º', '5º', '10º', '15º', '20º', '25º', '30º', '35º', '40º', '45º', '50º', '55º', '60º', '65º', '70º'],
                    'dificultat' => 'required|in:' . ['4a', '4b', '4c', '5a', '5b', '5c', '6a', '6a+', '6b', '6b+', '6c', '6c+', '7a', '7a+', '7b', '7b+', '7c', '7c+', '8a', '8a+', '8b', '8b+', '8c', '8c+',],
                    'peces' => 'required',
                    'layout' => 'required|in:'.['homeWall', 'original'],
                    'size' => 'required|in:'.['7x10FullRideLedKit', '7x10MainlineLedKit', '7x10AuxliaryLedKit', '10x10FullRideLedKit', '10x10MainlineLedKit', '10x10AuxliaryLedKit', '8x12FullrideLedKit', '8x12MainlineLedKit', '10x12FullRideLedKit', '10x12MainlineLedKit'],
                    'mainline' => 'required',
                    'auxiliary' => 'required',
                ],
                [
                    'nom.required' => 'El nom de la ruta és obligatori',
                    'nom.string' => 'El nom de la ruta ha de ser un text',
                    'nom.max' => 'El nom de la ruta no pot superar els 45 caràcters',
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
                    'mainline.required' => 'La mainline de la ruta és obligatòria',
                    'auxiliary.required' => 'La auxiliary de la ruta és obligatòria',
                ]
            );

            $peces = $request->peces;

            foreach ($peces as $peca) {
                
            }

            return response()->json(['success' => 'Ruta creada correctament', 'wall' => $wall,]);
        } catch (ValidationException $e) {
            return response()->json(['error' => $e->errors(), 'wall' => $wall], 422);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al crear la ruta', 'wall' => $wall], 500);
        }
    }
}
