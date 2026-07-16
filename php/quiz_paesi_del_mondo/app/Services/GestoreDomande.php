<?php

namespace App\Services;

use App\Entities\Country;
use App\Entities\Domanda;
use Illuminate\Support\Collection;
use InvalidArgumentException;

/**
 * Carica i paesi dal database e genera le domande del quiz
 * a partire dai loro dati (capitale, lingua, continente, valuta, bandiera, popolazione).
 */
class GestoreDomande
{
    public const CATEGORIE = ['capitale', 'lingua', 'continente', 'valuta', 'bandiera', 'popolazione'];

    /** @var Collection<int, Country> */
    private Collection $countries;

    public function __construct()
    {
        $this->countries = Country::all();
    }

    /** @return Domanda[] */
    public function generaDomande(int $numero, int $livello, ?string $categoria = null): array
    {
        $numOpzioni = self::numeroOpzioniPerLivello($livello);

        return collect(range(1, $numero))
            ->map(fn () => $this->generaDomandaSingola(
                $categoria ?? self::CATEGORIE[array_rand(self::CATEGORIE)],
                $numOpzioni,
                $livello
            ))
            ->all();
    }

    public static function numeroOpzioniPerLivello(int $livello): int
    {
        return match ($livello) {
            1 => 3,
            2 => 4,
            default => 5,
        };
    }

    private function generaDomandaSingola(string $categoria, int $numOpzioni, int $livello): Domanda
    {
        $paese = $this->countries->random();

        [$testo, $corretta, $estrattore, $immagineUrl] = match ($categoria) {
            'capitale' => [
                "Qual è la capitale di {$paese->name_it}?",
                $paese->capital,
                fn (Country $c) => $c->capital,
                null,
            ],
            'lingua' => [
                "Quale lingua si parla ufficialmente in {$paese->name_it}?",
                $paese->linguaCasuale(),
                fn (Country $c) => $c->linguaCasuale(),
                null,
            ],
            'continente' => [
                "In quale continente si trova {$paese->name_it}?",
                $paese->regione_it,
                fn (Country $c) => $c->regione_it,
                null,
            ],
            'valuta' => [
                "Qual è la valuta ufficiale di {$paese->name_it}?",
                $paese->valutaCasuale(),
                fn (Country $c) => $c->valutaCasuale(),
                null,
            ],
            'bandiera' => [
                'A quale paese appartiene questa bandiera?',
                $paese->name_it,
                fn (Country $c) => $c->name_it,
                $paese->flag_url,
            ],
            'popolazione' => [
                "Qual è la popolazione approssimativa di {$paese->name_it}?",
                $paese->popolazione_formattata,
                fn (Country $c) => $c->popolazione_formattata,
                null,
            ],
            default => throw new InvalidArgumentException("Categoria sconosciuta: {$categoria}"),
        };

        return new Domanda(
            testo: $testo,
            rispostaCorretta: $corretta,
            risposteErrate: $this->distrattoriUnivoci($paese, $corretta, $estrattore, $numOpzioni - 1),
            categoria: $categoria,
            livello: $livello,
            immagineUrl: $immagineUrl,
        );
    }

    /**
     * Pesca valori distinti (diversi dalla risposta corretta e tra loro) da altri paesi,
     * evitando che due opzioni del quiz coincidano per errore (es. "Euro" o "English"
     * condivisi da molti paesi).
     *
     * @return string[]
     */
    private function distrattoriUnivoci(Country $escludi, string $corretta, callable $estrattore, int $quantita): array
    {
        $pool = $this->countries
            ->reject(fn (Country $c) => $c->id === $escludi->id)
            ->shuffle();

        $distrattori = [];
        foreach ($pool as $candidato) {
            if (count($distrattori) >= $quantita) {
                break;
            }

            $valore = $estrattore($candidato);
            if ($valore !== $corretta && !in_array($valore, $distrattori, true)) {
                $distrattori[] = $valore;
            }
        }

        return $distrattori;
    }
}
