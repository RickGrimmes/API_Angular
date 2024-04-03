<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use App\Models\Shipper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ShippersController extends Controller
{
    public function index(Request $request)
    {
        try
        {
            $authenticatedUser = $request->user();
            DB::enableQueryLog();

            $shipper = Shipper::all();

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
        $authenticatedUser = $request->user();

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
            DB::enableQueryLog();

            $shipper = Shipper::create($request->all());

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($shipper),
                'request_time'=> now()->toDateTimeString()
            ]);

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
        $authenticatedUser = $request->user();

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
            DB::enableQueryLog();
            
            $shipper->update($request->all());
            
            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($shipper),
                'request_time'=> now()->toDateTimeString()
            ]);
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

    public function destroy(Request $request, $id)
    {
        $authenticatedUser = $request->user();
        $shipper = Shipper::findOrFail($id);

        try
        {
            DB::enableQueryLog();

            $shipper->delete();

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($shipper),
                'request_time'=> now()->toDateTimeString()
            ]);

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
