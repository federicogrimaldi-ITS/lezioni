@extends('layouts.app')

@section('content')
<div class="card country-card">
    <p class="counter">Paese {{ $indice + 1 }} di {{ $totale }}</p>
    <img class="flag-preview" src="{{ $paese->flag_url }}" alt="Bandiera di {{ $paese->name_it }}">
    <h2>{{ $paese->name_it }}</h2>

    <dl class="country-facts">
        <dt>Capitale</dt>
        <dd>{{ $paese->capital }}</dd>

        <dt>Lingue</dt>
        <dd>{{ implode(', ', $paese->languages) }}</dd>

        <dt>Continente</dt>
        <dd>{{ $paese->regione_it }}{{ $paese->subregion ? " ({$paese->subregion})" : '' }}</dd>

        <dt>Popolazione</dt>
        <dd>{{ number_format($paese->population, 0, ',', '.') }} abitanti</dd>

        <dt>Valute</dt>
        <dd>{{ implode(', ', $paese->currencies) }}</dd>
    </dl>

    <form class="nav-form" method="get" action="{{ route('allenamento') }}">
        <a class="btn btn-secondary" href="{{ route('allenamento', ['i' => $indice - 1]) }}" @if($indice === 0) aria-disabled="true" style="pointer-events:none;opacity:0.4;" @endif>← Precedente</a>

        <select name="i" onchange="this.form.submit()">
            @foreach ($countries as $i => $c)
                <option value="{{ $i }}" @selected($i === $indice)>{{ $c->name_it }}</option>
            @endforeach
        </select>

        <a class="btn" href="{{ route('allenamento', ['i' => $indice + 1]) }}" @if($indice === $totale - 1) aria-disabled="true" style="pointer-events:none;opacity:0.4;" @endif>Successivo →</a>
    </form>
</div>
@endsection
