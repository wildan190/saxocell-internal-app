<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class LauncherController extends Controller
{
    public function index()
    {
        return view('launcher');
    }
}
