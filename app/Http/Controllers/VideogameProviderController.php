<?php

namespace App\Http\Controllers;

use App\Models\videogameProvider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideogameProviderController extends Controller
{
    public function index()
    {
        try
        {
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

    public function indexV($id)
    {
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

        try
        {
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

    public function indexP($id)
    {
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
        try
        {
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
            $videogameprov = videogameProvider::create($request->all());

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
            $videogameprov->update(['provider_id' => $request->provider_id]);

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
