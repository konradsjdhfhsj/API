<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Wettercontroller extends Controller
{
     public function wetter()
    {
        return view('wetter');
    }
}
