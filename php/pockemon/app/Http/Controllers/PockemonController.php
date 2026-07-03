<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Pockemon;

class PockemonController extends Controller
{
    public function index(Request $request)
    {
        $title = 'Pokédex';
        $selectedType = $request->string('type')->toString();
        $types = $this->getAvailableTypes();

        $pockemons = Pockemon::query()
            ->when($selectedType !== '', function ($query) use ($selectedType) {
                $query->where(function ($subQuery) use ($selectedType) {
                    $subQuery->where('Type 1', $selectedType)
                        ->orWhere('Type 2', $selectedType);
                });
            })
            ->orderBy('Generation')
            ->orderByDesc('Total')
            ->orderBy('Id')
            ->paginate(24)
            ->withQueryString();

        $summary = [
            'total' => Pockemon::count(),
            'filtered' => $pockemons->total(),
            'legendary' => Pockemon::where('Legendary', 'True')->count(),
        ];

        return view('pockemon.index', compact('title', 'pockemons', 'types', 'selectedType', 'summary'));
    }

    public function show(string $id)
    {
        $pockemon = Pockemon::where('Id', $id)->firstOrFail();
        $imageUrl = $this->getPokeApiImage($pockemon);
        $title = $pockemon->{'Name'};

        return view('pockemon.show', compact('title', 'pockemon', 'imageUrl'));
    }

    private function getAvailableTypes()
    {
        return Pockemon::query()
            ->pluck('Type 1')
            ->merge(Pockemon::query()->pluck('Type 2'))
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    private function getPokeApiImage(Pockemon $pockemon): string
    {
        $candidates = [
            Str::slug($pockemon->{'Name'}),
            $pockemon->{'Id'},
        ];

        foreach ($candidates as $candidate) {
            $response = Http::timeout(6)->get("https://pokeapi.co/api/v2/pokemon/{$candidate}");
            
            if ($response->successful()) {
                $data = $response->json();
                return $data['sprites']['other']['official-artwork']['front_default'] 
                    ?? $data['sprites']['front_default']
                    ?? '';
            }
        }

        return '';
    }
}
