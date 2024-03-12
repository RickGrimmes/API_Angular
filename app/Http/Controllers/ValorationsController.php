<?php

namespace App\Http\Controllers;

use App\Models\Valoration;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ValorationsController extends Controller
{
    public function index()
    {
        try
        {
            $valoration = Valoration::all();

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
            $valoration = Valoration::create($request->all());

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
            $valoration->update(['estrellas' => $request->estrellas]);

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
