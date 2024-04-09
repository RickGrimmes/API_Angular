<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\Valoration; 
use App\Models\videogamePlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VideogamePlatformController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $authenticatedUser = $request->user();
            DB::enableQueryLog();

            $videogameplats = videogamePlatform::with([
                'platform:id,plataforma',
                'videogame:id,nombre'
            ])->get();

            $videogameplat = $videogameplats->map(function ($videogameplat)
            {
                return [
                    'id' => $videogameplat->id,
                    'platform_name' => $videogameplat->platform->plataforma,
                    'videogame_name' => $videogameplat->videogame->nombre,
                    'updated_at' => $videogameplat->updated_at,
                    'created_at' => $videogameplat->created_at
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
                'data' => $videogameplat
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
            $vp = videogamePlatform::findOrFail($id);

            return response()->json([
                'status' => 'success',
                'data' => $vp,
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
            
            $videogameplats = videogamePlatform::with([
                'platform:id,plataforma',
                'videogame:id,nombre'
            ])->where('videogame_id', $id)
            ->get();

            $videogameplat = $videogameplats->map(function ($videogameplat) 
            {
                return [
                'id' => $videogameplat->id,
                'platform_name' => $videogameplat->platform->plataforma,
                'videogame_name' => $videogameplat->videogame->nombre,
                'updated_at' => $videogameplat->updated_at,
                'created_at' => $videogameplat->created_at
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
                'data' => $videogameplat
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

            $videogameplats = videogamePlatform::with([
                'platform:id,plataforma',
                'videogame:id,nombre'
            ])->where('platform_id', $id)
            ->get();

            $videogameplat = $videogameplats->map(function ($videogameplat) 
            {
                return [
                'id' => $videogameplat->id,
                'platform_name' => $videogameplat->platform->plataforma,
                'videogame_name' => $videogameplat->videogame->nombre,
                'updated_at' => $videogameplat->updated_at,
                'created_at' => $videogameplat->created_at
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
                'data' => $videogameplat
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
            'platform_id' => 'required|exists:platforms,id',
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

            $videogameplat = videogamePlatform::create($request->all());

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($videogameplat),
                'request_time'=> now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $videogameplat
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

    public function update(Request $request, $id)
    {
        $authenticatedUser = $request->user();

        $videogameplat = videogamePlatform::find($id);

        $validator = Validator::make($request->all(), [
            'platform_id' => 'required|integer|exists:platforms,id'
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
            
            $videogameplat->update(['platform_id' => $request->platform_id]);

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($videogameplat),
                'request_time'=> now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $videogameplat
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
