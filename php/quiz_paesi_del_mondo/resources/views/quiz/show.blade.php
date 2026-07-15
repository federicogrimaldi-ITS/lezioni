@php
    $progresso = (int) round(($indice / $totale) * 100);
@endphp

@extends('layouts.app')

@section('content')
<div class="card">
    <div class="progress-label">
        <span>Domanda {{ $indice + 1 }} di {{ $totale }}</span>
        <span>Punteggio: {{ $punteggio }}</span>
    </div>
    <div class="progress">
        <div class="progress-bar" style="width: {{ $progresso }}%"></div>
    </div>

    @if ($feedback)
        <div class="feedback {{ $feedback['corretta'] ? 'ok' : 'ko' }}">
            @if ($feedback['corretta'])
                ✅ Corretto! La risposta era: {{ $feedback['rispostaCorretta'] }}
            @else
                ❌ Sbagliato. La risposta corretta era: {{ $feedback['rispostaCorretta'] }}
            @endif
        </div>
    @endif

    @if ($domanda->immagineUrl)
        <img class="flag-preview" src="{{ $domanda->immagineUrl }}" alt="Bandiera da indovinare">
    @endif

    <p class="question-text">{{ $domanda->testo }}</p>

    <div class="options">
        @foreach ($domanda->opzioni() as $opzione)
            <form method="post" action="{{ route('quiz.rispondi') }}">
                @csrf
                <input type="hidden" name="risposta" value="{{ $opzione }}">
                <button type="submit" class="option-btn">{{ $opzione }}</button>
            </form>
        @endforeach
    </div>
</div>
@endsection
