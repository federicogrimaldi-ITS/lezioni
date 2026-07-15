<?php

namespace App\Domain\Quiz;

/**
 * Rappresenta una singola domanda del quiz a risposta multipla.
 */
class Domanda
{
    /** @param string[] $risposteErrate */
    public function __construct(
        public readonly string $testo,
        public readonly string $rispostaCorretta,
        public readonly array $risposteErrate,
        public readonly string $categoria,
        public readonly int $livello,
        public readonly ?string $immagineUrl = null,
    ) {
    }

    /** Restituisce tutte le opzioni (corretta + errate) in ordine casuale. */
    public function opzioni(): array
    {
        $opzioni = [...$this->risposteErrate, $this->rispostaCorretta];
        shuffle($opzioni);

        return $opzioni;
    }

    public function isCorretta(string $risposta): bool
    {
        return trim($risposta) === trim($this->rispostaCorretta);
    }

    /**
     * La sessione Laravel serializza in JSON (per evitare i rischi di sicurezza
     * della deserializzazione di oggetti PHP): questi due metodi permettono di
     * salvare e ricostruire la domanda come semplice array associativo.
     */
    public function toArray(): array
    {
        return [
            'testo' => $this->testo,
            'rispostaCorretta' => $this->rispostaCorretta,
            'risposteErrate' => $this->risposteErrate,
            'categoria' => $this->categoria,
            'livello' => $this->livello,
            'immagineUrl' => $this->immagineUrl,
        ];
    }

    public static function fromArray(array $dati): self
    {
        return new self(
            testo: $dati['testo'],
            rispostaCorretta: $dati['rispostaCorretta'],
            risposteErrate: $dati['risposteErrate'],
            categoria: $dati['categoria'],
            livello: $dati['livello'],
            immagineUrl: $dati['immagineUrl'],
        );
    }
}
