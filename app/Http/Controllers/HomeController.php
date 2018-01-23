<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{

    // authorization
    public function __construct()
    {
        $this->middleware('auth');
    }

    // show the home page
    // #return \Illuminate\Http\Response
    public function index()
    {
        return view('home');
    }
}
