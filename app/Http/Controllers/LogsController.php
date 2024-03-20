<?php

namespace App\Http\Controllers;

use App\Models\RequestLog;
use Illuminate\Http\Request;

class LogsController extends Controller
{
    public function index()
    {
        $logs = RequestLog::all();
        return response()->json([
            'state' => 'succes',
            'data' => $logs
        ], 200);
    }
}
