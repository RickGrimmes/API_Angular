<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class UsersController extends Controller
{

    public function index()
    {
        $user=User::all();
        return($user);
    }
    public function show($id)
    {
        $user = User::find($id);
        if($user){
            return response()->json(['message' => 'Usuario ecncontrado: ',$user], 200);
        }
    
        return response()->json(['message'=>'usuario no encontrado'], 404);
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name'=>'required|max:100|string',
            'email'=>'required|max:255|string|email|unique:'.User::class,
            'password'=>'required|max:100|string',
            'isActive'=>'max:100|string',
            'role_id'=>''
        ]);
    
        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }
  
       $user= User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'isActive'=>$request->isActive,
            'role_id' => $request->role_id
        ]);
        return response()->json($user, 201);

    }
    public function update(Request $request, $id)
{
    $validator = Validator::make($request->all(), [
        'name'=>'required|max:100|string',
        'email'=>'required|max:255|string|email|unique:'.User::class,
        'password'=>'max:100|string',
        'isActive'=>'max:100|string',
        'role_id'=>''
    ]);

    if ($validator->fails()) {
        return response()->json($validator->errors(), 400);
    }

    $user = User::find($id);
    $user->update($request->all());

    return response()->json($user, 200);
}

public function destroy($id)
{
    $user = User::find($id);

    if($user){
        $user->delete();
        return response()->json(['message' => 'Usuario eliminado: ',$user], 200);
    }

    return response()->json(['message'=>'usuario no encontrado'], 404);
}

}
