@extends('layouts.app')

@section('content')
<section class="detail-shell">
    <a class="back-link" href="{{ route('pockemons.index', ['type' => request('type')]) }}">Torna alla lista</a>

    <div class="detail-card">
        <div class="detail-card__media">
            <img src="{{ $imageUrl }}" alt="{{ $pockemon->{'Name'} }}" loading="lazy">
        </div>

        <div class="detail-card__body">
            <span class="eyebrow">Pokémon detail</span>
            <h1>{{ $pockemon->{'Name'} }}</h1>

            <div class="type-row">
                <span class="type-chip type-chip--primary">{{ $pockemon->{'Type 1'} }}</span>
                @if(!empty($pockemon->{'Type 2'}))
                    <span class="type-chip">{{ $pockemon->{'Type 2'} }}</span>
                @endif
                @if($pockemon->{'Legendary'} === 'True')
                    <span class="type-chip type-chip--accent">Legendary</span>
                @endif
            </div>

            <div class="detail-grid">
                <div>
                    <span>Id</span>
                    <strong>{{ $pockemon->{'Id'} }}</strong>
                </div>
                <div>
                    <span>Generation</span>
                    <strong>{{ $pockemon->{'Generation'} }}</strong>
                </div>
                <div>
                    <span>Total</span>
                    <strong>{{ $pockemon->{'Total'} }}</strong>
                </div>
                <div>
                    <span>Speed</span>
                    <strong>{{ $pockemon->{'Speed'} }}</strong>
                </div>
            </div>

            <div class="detail-stats">
                @foreach([
                    'HP' => $pockemon->{'HP'},
                    'Attack' => $pockemon->{'Attack'},
                    'Defense' => $pockemon->{'Defense'},
                    'Sp. Atk' => $pockemon->{'Sp. Atk'},
                    'Sp. Def' => $pockemon->{'Sp. Def'},
                ] as $label => $value)
                    <div class="detail-stat">
                        <span>{{ $label }}</span>
                        <strong>{{ $value }}</strong>
                    </div>
                @endforeach
            </div>

            <div class="api-note">
                Immagine caricata da PokeAPI.
            </div>
        </div>
    </div>
</section>
@endsection