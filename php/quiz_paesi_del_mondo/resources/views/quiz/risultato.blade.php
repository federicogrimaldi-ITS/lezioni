@php
    $percentuale = $totale > 0 ? (int) round(($punteggio / $totale) * 100) : 0;
@endphp

@extends('layouts.app')

@section('content')
<div class="card" style="text-align: center;">
    <h2>Quiz completato!</h2>
    <div class="score-circle">
        <span class="num">{{ $punteggio }}/{{ $totale }}</span>
        <span class="den">{{ $percentuale }}%</span>
    </div>
    <p>Hai risposto correttamente a {{ $punteggio }} domande su {{ $totale }}.</p>

    <div class="btn-row">
        <a class="btn btn-secondary" href="{{ route('home') }}">Torna alla home</a>
        <a class="btn" href="{{ route('allenamento') }}">Ripassa i paesi</a>
    </div>
</div>

<div class="card">
    <h2>Riepilogo risposte</h2>
    <ul class="history">
        @foreach ($storico as $voce)
            <li>
                <span class="tag {{ $voce['corretta'] ? 'ok' : 'ko' }}">{{ $voce['corretta'] ? 'OK' : 'KO' }}</span>
                {{ $voce['domanda'] }}
                <br>
                <small>
                    Hai risposto: <strong>{{ $voce['rispostaData'] }}</strong>
                    @unless ($voce['corretta'])
                        — corretta: <strong>{{ $voce['rispostaCorretta'] }}</strong>
                    @endunless
                </small>
            </li>
        @endforeach
    </ul>
</div>
@endsection
