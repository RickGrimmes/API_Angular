<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\videogameProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VideogameProviderController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $authenticatedUser = $request->user();
            DB::enableQueryLog();

            $videogameprovs = videogameProvider::with([
                'videogame:id,nombre',
                'provider:id,nombre'
            ])->get();

            $videogameprov = $videogameprovs->map(function ($videogameprov)
            {
                return [
                    'id' => $videogameprov->id,
                    'videogame_name' => $videogameprov->videogame->nombre,
                    'provider_name' => $videogameprov->provider->nombre,
                    'updated_at' => $videogameprov->updated_at,
                    'created_at' => $videogameprov->created_at
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
                'query' => $sqlQuery, 
                'data' => null,
                'request_time' => now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $videogameprov
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los datos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function show($id)
    {
        try
        {
            $vprovider = videogameProvider::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $vprovider,
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

    public function indexV(Request $request, $id)
    {
        try
        {
            $authenticatedUser = $request->user();
            DB::enableQueryLog();

            $videogameprovs = videogameProvider::with([
                'provider:id,nombre',
                'videogame:id,nombre'
            ])->where('videogame_id', $id)
            ->get();

            $videogameprov = $videogameprovs->map(function ($videogameprov) 
            {
                return [
                    'id' => $videogameprov->id,
                    'videogame_name' => $videogameprov->videogame->nombre,
                    'provider_name' => $videogameprov->provider->nombre,
                    'updated_at' => $videogameprov->updated_at,
                    'created_at' => $videogameprov->created_at
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
                'query' => $sqlQuery, 
                'data' => null,
                'request_time' => now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $videogameprov
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los datos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function indexP(Request $request, $id)
    {
        try
        {
            $authenticatedUser = $request->user();
            DB::enableQueryLog();

            $videogameprovs = videogameProvider::with([
                'provider:id,nombre',
                'videogame:id,nombre'
            ])->where('provider_id', $id)
            ->get();

            $videogameprov = $videogameprovs->map(function ($videogameprov) 
            {
                return [
                    'id' => $videogameprov->id,
                    'videogame_name' => $videogameprov->videogame->nombre,
                    'provider_name' => $videogameprov->provider->nombre,
                    'updated_at' => $videogameprov->updated_at,
                    'created_at' => $videogameprov->created_at
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
                'query' => $sqlQuery, 
                'data' => null,
                'request_time' => now()->toDateTimeString()
            ]);
            
            return response()->json([
                'status' => 'success',
                'data' => $videogameprov
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los datos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $authenticatedUser = $request->user();
        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|exists:providers,id',
            'videogame_id' => 'required|exists:videogames,id'
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

            $videogameprov = videogameProvider::create($request->all());

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($videogameprov),
                'request_time'=> now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $videogameprov
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los datos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $provider_id, $videogame_id)
    {
        $authenticatedUser = $request->user();
        $videogameprov = videogameProvider::where('provider_id', $provider_id)
        ->where('videogame_id', $videogame_id)
        ->firstOrFail();

        $validator = Validator::make($request->all(), [
            'provider_id' => 'required|integer|exists:providers,id'
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

            $videogameprov->update(['provider_id' => $request->provider_id]);

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($videogameprov),
                'request_time'=> now()->toDateTimeString()
            ]);
            
            return response()->json([
                'status' => 'success',
                'data' => $videogameprov
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
