<?php

namespace App\Http\Controllers;
 
use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $authenticatedUser = $request->user();
        DB::enableQueryLog();

        $id = $authenticatedUser->id;
        $tipo = $authenticatedUser->role_id;

        if ($tipo == 1)
        {
            $orders = Order::with([
                'user:id,name',
                'shipper:id,name',
                'state:id,estado'
            ])->where('state_id', '<', 4)->get();
        }
        else {
            $orders = Order::with([
                'user:id,name',
                'shipper:id,name',
                'state:id,estado'
            ])
            ->where('state_id', '<', 4)
            ->where('user_id', '=', $id)->get();
        }

        $order = $orders->map(function ($order) {
            return [
                'id' => $order->id,
                'user_name' => $order->user->name,
                'shipper_name' => $order->shipper->name,
                'state' => $order->state->estado,
                'updated_at' => $order->updated_at,
                'created_at' => $order->created_at,
            ];
        });

        if($order){
            
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
                'message' => 'Order ecncontradas: ',
                $order
            ], 200);
        }
        
        if ($order->isEmpty())
        {
            return response()->json(['message'=>'Orders no encontradas'], 404);
        }
    }

    public function show(Request $request, $id)
    {
        $authenticatedUser = $request->user();
        DB::enableQueryLog();

        $order=Order::where('user_id', $id)->get();

        $queries = DB::getQueryLog();
        if($order){
            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($queries), 
                'data' => json_encode($order),
                'request_time'=> now()->toDateTimeString()
            ]);
            return response()->json(['message' => 'Ordenes encontradas: ', $order], 200);
        }

        return response()->json(['message'=>'Ordenes no encontradas para el usuario con id: '.$id], 404);
    }
    public function store(Request $request)
    {
        // que agarre el id del usaurio autenticado en vez del id del request
        $authenticatedUser = $request->user();

        DB::enableQueryLog();

        $validator=Validator::make($request->all(),[
            'user_id'=>'required|numeric',
            'shipper_id'=>'required|numeric',
            'state_id'=>'required|numeric'
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(),400);
        }

        $order=Order::create([
            'user_id'=>$request->user_id,
            'shipper_id'=>$request->shipper_id,
            'state_id'=>$request->state_id
        ]);
        
        $queries = DB::getQueryLog();
        $querie = end($queries)['query'];

        RequestLog::create([
            'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
            'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
            'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
            'http_verb' => request()->method(),
            'route' => request()->path(),
            'query' => json_encode($querie), 
            'data' => json_encode($order),
            'request_time'=> now()->toDateTimeString()
        ]);
        
        return response()->json($order,201);
    }

    public function update(Request $request, $id)
    {
        $authenticatedUser = $request->user();
        $order=Order::find($id);
        if($order)
        {
        $validator=Validator::make($request->all(),[
            'state_id'=>'required|numeric'
        ]);
        if($validator->fails())
        {
            return response()->json($validator->errors(),400);
        }    
        DB::enableQueryLog();

        $order->update(['state_id' => $request->state_id]);

        $queries = DB::getQueryLog();
        $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($order),
                'request_time'=> now()->toDateTimeString()
            ]);
        return response()->json($order,200);
        }
        return response()->json(['message'=>'Order no encontrada'], 404);
    }

    public function destroy(Request $request, $id)
    {
        $authenticatedUser = $request->user();
        $order = Order::findOrFail($id);

        try
        {
            DB::enableQueryLog();
            
            $order->update(['state_id' => 4]);

            $queries = DB::getQueryLog();
            $querie = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null,
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => json_encode($querie), 
                'data' => json_encode($order),
                'request_time'=> now()->toDateTimeString()
            ]);

            return response()->json([
                'status' => 'success',
                'data' => $order
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

    // metodo para eliminar, en el get solo muestro los que no están cancelados
}

