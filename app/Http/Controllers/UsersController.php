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
    public function store(Request $request)
    {
        $request->validate([
            'name'=>'max:100|string',
            'email'=>'max:255|string|email|',
            'password'=>'max:100|string',
            'isActive'=>'max:100|string',
            'role_id'=>''
        ]);
  
        User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' =>$request->password,
            'isActive' =>$request->isActive,
            'role_id' => $request->role_id
        ]);
        return($request);
    }
}
