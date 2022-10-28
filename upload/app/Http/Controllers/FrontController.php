<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class FrontController extends Controller
{
    public function index()
    {
        if (config('fpos.demo') && !auth()->check()){
            return view('pos');
        } else {
            return redirect()->route('home');
        }
    }
}
