<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\orderDetail;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
 
class OrderDetailsController extends Controller
{
    public function index(Request $request)
    {
        $authenticatedUser = $request->user();
        DB::enableQueryLog();

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

        return response()->json($orderdit,200);
    }

    public function show(Request $request, $id)
    {
        $authenticatedUser = $request->user();
        DB::enableQueryLog();

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
            $queries = DB::getQueryLog();
            $sqlQuery = end($queries)['query'];

            RequestLog::create([
                'user_id' => $authenticatedUser ? $authenticatedUser->id : null, 
                'user_name' => $authenticatedUser ? $authenticatedUser->name : null,
                'user_email' => $authenticatedUser ? $authenticatedUser->email : null,
                'http_verb' => request()->method(),
                'route' => request()->path(),
                'query' => $sqlQuery, 
                'data' => json_encode($orderdit),
                'request_time' => now()->toDateTimeString()
            ]);
            return response()->json(['message:'=>'Orde encontrada',$orderdit],200);
        }
        return response()->json(['message:'=>'Orde no encontrada'],400);
    }

    public function store(Request $request)
    {
        $authenticatedUser = $request->user();
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

            DB::enableQueryLog();

            $orderdei=orderDetail::create([
                'order_id'=>$request->order_id,
                'videogame_id'=>$request->videogame_id,
                'quantity'=>$request->quantity,
                'totalPrice'=>$request->totalPrice,
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
                'data' => json_encode($orderdei),
                'request_time'=> now()->toDateTimeString()
            ]);

        return response()->json($orderdei,201);
    }
}
