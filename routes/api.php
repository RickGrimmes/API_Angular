<?php

use App\Http\Controllers\OrderController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\VideogamesController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::resource('User',UsersController::class);
Route::resource('Order',OrderController::class);

// index solo es llamar a todos los juegos
Route::get('/videogames', [VideogamesController::class, 'index']);
// findOne busca según el id, osea solo muestra 1 juego, aquí puedo meterle mas cositas de otras tablas
Route::get('/videogames/{id}', [VideogamesController::class, 'findOne']);

// crear nuevo juego, pide todo lo que se necesita para crearlo
Route::post('/videogames', [VideogamesController::class, 'store']);

// actualizar el juego, en la ruta se ocupa una id para que sepa cuál juego es, y ya según lo que le quieras mover
Route::put('/videogames/{id}', [VideogamesController::class, 'update']);

// elimina el juego en base a la id, la ruta requiere de la id, solo eso necesita
Route::delete('/videogames/{id}', [VideogamesController::class, 'destroy']);


// invitado nomas puede ver cosas pero nada mas, cliente puede ver, pero solo agregar y modificar