<?php

use App\Http\Controllers\GenreController;
use App\Http\Controllers\LogsController;
use App\Http\Controllers\NotificationsController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderDetailsController;
use App\Http\Controllers\PlatformController;
use App\Http\Controllers\ProvidersController;
use App\Http\Controllers\ShippersController;
use App\Http\Controllers\UsersController;
use App\Http\Controllers\ValorationsController;
use App\Http\Controllers\VideogamePlatformController;
use App\Http\Controllers\VideogameProviderController;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
*/

// hay que ver si a user le metemos algo para mostrar a los activos, lo dejo en 0 porque todos tienen el isactive false, pero debería mostrar los true
//Route::resource('Users',UsersController::class);

Route::get('Logs', [LogsController::class, 'index']);

Route::post('sendEmail', [UsersController::class, 'sendEmail']);

Route::post('User', [UsersController::class, 'store']); // 1
Route::post('User/login', [UsersController::class, 'login']); // 1

// protected routes
 Route::middleware('jwt.verify')->group(function(){
    Route::get('User', [UsersController::class, 'index']); // 1
    Route::get('User/{id}', [UsersController::class, 'show']); // 1
    Route::delete('User/{id}', [UsersController::class, 'destroy']); // 1
    Route::put('User/{id}', [UsersController::class, 'update']);
    Route::post('User/logout', [UsersController::class, 'logout']); // 1
    Route::post('validarCodigo', [UsersController::class, 'validarCodigo']);

    Route::put('cambiarRole', [UsersController::class, 'changeRole']);
    Route::get('User2', [UsersController::class, 'index2']);
    Route::post('codecheck', [UsersController::class, 'codeCheck']);

    //ruta para validar el codigo de login
    Route::post('validarLogin', [UsersController::class, 'validarLogin']);
    //--------------------------------------------------------------------------------------------------------------------------
    Route::resource('Order',OrderController::class);
    Route::resource('OrderDit',OrderDetailsController::class);
    Route::get('OrderDit/{id}', [OrderDetailsController::class, 'show']);
    //--------------------------------------------------------------------------------------------------------------------------
    // index solo es llamar a todos los juegos
    Route::get('/videogames', [VideogamesController::class, 'index']); // 1
    // findOne busca según el id, osea solo muestra 1 juego, aquí puedo meterle mas cositas de otras tablas
    // quizá a quí meta lo de valoraciones o vp y vplat
    Route::get('/videogames/{id}', [VideogamesController::class, 'findOne']); // 1
    // crear nuevo juego, pide todo lo que se necesita para crearlo
    Route::post('/videogames', [VideogamesController::class, 'store']);
    // actualizar el juego, en la ruta se ocupa una id para que sepa cuál juego es, y ya según lo que le quieras mover
    Route::put('/videogames/{id}', [VideogamesController::class, 'update']);
    // elimina el juego en base a la id, la ruta requiere de la id, solo eso necesita
    Route::delete('/videogames/{id}', [VideogamesController::class, 'destroy']);
    //--------------------------------------------------------------------------------------------------------------------------
    Route::get('/shippers', [ShippersController::class, 'index']);
    Route::get('/shippers/{id}', [ShippersController::class, 'show']);
    Route::post('/shippers', [ShippersController::class, 'store']);
    Route::put('/shippers/{id}', [ShippersController::class, 'update']);
    Route::delete('/shippers/{id}', [ShippersController::class, 'destroy']);

    //--------------------------------------------------------------------------------------------------------------------------
    Route::get('/providers', [ProvidersController::class, 'index']);
    Route::get('/providers/{id}', [ProvidersController::class, 'show']);
    Route::post('/providers', [ProvidersController::class, 'store']);
    Route::put('/providers/{id}', [ProvidersController::class, 'update']);
    Route::delete('/providers/{id}', [ProvidersController::class, 'destroy']);
    Route::post('/providers/{id}', [ProvidersController::class, 'quickRevive']);

    //--------------------------------------------------------------------------------------------------------------------------
    Route::get('/valorations', [ValorationsController::class, 'index']);
    Route::get('/valorations/{id}', [ValorationsController::class, 'show']);
    Route::post('/valorations', [ValorationsController::class, 'store']);
    // esta está curiosa, porque, debe recibir el id del juego, ok, pero cómo hago que ubique al user, una forma sería, obvio ubica al user con su token, sí, y al yo actualizar como usuario le pico al juego y me muestra mi valoración, ahí yo ya estoy en un usaurio y un juego, ya solo actualizo, entonces recibo como tal ambos campos, pero si es así, yo en la ruta debo recibir solo el id del juego y el del usuario, no hay de otra, debo recibir ambos, entonces si recibo ambos, ya puedo actualizar, necesito ambos id para actualizar
    // SOLO ME FALTA ESTE ACTUALIZAR PARA VALORACIONES
    Route::put('/valorations/{id}', [ValorationsController::class, 'update']);

    //--------------------------------------------------------------------------------------------------------------------------
    Route::get('/videogamePlatforms', [VideogamePlatformController::class, 'index']);
    Route::get('/videogamePlatforms/{id}', [VideogamePlatformController::class, 'show']);
    Route::get('/videogamePlatforms/v/{id}', [VideogamePlatformController::class, 'indexV']);
    Route::get('/videogamePlatforms/p/{id}', [VideogamePlatformController::class, 'indexP']);
    Route::post('/videogamePlatforms', [VideogamePlatformController::class, 'store']);
    Route::put('/videogamePlatforms/{id}', [VideogamePlatformController::class, 'update']);

    //--------------------------------------------------------------------------------------------------------------------------
    Route::get('/videogameProviders', [VideogameProviderController::class, 'index']);
    Route::get('/videogameProviders/{id}', [VideogameProviderController::class, 'show']);
    Route::get('/videogameProviders/v/{id}', [VideogameProviderController::class, 'indexV']);
    Route::get('/videogameProviders/p/{id}', [VideogameProviderController::class, 'indexP']);
    Route::post('/videogameProviders', [VideogameProviderController::class, 'store']);
    Route::put('/videogameProviders/{id}', [VideogameProviderController::class, 'update']);

    //--------------------------------------------------------------------------------------------------------------------------
    Route::get('/genres', [GenreController::class, 'index']);
    Route::post('/genres', [GenreController::class, 'sse']);

    Route::get('/platform', [PlatformController::class, 'index']);

    //--------------------------------------------------------------------------------------------------------------------------
    Route::post('/notification', [NotificationsController::class, 'create']);
 });

 Route::get('send-notification', [NotificationsController::class, 'index']);

// invitado nomas puede ver cosas pero nada mas, cliente puede ver, pero solo agregar y modificar

    
