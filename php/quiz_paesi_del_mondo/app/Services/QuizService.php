<?php

namespace App\Services;

use App\Domain\Quiz\Domanda;
use App\Domain\Quiz\GestoreDomande;
use Illuminate\Support\Facades\Session;
use LogicException;

/**
 * Gestisce la logica del quiz appoggiandosi alla sessione Laravel per mantenere
 * lo stato tra una richiesta e l'altra: punteggio corrente, numero della domanda,
 * risposte già date, livello scelto dall'utente.
 */
class QuizService
{
    private const CHIAVE = 'quiz';

    public function __construct(private readonly GestoreDomande $gestoreDomande)
    {
    }

    public function avvia(int $livello, int $totaleDomande, ?string $categoria = null): void
    {
        $domande = $this->gestoreDomande->generaDomande($totaleDomande, $livello, $categoria);

        Session::put(self::CHIAVE, [
            'domande' => array_map(fn (Domanda $d) => $d->toArray(), $domande),
            'indice' => 0,
            'punteggio' => 0,
            'livello' => $livello,
            'totaleDomande' => $totaleDomande,
            'storico' => [],
            'iniziato' => true,
        ]);
    }

    public function reset(): void
    {
        Session::forget(self::CHIAVE);
    }

    public function isIniziato(): bool
    {
        return (bool) Session::get(self::CHIAVE . '.iniziato', false);
    }

    public function isTerminato(): bool
    {
        return $this->getIndice() >= $this->getTotaleDomande();
    }

    public function getDomandaCorrente(): ?Domanda
    {
        if ($this->isTerminato()) {
            return null;
        }

        $dati = Session::get(self::CHIAVE . '.domande')[$this->getIndice()];

        return Domanda::fromArray($dati);
    }

    public function rispondi(string $risposta): bool
    {
        $domanda = $this->getDomandaCorrente();
        if ($domanda === null) {
            throw new LogicException('Il quiz è già terminato.');
        }

        $corretta = $domanda->isCorretta($risposta);

        if ($corretta) {
            Session::increment(self::CHIAVE . '.punteggio');
        }

        Session::push(self::CHIAVE . '.storico', [
            'domanda' => $domanda->testo,
            'rispostaData' => $risposta,
            'rispostaCorretta' => $domanda->rispostaCorretta,
            'corretta' => $corretta,
        ]);

        Session::increment(self::CHIAVE . '.indice');

        return $corretta;
    }

    public function getIndice(): int
    {
        return (int) Session::get(self::CHIAVE . '.indice', 0);
    }

    public function getTotaleDomande(): int
    {
        return (int) Session::get(self::CHIAVE . '.totaleDomande', 0);
    }

    public function getPunteggio(): int
    {
        return (int) Session::get(self::CHIAVE . '.punteggio', 0);
    }

    public function getLivello(): int
    {
        return (int) Session::get(self::CHIAVE . '.livello', 1);
    }

    public function getStorico(): array
    {
        return Session::get(self::CHIAVE . '.storico', []);
    }
}
