<?php

namespace Interfaces\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class TestController extends Controller
{
    public function createJob(Request $request) 
    {
        Log::debug('Creating a test job');
        
    }
}