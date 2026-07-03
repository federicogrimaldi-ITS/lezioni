@extends('layouts.app')

@section('content')
<section class="page-shell">
    <aside class="sidebar">
        <div class="sidebar__hero">
            <span class="eyebrow">Pokédex</span>
            <h1>Elenco Pokémon</h1>
            <p>Filtra per tipo e apri la scheda dettagliata con immagine da PokeAPI.</p>
        </div>

        <div class="sidebar__stats">
            <article>
                <strong>{{ $summary['total'] }}</strong>
                <span>totali</span>
            </article>
            <article>
                <strong>{{ $summary['filtered'] }}</strong>
                <span>visibili</span>
            </article>
            <article>
                <strong>{{ $summary['legendary'] }}</strong>
                <span>legendary</span>
            </article>
        </div>

        <div class="type-filters">
            <a class="type-filter {{ $selectedType === '' ? 'is-active' : '' }}" href="{{ route('pockemons.index') }}">Tutti</a>
            @foreach($types as $type)
                <a class="type-filter {{ $selectedType === $type ? 'is-active' : '' }}" href="{{ route('pockemons.index', ['type' => $type]) }}">{{ $type }}</a>
            @endforeach
        </div>
    </aside>

    <section class="content-panel">
        <div class="section-heading">
            <div>
                <span class="eyebrow">Roster completo</span>
                <h2>{{ $selectedType ? 'Tipo ' . $selectedType : 'Tutti i Pokémon' }}</h2>
            </div>
            <p>{{ $summary['filtered'] }} risultati trovati.</p>
        </div>

        <div class="table-wrap">
            <table class="pokemon-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Nome</th>
                        <th>Tipo 1</th>
                        <th>Tipo 2</th>
                        <th>Totale</th>
                        <th>Gen</th>
                        <th>Azioni</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pockemons as $pockemon)
                        <tr>
                            <td>{{ $pockemon->{'Id'} }}</td>
                            <td class="pokemon-name-cell">
                                <a href="{{ route('pockemons.show', ['id' => $pockemon->{'Id'}, 'slug' => \Illuminate\Support\Str::slug($pockemon->{'Name'}), 'type' => $selectedType ?: null]) }}">
                                    {{ $pockemon->{'Name'} }}
                                </a>
                            </td>
                            <td><span class="type-chip type-chip--primary">{{ $pockemon->{'Type 1'} }}</span></td>
                            <td>
                                @if(!empty($pockemon->{'Type 2'}))
                                    <span class="type-chip">{{ $pockemon->{'Type 2'} }}</span>
                                @else
                                    <span class="table-empty">-</span>
                                @endif
                            </td>
                            <td>{{ $pockemon->{'Total'} }}</td>
                            <td>{{ $pockemon->{'Generation'} }}</td>
                            <td>
                                <a class="action-link" href="{{ route('pockemons.show', ['id' => $pockemon->{'Id'}, 'slug' => \Illuminate\Support\Str::slug($pockemon->{'Name'}), 'type' => $selectedType ?: null]) }}">Apri scheda</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-state-cell">
                                Nessun Pokémon per il filtro selezionato.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="pagination-wrap">
            {{ $pockemons->links() }}
        </div>
    </section>
</section>
@endsection
