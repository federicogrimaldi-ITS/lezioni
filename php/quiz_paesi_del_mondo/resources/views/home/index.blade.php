@extends('layouts.app')

@section('content')
<div class="mode-choice">
    <a class="mode-card" href="{{ route('allenamento') }}">
        <span class="emoji">📖</span>
        <strong>Allenamento</strong>
        <p>Sfoglia i paesi uno alla volta per memorizzare capitali, lingue e bandiere.</p>
    </a>
    <a class="mode-card" href="#quiz-form">
        <span class="emoji">🎯</span>
        <strong>Quiz</strong>
        <p>Mettiti alla prova con domande a risposta multipla.</p>
    </a>
</div>

<div class="card" id="quiz-form">
    <h2>Configura il quiz</h2>
    <form class="settings-form" action="{{ route('quiz.avvia') }}" method="post">
        @csrf

        <fieldset>
            <label for="livello">Livello di difficoltà</label>
            <select name="livello" id="livello">
                <option value="1">Facile (3 opzioni)</option>
                <option value="2" selected>Medio (4 opzioni)</option>
                <option value="3">Difficile (5 opzioni)</option>
            </select>

            <label for="numDomande">Numero di domande</label>
            <input type="number" name="numDomande" id="numDomande" value="10" min="3" max="30">

            <label for="categoria">Categoria</label>
            <select name="categoria" id="categoria">
                <option value="">Tutte le categorie (mix casuale)</option>
                @foreach ($categorie as $categoria)
                    <option value="{{ $categoria }}">{{ ucfirst($categoria) }}</option>
                @endforeach
            </select>
        </fieldset>

        <div class="btn-row">
            <span></span>
            <button type="submit" class="btn">Inizia il quiz</button>
        </div>
    </form>
</div>
@endsection
