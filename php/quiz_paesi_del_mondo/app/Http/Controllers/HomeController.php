<?php

namespace App\Http\Controllers;

use App\Services\GestoreDomande;

class HomeController extends Controller
{
    public function index()
    {
        $title = 'Quiz Paesi del Mondo';
        $categorie = GestoreDomande::CATEGORIE;

        return view('home.index', compact('title', 'categorie'));
    }
}
