<?php

namespace App\Http\Controllers;

use App\Log;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class LogController extends Controller
{
    public function __construct() {
        $this->middleware('auth');
    }

    public function index()
    {
    	$logs = Log::latest()->get();

    	return view('log', compact('logs'));
    }
}
