<?php

namespace App\Http\Controllers;

use App\Models\Genre;
use App\Models\RequestLog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class GenreController extends Controller
{
    public function index()
    {
        DB::enableQueryLog();

        $genre = Genre::all();

        $queries = DB::getQueryLog();
        $sqlQuery = end($queries)['query'];

        // Crear un registro en RequestLog
        RequestLog::create([
            'user_id' => null, // No hay usuario relacionado
            'user_name' => null,
            'user_email' => null,
            'http_verb' => request()->method(),
            'route' => request()->path(),
            'query' => $sqlQuery, // Query SQL ejecutado
            'data' => null,
            'request_time' => now()->toDateTimeString()
        ]);

        return response()->json([
            'status' => 'success',
            'data' => $genre
        ], 200);
    }
}
