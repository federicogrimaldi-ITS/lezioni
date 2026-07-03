<?php
/**
 * Classe CodiceFiscaleValidator - Valida il Codice Fiscale italiano
 */

class CodiceFiscaleValidator {
    /**
     * Verifica la validità formale del Codice Fiscale italiano
     * 
     * Il Codice Fiscale è composto da:
     * - 6 caratteri dalle consonanti del cognome
     * - 6 caratteri dalle consonanti del nome
     * - 2 cifre dell'anno di nascita (ultimi 2 digit)
     * - 1 lettera del mese di nascita
     * - 2 cifre del giorno di nascita (con cifra dispari per donne)
     * - 1 lettera (carattere del comune di nascita)
     * - 3 cifre progressivo
     * - 1 carattere di controllo (check digit)
     */

    private $codiceFiscale;
    private $errori = [];

    public function __construct($codiceFiscale = '') {
        $this->codiceFiscale = strtoupper(trim($codiceFiscale));
    }

    public function setCodiceFiscale($codiceFiscale) {
        $this->codiceFiscale = strtoupper(trim($codiceFiscale));
        $this->errori = [];
    }

    public function isValid() {
        $this->errori = [];
        
        // Lunghezza
        if (strlen($this->codiceFiscale) !== 16) {
            $this->errori[] = 'La lunghezza deve essere esattamente 16 caratteri.';
            return false;
        }

        // Formato generale
        if (!preg_match('/^[A-Z]{6}[\d]{2}[A-Z][\d]{2}[A-Z][\d]{3}[A-Z]$/', $this->codiceFiscale)) {
            $this->errori[] = 'Formato non valido. Il codice fiscale deve seguire lo schema: XXXXXX##X##X###X';
            return false;
        }

        // Check digit
        if (!$this->isCheckDigitValid()) {
            $this->errori[] = 'Il carattere di controllo (check digit) non è corretto.';
            return false;
        }

        return true;
    }

    private function isCheckDigitValid() {
        $dispari = 'BAFHJNPRTVCZSQWERTYUIOPMLKDXC';
        $pari = 'ACZSE3456789012B4DFG';

        $somma = 0;
        for ($i = 0; $i < 15; $i++) {
            $carattere = $this->codiceFiscale[$i];
            
            if (is_numeric($carattere)) {
                if (($i + 1) % 2 == 1) { // posizione dispari
                    $somma += intval($carattere);
                } else { // posizione pari
                    $somma += intval($carattere) * 2;
                    if ($somma % 10 != 0) {
                        $somma = intval($somma / 10) + ($somma % 10);
                    }
                }
            } else {
                if (($i + 1) % 2 == 1) { // posizione dispari
                    $somma += strpos($dispari, $carattere);
                } else { // posizione pari
                    $somma += strpos($pari, $carattere);
                }
            }
        }

        $resto = $somma % 26;
        $checkChar = chr(65 + $resto); // Converte a lettere maiuscole (A=65)

        return $checkChar === $this->codiceFiscale[15];
    }

    public function hasErrors() {
        return !empty($this->errori);
    }

    public function getErrors() {
        return $this->errori;
    }

    public function getCodiceFiscale() {
        return $this->codiceFiscale;
    }

    public function extractInfo() {
        if (!$this->isValid()) {
            return false;
        }

        $mesiNascita = [
            'A' => 'Gennaio',   'B' => 'Febbraio',  'C' => 'Marzo',
            'D' => 'Aprile',    'E' => 'Maggio',    'H' => 'Giugno',
            'L' => 'Luglio',    'M' => 'Agosto',    'P' => 'Settembre',
            'R' => 'Ottobre',   'S' => 'Novembre',  'T' => 'Dicembre'
        ];

        $anno = substr($this->codiceFiscale, 6, 2);
        $annoCompleto = intval($anno) <= intval(substr(date('Y'), 2)) ? '20' . $anno : '19' . $anno;
        
        $mese = $mesiNascita[$this->codiceFiscale[8]];
        $giorno = intval(substr($this->codiceFiscale, 9, 2));
        
        // Correzione per donne (giorni > 40)
        if ($giorno > 40) {
            $giorno -= 40;
            $sesso = 'Donna';
        } else {
            $sesso = 'Uomo';
        }

        return [
            'cognome_hint' => substr($this->codiceFiscale, 0, 6),
            'nome_hint' => substr($this->codiceFiscale, 6, 3),
            'anno_nascita' => $annoCompleto,
            'mese_nascita' => $mese,
            'giorno_nascita' => str_pad($giorno, 2, '0', STR_PAD_LEFT),
            'sesso' => $sesso,
            'comune_nascita_hint' => substr($this->codiceFiscale, 11, 1)
        ];
    }
}
?>
