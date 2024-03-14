<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\orderDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderDetailsController extends Controller
{
    public function index()
    {
        $orderdits = orderDetail::with([
            'videogame:id,nombre'
        ])->get();

        $orderdit = $orderdits->map(function ($orderdit)
        {
            return [
                'id' => $orderdit->id,
                'order_id' => $orderdit->order_id,
                'videogame_name' => $orderdit->videogame->nombre,
                'quantity' => $orderdit->quantity,
                'totalPrice' => $orderdit->totalPrice,
                'updated_at' => $orderdit->updated_at,
                'created_at' => $orderdit->created_at
            ];
        });

        return response()->json($orderdit,200);
    }

    public function show($id)
    {
        $orderdits=orderDetail::with([
            'videogame:id,nombre'
        ])->where('order_id',$id)
        ->get();

        $orderdit = $orderdits->map(function ($orderdit)
        {
            return [
                'id' => $orderdit->id,
                'order_id' => $orderdit->order_id,
                'videogame_name' => $orderdit->videogame->nombre,
                'quantity' => $orderdit->quantity,
                'totalPrice' => $orderdit->totalPrice,
                'updated_at' => $orderdit->updated_at,
                'created_at' => $orderdit->created_at
            ];
        });

        if($orderdit)
        {
            return response()->json(['message:'=>'Orde encontrada',$orderdit],200);
        }
        return response()->json(['message:'=>'Orde no encontrada'],400);
    }

    public function store(Request $request)
    {
$validator=Validator::make($request->all(),[
    'order_id'=>'required|numeric',
    'videogame_id'=>'required|numeric',
    'quantity'=>'required|numeric',
    'totalPrice'=>'required|numeric',
]);
if($validator->fails())
        {
            return response()->json($validator->errors(),400);
        }
        $orderdei=orderDetail::create([
        'order_id'=>$request->order_id,
        'videogame_id'=>$request->videogame_id,
        'quantity'=>$request->quantity,
        'totalPrice'=>$request->totalPrice,
    ]);
    return response()->json($orderdei,201);
    }
}
