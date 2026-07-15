<?php

namespace App\Http\Controllers;

use App\Models\Country;
use Illuminate\Http\Request;

class AllenamentoController extends Controller
{
    public function show(Request $request)
    {
        $countries = Country::orderBy('name_it')->get();
        $totale = $countries->count();

        $indice = max(0, min($totale - 1, (int) $request->query('i', 0)));
        $paese = $countries[$indice];
        $title = "Allenamento - {$paese->name_it}";

        return view('allenamento.show', [
            'title' => $title,
            'countries' => $countries,
            'paese' => $paese,
            'indice' => $indice,
            'totale' => $totale,
        ]);
    }
}
