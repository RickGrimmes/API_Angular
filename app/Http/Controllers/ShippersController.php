<?php

namespace App\Http\Controllers;

use App\Models\Shipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ShippersController extends Controller
{
    public function index()
    {
        try
        {
            $shipper = Shipper::all();
            return response()->json([
                'status' => 'success',
                'data' => $shipper
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los shippers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|min:5|max:100',
            'direccion' => 'required|string',
            'email_contacto' => 'required|email|unique:shippers'
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
            $shipper = Shipper::create($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $shipper
            ], 201);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los shippers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $shipper = Shipper::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'name' => 'string|min:5|max:100',
            'direccion' => 'string',
            'email_contacto' => 'email|unique:shippers'
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
            $shipper->update($request->all());

            return response()->json([
                'status' => 'success',
                'data' => $shipper
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los shippers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }

    public function destroy($id)
    {
        $shipper = Shipper::findOrFail($id);

        try
        {
            $shipper->delete();
            return response()->json([
                'status' => 'success',
                'message' => 'Shipper eliminado'
            ], 200);
        }
        catch (\Exception $e)
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Error al obtener los shippers',
                'error' => $e->getMessage(),
            ], 500);
        }
    }
}
