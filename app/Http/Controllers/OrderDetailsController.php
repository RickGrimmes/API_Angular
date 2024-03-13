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
        $orderdit=orderDetail::all();
        return response()->json($orderdit,200);
    }
    public function show($id)
    {
        $orderdita=orderDetail::where('order_id',$id)->get();
        if($orderdita)
        {
            return response()->json(['message:'=>'Orde encontrada',$orderdita],200);
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
