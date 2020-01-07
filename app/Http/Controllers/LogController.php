<?php

namespace App\Http\Controllers;

use App\Log;
use Illuminate\Http\Request;

class LogController extends Controller
{
    public function __construct(Log $log)
    {
        $this->log = $log;
    }

    public function index()
    {
        $logs = $this->log->get();

        return response()->json([
            'message' => 'Success',
            'data' => $logs
        ], 201); 
        
    }
}
