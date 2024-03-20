<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Tymon\JWTAuth\Exceptions\JWTException;
use Tymon\JWTAuth\Facades\JWTAuth;

class UsersController extends Controller
{
    public function index(Request $request)
    {
        $authenticatedUser = Auth::user();

        if ($authenticatedUser->role_id != 1) {
            return response()->json([
                'message' => 'No tiene permiso para acceder a esta funcionalidad'
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
                'user_id' => $authenticatedUser->id,
                'user_name' => $authenticatedUser->name,
                'user_email' => $authenticatedUser->email,
                'http_verb' => $request->method(),
                'route' => $request->path(),
                'query' => json_encode($queries),
                // 'data' => json_encode($user),
                'data' => null,
                'request_time'=> now()->toDateTimeString()
            ]);
            return response()->json(['message' => 'Usuario ecncontrado: ',$user], 200);
        }
        
        RequestLog::create([
            'user_id' => $authenticatedUser->id,
            'user_name' => $authenticatedUser->name,
            'user_email' => $authenticatedUser->email,
            'http_verb' => $request->method(),
            'route' => $request->path(),
            'query' => json_encode($queries),
            'data' => null,
            'request_time'=> now()->toDateTimeString()
        ]);
        return response()->json(['message'=>'usuario no encontrado'], 404);
    }

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

        return response()->json([
            'status' => 'success',
            'data' => $user,
            'token' => $token
        ]);
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

        $users = User::with([
            'role:id,rol'
        ])->where('id', $id)
        ->get();

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
        'email'=>'required|max:255|string|email|unique:'.User::class,
        'password'=>'required|max:100|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

    
        $user->update($request->all());

        return response()->json($user, 200);
        }
        return response()->json(['message'=>'usuario no encontrado'], 404);
    }

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
            $user->delete();
            return response()->json(['message' => 'Usuario eliminado: ',$user], 200);
        }

        return response()->json(['message'=>'usuario no encontrado'], 404);
    }

}
