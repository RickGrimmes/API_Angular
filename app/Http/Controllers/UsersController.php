<?php

namespace App\Http\Controllers;

use App\Models\User;
use GuzzleHttp\Psr7\Response;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules;
use Inertia\Inertia;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Auth;

class UsersController extends Controller
{

    public function index()
    {
        $user=User::all();
        return($user);
    }
    public function indexin($id)
    {
        $user=User::find($id);
        return($user);
    }
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'required|max:100|string',
            'email'=>'required|max:255|string|email||unique:'.User::class,
            'password'=>'required|max:100|string',
            'isActive'=>'required|max:100|string',
            'role_id'=>''
        ]);
  
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'isActive' =>$request->isActive,
            'role_id' => $request->role_id
        ]);
        return($request);
    }
    public function update(Request $request, $id)
{
    $user = User::find($id);
    $request->validate([
        'name'=>'required|max:100|string',
        'email' => 'max:255|string|email|unique:users,email,' . $user->id,
        'password'=>'required|max:100|string',
        'isActive'=>'required|max:100|string',
        'role_id'=>''
    ]);

    $user = User::find($id);
    $user->update($request->all());

    return response()->json($user, 200);
}

public function destroy($id)
{
    $user = User::find($id);

    if($user){
        $user->delete();
        return response()->json(null, 204);
    }

    return response()->json(null, 404);
}

}
