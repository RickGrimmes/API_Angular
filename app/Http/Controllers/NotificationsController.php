<?php

namespace App\Http\Controllers;

use App\Models\Notifications;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class NotificationsController extends Controller
{
    public function index()
    {
        $users = User::all();

        return view('sse', compact('users'));
    }

    public function create(Request $request)
    {
        $authenticatedUser = $request->user();

        $tipo = $authenticatedUser->role_id;
        
        if ($tipo == 1)
        {
            $validator=Validator::make($request->all(),[
                'message'=>'required|max:1000|min:2',
            ]);
            if($validator->fails())
            {
                return response()->json($validator->errors(),400);
            }
    
            $notification=Notifications::create([
                'message' => $request->message,
                'user_id'=>$authenticatedUser->id,
            ]);
    
            return response()->json([
                'status' => 'success',
                'message' => 'Notificación exitosa',
                'data' => $notification
            ], 200);
        }
        else
        {
            return response()->json([
                'status' => 'error',
                'message' => 'Notificación fallida, no tienes autorización'
            ], 403);
        }
        
    }
}
