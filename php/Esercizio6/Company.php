<?php
/**
 * Classe Company - Rappresenta un'azienda
 */

class Company {
    private $nome;
    private $ragioneSociale;
    private $indirizzo;
    private $partitaIVA;

    public function __construct($nome = '', $ragioneSociale = '', $indirizzo = '', $partitaIVA = '') {
        $this->nome = $nome;
        $this->ragioneSociale = $ragioneSociale;
        $this->indirizzo = $indirizzo;
        $this->partitaIVA = $partitaIVA;
    }

    public function setNome($nome) {
        $this->nome = trim($nome);
    }

    public function setRagioneSociale($ragioneSociale) {
        $this->ragioneSociale = trim($ragioneSociale);
    }

    public function setIndirizzo($indirizzo) {
        $this->indirizzo = trim($indirizzo);
    }

    public function setPartitaIVA($partitaIVA) {
        $this->partitaIVA = trim($partitaIVA);
    }

    public function getNome() {
        return $this->nome;
    }

    public function getRagioneSociale() {
        return $this->ragioneSociale;
    }

    public function getIndirizzo() {
        return $this->indirizzo;
    }

    public function getPartitaIVA() {
        return $this->partitaIVA;
    }

    public function toArray() {
        return [
            'Nome' => $this->nome,
            'Ragione Sociale' => $this->ragioneSociale,
            'Indirizzo' => $this->indirizzo,
            'Partita IVA' => $this->partitaIVA
        ];
    }
}
?>
