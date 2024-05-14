<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', [UserController::class, 'home'])->name('home');

Route::get('login',[UserController::class, 'loginView'])->name('login')->middleware('guest');

Route::get('registre',[UserController::class, 'registreView'])->name('registre')->middleware('guest');

Route::post('login', [UserController::class, 'login'])->name('login')->middleware('guest');

Route::get('login-google', [UserController::class, 'loginGoogle'])->name('login-google')->middleware('guest');;

Route::get('/google-callback', [UserController::class, 'googleCallback'])->name('google-callback')->middleware('guest');;

Route::post('registre', [UserController::class, 'registre'])->name('registre')->middleware('guest');

Route::post('logout', [UserController::class, 'logout'])->name('logout')->middleware('auth');

Route::post('recuperar', [UserController::class, 'recuperar'])->name('recuperar')->middleware('guest');

Route::get('restaurarContrasenya/{token}', [UserController::class, 'restaurarForm'])->name('restaurarContrasenya')->middleware('guest');

Route::post('restaurarContrasenya', [UserController::class, 'restaurarContrasenya'])->name('restaurarContrasenya.post')->middleware('guest');

Route::post('/api/send-message', [UserController::class, 'sendMessage'])->name('send-message')->middleware('auth');

Route::post('/api/send-message-to-client', [UserController::class, 'sendMessageToClient'])->name('send-message-to-client')->middleware('auth');

Route::post('/api/get-user-messages', [UserController::class, 'getUserMessages'])->name('get-user-messages')->middleware('auth');

Route::post('/api/marcar-missatges-llegits', [UserController::class, 'marcarMissatgesComLLegits'])->name('marcar-missatges-llegits')->middleware('auth');

Route::post('enviarSolicitudAmic', [UserController::class, 'enviarSolicitudAmic'])->name('enviarSolicitudAmic')->middleware('auth');

Route::post('acceptarSolicitudAmic', [UserController::class, 'acceptarSolicitudAmic'])->name('acceptarSolicitudAmic')->middleware('auth');

Route::post('rebutjarSolicitudAmic', [UserController::class, 'rebutjarSolicitudAmic'])->name('rebutjarSolicitudAmic')->middleware('auth');

Route::post('eliminarAmic', [UserController::class, 'eliminarAmic'])->name('eliminarAmic')->middleware('auth');

Route::get('perfil/{id}', [UserController::class, 'perfil'])->name('perfil');

Route::get('perfil', [UserController::class, 'perfilPropi'])->name('perfilPropi')->middleware('auth');

Route::get('configuracio', [UserController::class, 'configuracio'])->name('configuracio')->middleware('auth');

Route::post('actualitzarImatgePerfil', [UserController::class, 'actualitzarImatgePerfil'])->name('actualitzarImatgePerfil')->middleware('auth');

Route::post('eliminarImatgePerfil', [UserController::class, 'eliminarImatgePerfil'])->name('eliminarImatgePerfil')->middleware('auth');

Route::post('actualitzarDades', [UserController::class, 'actualitzarDades'])->name('actualitzarDades')->middleware('auth');

Route::post('actualitzarContrasenya', [UserController::class, 'actualitzarContrasenya'])->name('actualitzarContrasenya')->middleware('auth');

