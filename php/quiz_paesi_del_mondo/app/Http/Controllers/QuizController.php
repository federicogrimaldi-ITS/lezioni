<?php

namespace App\Http\Controllers;

use App\Domain\Quiz\GestoreDomande;
use App\Services\QuizService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class QuizController extends Controller
{
    public function __construct(private readonly QuizService $quiz)
    {
    }

    public function avvia(Request $request): RedirectResponse
    {
        $dati = $request->validate([
            'livello' => 'required|integer|between:1,3',
            'numDomande' => 'required|integer|between:3,30',
            'categoria' => 'nullable|in:' . implode(',', GestoreDomande::CATEGORIE),
        ]);

        $this->quiz->avvia($dati['livello'], $dati['numDomande'], $dati['categoria'] ?? null);

        return redirect()->route('quiz.show');
    }

    public function show()
    {
        if (!$this->quiz->isIniziato()) {
            return redirect()->route('home');
        }

        if ($this->quiz->isTerminato()) {
            return redirect()->route('quiz.risultato');
        }

        return view('quiz.show', [
            'title' => 'Quiz in corso',
            'domanda' => $this->quiz->getDomandaCorrente(),
            'indice' => $this->quiz->getIndice(),
            'totale' => $this->quiz->getTotaleDomande(),
            'punteggio' => $this->quiz->getPunteggio(),
            'feedback' => session('feedback'),
        ]);
    }

    public function rispondi(Request $request): RedirectResponse
    {
        $dati = $request->validate([
            'risposta' => 'required|string',
        ]);

        $this->quiz->rispondi($dati['risposta']);

        $storico = $this->quiz->getStorico();

        return redirect()->route('quiz.show')->with('feedback', end($storico));
    }

    public function risultato()
    {
        if (!$this->quiz->isIniziato() || !$this->quiz->isTerminato()) {
            return redirect()->route('home');
        }

        return view('quiz.risultato', [
            'title' => 'Risultato del quiz',
            'punteggio' => $this->quiz->getPunteggio(),
            'totale' => $this->quiz->getTotaleDomande(),
            'storico' => $this->quiz->getStorico(),
        ]);
    }
}
