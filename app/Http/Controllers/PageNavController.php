<?php

namespace App\Http\Controllers;

// use Illuminate\Http\Request;

class PageNavController extends Controller
{
    public function accueil(){
        return view('accueil');
    }

    public function redirection(){
        return redirect()->route('accueil');
    }
}
