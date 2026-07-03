<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;
use App\Models\Pockemon;

class PockemonController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $title = 'Pokédex';
        $selectedType = $request->string('type')->toString();
        $types = $this->availableTypes();

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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id, ?string $slug = null)
    {
        $matches = Pockemon::query()
            ->where('Id', $id)
            ->get();

        abort_if($matches->isEmpty(), 404);

        $pockemon = $slug
            ? $matches->first(function ($item) use ($slug) {
                return Str::slug((string) $item->{'Name'}) === $slug;
            }) ?? $matches->first()
            : $matches->first();

        $apiPayload = $this->loadPokeApiData($pockemon);
        $imageUrl = data_get($apiPayload, 'sprites.other.official-artwork.front_default')
            ?? data_get($apiPayload, 'sprites.other.home.front_default')
            ?? data_get($apiPayload, 'sprites.front_default')
            ?? 'https://raw.githubusercontent.com/PokeAPI/sprites/master/sprites/pokemon/other/official-artwork/' . $pockemon->{'Id'} . '.png';

        $title = $pockemon->{'Name'};

        return view('pockemon.show', compact('title', 'pockemon', 'apiPayload', 'imageUrl'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    protected function availableTypes()
    {
        return Pockemon::query()
            ->pluck('Type 1')
            ->merge(Pockemon::query()->pluck('Type 2'))
            ->filter()
            ->unique()
            ->sort()
            ->values();
    }

    protected function loadPokeApiData(Pockemon $pockemon): array
    {
        foreach ($this->pokeApiCandidates($pockemon) as $candidate) {
            $response = Http::retry(1, 200)->timeout(6)->get('https://pokeapi.co/api/v2/pokemon/' . $candidate);

            if ($response->successful()) {
                return $response->json();
            }
        }

        return [];
    }

    protected function pokeApiCandidates(Pockemon $pockemon): array
    {
        $name = (string) $pockemon->{'Name'};
        $base = Str::slug($name);
        $id = (string) $pockemon->{'Id'};

        $candidates = [$base, $id];

        $normalizations = [
            'mega ' => 'mega-',
            'primal ' => 'primal-',
            'forme' => '',
            'forme ' => '',
            'female' => 'female',
            'male' => 'male',
        ];

        $candidateName = Str::of($name)->replace(array_keys($normalizations), array_values($normalizations))->toString();
        $candidates[] = Str::slug($candidateName);

        if (str_contains($name, 'Rotom')) {
            $candidates[] = 'rotom';
            foreach (['Heat' => 'heat', 'Wash' => 'wash', 'Frost' => 'frost', 'Fan' => 'fan', 'Mow' => 'mow'] as $label => $form) {
                if (str_contains($name, $label)) {
                    $candidates[] = 'rotom-' . $form;
                }
            }
        }

        if (str_contains($name, 'Deoxys')) {
            $candidates[] = 'deoxys-normal';
            foreach (['Attack' => 'attack', 'Defense' => 'defense', 'Speed' => 'speed'] as $label => $form) {
                if (str_contains($name, $label)) {
                    $candidates[] = 'deoxys-' . $form;
                }
            }
        }

        if (str_contains($name, 'Giratina')) {
            $candidates[] = str_contains($name, 'Origin') ? 'giratina-origin' : 'giratina-altered';
        }

        if (str_contains($name, 'Shaymin')) {
            $candidates[] = str_contains($name, 'Sky') ? 'shaymin-sky' : 'shaymin-land';
        }

        if (str_contains($name, 'Tornadus')) {
            $candidates[] = str_contains($name, 'Therian') ? 'tornadus-therian' : 'tornadus-incarnate';
        }

        if (str_contains($name, 'Thundurus')) {
            $candidates[] = str_contains($name, 'Therian') ? 'thundurus-therian' : 'thundurus-incarnate';
        }

        if (str_contains($name, 'Landorus')) {
            $candidates[] = str_contains($name, 'Therian') ? 'landorus-therian' : 'landorus-incarnate';
        }

        if (str_contains($name, 'Kyurem')) {
            if (str_contains($name, 'Black')) {
                $candidates[] = 'kyurem-black';
            } elseif (str_contains($name, 'White')) {
                $candidates[] = 'kyurem-white';
            } else {
                $candidates[] = 'kyurem';
            }
        }

        return array_values(array_unique(array_filter($candidates)));
    }
}
