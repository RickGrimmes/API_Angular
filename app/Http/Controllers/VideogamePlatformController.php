<?php

namespace App\Http\Controllers;

use App\Models\Valoration;
use App\Models\videogamePlatform;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class VideogamePlatformController extends Controller
{
    public function index()
    {
        try
        {
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

    public function indexV($id)
    {
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

        try
        {
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

    public function indexP($id)
    {
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

        try
        {
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
            $videogameplat = videogamePlatform::create($request->all());

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

    public function update(Request $request, $platform_id, $videogame_id)
    {
        $videogameplat = videogamePlatform::where('platform_id', $platform_id)
        ->where('videogame_id', $videogame_id)
        ->firstOrFail();

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
            $videogameplat->update(['platform_id' => $request->platform_id]);

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
