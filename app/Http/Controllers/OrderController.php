<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        $orders = Order::with([
            'user:id,name',
            'shipper:id,name',
            'state:id,estado'
        ])->get();

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

    public function show($id)
    {
    $order=Order::where('user_id', $id)->get();
    if($order){
        return response()->json(['message' => 'Ordenes encontradas: ', $order], 200);
    }

    return response()->json(['message'=>'Ordenes no encontradas para el usuario con id: '.$id], 404);
    }
    public function store(Request $request)
    {
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
        return response()->json($order,201);
    }
    public function update(Request $request, $id)
    {
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
        $order->update($request->all());
        return response()->json($order,200);
    }
    return response()->json(['message'=>'Order no encontrada'], 404);
        }
}

