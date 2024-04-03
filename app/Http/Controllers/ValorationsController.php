<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\Valoration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ValorationsController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $authenticatedUser = $request->user();
            DB::enableQueryLog();

            $valorations = Valoration::with([
                'user:id,name',
                'videogame:id,nombre'
            ])->get();

            $valoration = $valorations->map(function ($valoration) {
                return [
                    'id' => $valoration->id,
                    'user_name' => $valoration->user->name,
                    'videogame_name' => $valoration->videogame->nombre,
                    'estrellas' => $valoration->estrellas,
                    'updated_at' => $valoration->updated_at,
                    'created_at' => $valoration->created_at,
                ];
            });

            $queries = DB::getQueryLog();
            $sqlQuery = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => $sqlQuery, // Query SQL ejecutado
                'data' => null,
                'request_time' => now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $valoration
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los providers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $authenticatedUser = $request->user();

        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'videogame_id' => 'required|exists:videogames,id', 
            'estrellas' => 'required|integer|min:1|max:5'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try
        {
            DB::enableQueryLog();

            $valoration = Valoration::create($request->all());

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($valoration),
                'request_time'=> now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $valoration
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los providers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $user_id, $videogame_id)
    {
        $authenticatedUser = $request->user();

        $valoration = Valoration::where('user_id', $user_id)
        ->where('videogame_id', $videogame_id)
        ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'estrellas' => 'required|integer|min:1|max:5'
        ]);

        if ($validator->fails())
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error de validación',
                'errors' => $validator->errors(),
            ], 422);
        }

        try
        {
            DB::enableQueryLog();

            $valoration->update(['estrellas' => $request->estrellas]);

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($valoration),
                'request_time'=> now()->toDateTimeString()
            ]);
            return response()->json([
                'status' => 'success',
                'data' => $valoration
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los providers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
