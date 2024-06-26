<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
    public function index(Request $request)
    {
       $authenticatedUser = $request->user();

       if ($authenticatedUser->role_id != 1) 
       {
           return response()->json([
               'message' => 'No tiene permiso para ver este contenido'
           ], 403);
       }                            
                            
        DB::enableQueryLog();

        $users = User::with([
            'role:id,rol'
        ])->where('isActive', 1)
        ->get();
        
        // recibe el query
        $querie = DB::getQueryLog();
        // para que se vea bonito, porque si no lo da con cosas extra que ni al caso para mostrar
        $queries = array_map(function ($query) {
            return str_replace('?', '%s', $query['query']);
        }, $querie);

        if ($users->isEmpty()) {
            return response()->json(['message' => 'No se encontraron usuarios activos'], 404);
        }

        $user = $users->map(function ($user)
        {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'isActive' => $user->isActive,
                'role' => $user->role->rol,
                'code' => $user->code,
                'updated_at' => $user->updated_at,
                'created_at' => $user->created_at
            ];
        });

        if($user){
            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null, 
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null, 
                'http_verb' => $request->method(),
                'route' => $request->path(),
                'query' => json_encode($queries),
                // 'data' => json_encode($user),
                'data' => null,
                'request_time'=> now()->toDateTimeString()
            ]);
            return response()->json([
                'message' => 'Usuario ecncontrado: ',
                'data' => $user
            ], 200);
        }

        return response()->json(['message'=>'usuario no encontrado'], 404);
    }

    ///modificacion de login
    public function login(Request $request)
    {
        $credenciales = $request->only('email', 'password');
        
        try
        {
            if(!$token = JWTAuth::attempt($credenciales))
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'credenciales invalidas'
                ], 400);
            }
        }
        catch(JWTException $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'token no existente'
            ], 500);
        }

        $user = JWTAuth::user();

        $user = User::where('email', $request->email)->first();
        $isActiver = $user->isActive;
        if($isActiver==1)
        {
            $token = JWTAuth::fromUser($user);
            
            RequestLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'http_verb' => $request->method(),
                'route' => $request->path(),
                'query' => null,
                'data' => 'SUCCESS',
                'request_time' => now()->toDateTimeString()
                
            ]);
            $this->sendEmail($request);
            return response()->json([
                'status' => 'succes',
                'user'=> $user
                ],200);
        }
        return response()->json([
            'status' => 'error, cuenta no encontrada'
            ],400);

    }


    public function logout (Request $request)
    {
        try 
        {
            $user = JWTAuth::user();

            JWTAuth::parseToken()->invalidate();

            RequestLog::create([
                'user_id' => $user->id,
                'user_name' => $user->name,
                'user_email' => $user->email,
                'http_verb' => $request->method(),
                'route' => $request->path(),
                'query' => null,
                'data' => 'SUCCESS',
                'request_time' => now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Sesión cerrada correctamente :D'
            ], 200);
        }   
        catch (JWTException $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al cerrar la sesión :c'
            ], 500);
        } 
    }

    public function show($id)
    {
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->id != $id) 
        {
            return response()->json([
                'message' => 'No tiene permiso para ver este usuario'
            ], 403);
        }

        DB::enableQueryLog();

        $users = User::with([
            'role:id,rol'
        ])->where('id', $id)
        ->get();

        $queries = DB::getQueryLog();

        $user = $users->map(function ($user)
        {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'isActive' => $user->isActive,
                'role' => $user->role->rol,
                'code' => $user->code,
                'updated_at' => $user->updated_at,
                'created_at' => $user->created_at
            ];
        })->first();

        if($user){
            RequestLog::create([
                'user_id' => $id,
                'user_name' => $user['name'],
                'user_email' => $user['email'],
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($queries), 
                'data' => json_encode($user),
                'request_time'=> now()->toDateTimeString()
            ]);
            return response()->json(['message' => 'Usuario ecncontrado: ',$user], 200);
        }
    
        return response()->json(['message'=>'usuario no encontrado'], 404);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|max:100|string',
            'email'=>'required|max:255|string|email|unique:'.User::class,
            'password'=>'required|max:100|string',
            'isActive'=>'max:100|string',
            'role_id'=>''
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

       $user= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'isActive'=>$request->isActive,
            'role_id' => $request->role_id ?? 2
        ]);

        $user->refresh();

        $sqlQuery = "INSERT INTO `****`.`****` (`name`, `email`, `password`) VALUES ";
        $sqlQuery .= "('" . $request->name . "', '" . $request->email . "', 'password');";

        RequestLog::create([
            'user_id' => $user->id, 
            'user_name' => $user->name, 
            'user_email' => $user->email, 
            'http_verb' => request()->method(),
            'route' => request()->path(),
            'query' => json_encode($sqlQuery), 
            'data' => json_encode($user),
            'request_time' => now()->toDateTimeString()
        ]);

        return response()->json([
            'user' => $user
        ], 201);
    }

    public function update(Request $request, $id)
    {
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->id != $id)
        {
            return response()->json([
                'message' => 'No tiene permiso para acceder a este usuario'
            ], 403);
        }

        $user = User::find($id);
        
        if($user){
        $validator = Validator::make($request->all(), [
            'email'=>'max:255|string|email|unique:'.User::class,
            'password'=>'max:100|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
    
        DB::enableQueryLog();
        $user->update(['password' => Hash::make($request->password)]);

        $queries = DB::getQueryLog();
        $user = User::find($id);

        $sqlQuery = end($queries)['query'];

        RequestLog::create([
            'user_id' => $id, 
            'user_name' => $user->name, 
            'user_email' => $user->email, 
            'http_verb' => request()->method(),
            'route' => request()->path(),
            'query' => $sqlQuery,
            'data' => json_encode($user),
            'request_time' => now()->toDateTimeString()
        ]);

        return response()->json($user, 200);
        }
        return response()->json(['message'=>'usuario no encontrado'], 404);
    }

        //modificacion de eliminar
    public function destroy($id)
    {
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->id != $id)
        {
            return response()->json([
                'message' => 'No tiene permiso para acceder a este usuario'
            ], 403);
        }
        $user = User::find($id);

        if($user){
            DB::enableQueryLog();
            $user->isActive = 0;
            $user->save();
            
            //$user = User::find($id);

            $queries = DB::getQueryLog();
            $sqlQuery = end($queries)['query'];

            RequestLog::create([
                'user_id' => $id, 
                'user_name' => $user->name ?? null, 
                'user_email' => $user->email, 
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => $sqlQuery,
                'data' => json_encode($user),
                'request_time' => now()->toDateTimeString()
            ]);
            
            return response()->json(['message' => 'Usuario eliminado: ',$user], 200);
        }
        return response()->json(['message'=>'usuario no encontrado'], 404);
    } 

    public function sendEmail(Request $request)
    {
        $codigo=rand(100000,999999);
        
        $user = User::where('email', $request->email)->first();
        $user->code = $codigo;
        $user->save();

        $contenidoCorreo = "Su código de verificación es: $codigo";
        Mail::raw($contenidoCorreo, function ($message) use ($request) {
            $message->to($request->email)->subject('Código de verificación');
        });

        return response()->json('Correo electrónico enviado con éxito');
    }

    public function validarCodigo(Request $request)
    {
        $authenticatedUser = Auth::user();
        
        //$user = User::where('email', $request->email)->first();
        $codigoAlmacenado = $authenticatedUser->code;
        $codigoSolicitud = $request->code;
        $rol = $authenticatedUser -> role_id;

        if ($rol == 3)
        {
            if ($codigoAlmacenado && $codigoAlmacenado == $codigoSolicitud) {
                $authenticatedUser->role_id = 2;
                $authenticatedUser->isActive = 1;
                $authenticatedUser->save();
                $token = JWTAuth::fromUser($authenticatedUser);
                return response()->json([
                    'status' => 'success',
                    'token' => $token]);
            } else {
                return response()->json('Código inválido');
            }
        }
        if ($codigoAlmacenado && $codigoAlmacenado == $codigoSolicitud) {
            $token = JWTAuth::fromUser($authenticatedUser);
            return response()->json([
                'status' => 'success',
                'token' => $token]);
        } else {
            return response()->json(['Código inválido'], 403);
        }
    }

    public function changeRole (Request $request)
    {
        try
        {
            $authenticatedUser = Auth::user();

            $root = $authenticatedUser->isRoot;

            if (!$root)
            {
                return response()->json([
                    'status' => 'error',
                    'message' => 'NO TIENES PERMITIDA ESTA FUNCIÓN'
                ], 403);
            }

            $validator = Validator::make($request->all(), [
                'user_id' => 'required|exists:users,id', 
                'role_id' => 'required|exists:roles,id', 
            ]);

            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }

            $user = User::findOrFail($request->user_id); 
            $user->update(['role_id' => $request->role_id]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'El rol del usuario ha sido actualizado correctamente.',
                'data' => $user
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'error' => $e
            ], 403);
        }
    }

    public function index2 (Request $request)
    {
        $authenticatedUser = $request->user();                         
                          
        $users = User::with([
            'role:id,rol'
        ])->where('isActive', 1)
        ->get();

        if ($users->isEmpty()) {
            return response()->json(['data' => 'No se encontraron usuarios activos'], 404);
        }

        $user = $users->map(function ($user)
        {
            return [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $user->role->rol,
                'isRoot' => $user->isRoot
            ];
        });

        if($user){
            return response()->json([
                'message' => 'Usuario ecncontrado: ',
                'data' => $user
            ], 200);
        }

        return response()->json(['message'=>'usuario no encontrado'], 404);
    }

    public function codeCheck(Request $request)
    {
        try {
            $authenticatedUser = Auth::user();

            $validator = Validator::make($request->all(), [
                'code' => 'required|size:6|string',
            ]);
        
            if ($validator->fails()) {
                return response()->json($validator->errors(), 400);
            }    

            $codigo = $authenticatedUser->code;
            $codigoSolicitud = $request->code;

            if ($codigo != $codigoSolicitud) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'El código ingresado no coincide con el código del usuario'
                ], 400);
            } else {
                return response()->json([
                    'status' => 'success',
                    'message' => 'El código ingresado coincide con el código del usuario'
                ], 200);
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Error en la verificación del código.',
                'error' => $e->getMessage()
            ], 418);
        }
    }

        //metodo para validar el codigo de login
    public function validarLogin(Request $request)
    {
        $user = Auth::user();
        
        $user = User::where('email', $request->email)->first();
        $codigoAlmacenado = $user->code;
        $codigoSolicitud = $request->code;
        if ($codigoAlmacenado && $codigoAlmacenado == $codigoSolicitud) {
            $token = JWTAuth::fromUser($user);
            return response()->json([
                'status' => 'success',
                'token' => $token]);
        } else {
            return response()->json(['Código inválido'], 403);
        }
    }
}
