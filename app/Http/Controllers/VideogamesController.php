<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\Videogame;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class VideogamesController extends Controller
{
    public function index(Request $request)
    {
        try 
        {
            $authenticatedUser = $request->user();

            DB::enableQueryLog();

            $VIDEOGAMES = Videogame::with([
                'genre:id,name'
            ])->where('inStock', '>=', 1)->get();

            $videogames = $VIDEOGAMES->map(function ($videogames)
            {
                return [
                    'id' => $videogames->id,
                    'nombre' => $videogames->nombre,
                    'genre' => $videogames->genre->name,
                    'unitPrice' => $videogames->unitPrice,
                    'description' => $videogames->description,
                    'inStock' => $videogames->inStock,
                    'discount' => $videogames->discount,
                    'updated_at' => $videogames->updated_at,
                    'created_at' => $videogames->created_at
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
                'data' => $videogames,
            ], 200);
        } 
        catch (\Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los videojuegos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function findOne(Request $request, $id)
    { 
        try 
        {
            $authenticatedUser = $request->user();

            DB::enableQueryLog();
            $videogames = Videogame::with(['genre:id,name'])->findOrFail($id);
                 
            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            $videogame = [
                'id' => $videogames->id,
                'nombre' => $videogames->nombre,
                'genre' => $videogames->genre->name,
                'unitPrice' => $videogames->unitPrice,
                'description' => $videogames->description,
                'inStock' => $videogames->inStock,
                'discount' => $videogames->discount,
                'updated_at' => $videogames->updated_at,
                'created_at' => $videogames->created_at
            ];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($videogame),
                'request_time'=> now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $videogame,
            ], 200);
        } 
        catch (\Exception $e) 
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los videojuegos',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $authenticatedUser = $request->user();

        $validator = Validator::make($request->all(), [
            'nombre' => 'required|string|min:2|max:200',
            'genre_id' => 'required|exists:genres,id', 
            'unitPrice' => 'required|numeric|min:0|',
            'description' => 'required|string|min:10',
            'inStock' => 'required|integer',
            'discount' => 'numeric',
        ]);

        // Si la validación falla, devolver errores
        if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error de validación',
            'errors' => $validator->errors(),
            ], 422); 
        }
            
        try
        {
            DB::enableQueryLog();

            $videogame = Videogame::create($request->all());

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($videogame),
                'request_time'=> now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'succes',
                'data' => $videogame
            ], 201);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al crear el videojuego',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $authenticatedUser = $request->user();

        $videogame = Videogame::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'unitPrice' => 'numeric|min:0|',
            'description' => 'string|min:10',
            'inStock' => 'integer',
            'discount' => 'numeric|max:100',
        ]);

        if ($validator->fails()) {
        return response()->json([
            'status' => 'error',
            'message' => 'Error de validación',
            'errors' => $validator->errors(),
            ], 422);
        }

        try
        {
            DB::enableQueryLog();

            $videogame->update($request->only(['unitPrice', 'description', 'inStock', 'discount']));

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];
    
                RequestLog::create([
                    'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                    'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                    'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                    'http_verb' => request()->method(),
                    'route' => request()->path(),
                    'query' => json_encode($querie), 
                    'data' => json_encode($videogame),
                    'request_time'=> now()->toDateTimeString()
                ]);

            return response()->json([
                'status' => 'success',
                'data' => $videogame
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al actualizar el videojuego',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try
        {
            $authenticatedUser = $request->user();
            DB::enableQueryLog();

            $videogame = Videogame::findOrFail($id);
            $videogame->update(['inStock' => 0]);

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($videogame),
                'request_time'=> now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Videojuego eliminado'
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al eliminar el videojuego',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}

