<?php

namespace App\Http\Controllers;

use App\Models\Videogame;
use Illuminate\Http\Request;

class VideogamesController extends Controller
{
    public function index()
    {
        $videogame = Videogame::all();
        return response()->json($videogame);
    }

    public function create(Request $request)
    {

    }

    public function update(Request $request)
    {

    }

    public function delete()
    {

    }
}

// invitado nomas puede ver cosas pero nada mas, cliente puede ver, pero solo agregar y modificar
