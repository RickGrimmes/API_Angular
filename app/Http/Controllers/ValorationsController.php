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

            $id = $authenticatedUser->id;
            $tipo = $authenticatedUser->role_id;

            if ($tipo == 1)
            {
                $valorations = Valoration::with([
                    'user:id,name',
                    'videogame:id,nombre'
                ])->get();
            }
            else {
                $valorations = Valoration::with([
                    'user:id,name',
                    'videogame:id,nombre'
                ])->where('user_id', "=", $id)->get();
            }

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

    public function show($id)
    {
        try
        {
            $valoration = Valoration::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $valoration,
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener el provider',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $authenticatedUser = $request->user();

        $validator = Validator::make($request->all(), [
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

            $valorationData = $request->only(['videogame_id', 'estrellas']) + ['user_id' => $authenticatedUser->id];
            $valoration = Valoration::create($valorationData);

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

    public function update(Request $request, $id)
    {
        $authenticatedUser = $request->user();

        $valoration = Valoration::find($id);

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
