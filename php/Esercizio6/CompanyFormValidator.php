<?php
/**
 * Classe CompanyFormValidator - Valida i dati della form dell'azienda
 */

class CompanyFormValidator {
    private $errors = [];
    private $data = [];

    public function validate($data) {
        $this->errors = [];
        $this->data = $data;

        $this->validateNome();
        $this->validateRagioneSociale();
        $this->validateIndirizzo();
        $this->validatePartitaIVA();

        return empty($this->errors);
    }

    private function validateNome() {
        $nome = trim($this->data['Nome'] ?? '');
        
        if (empty($nome)) {
            $this->errors['Nome'] = 'Il nome è obbligatorio.';
        } elseif (strlen($nome) < 2) {
            $this->errors['Nome'] = 'Il nome deve contenere almeno 2 caratteri.';
        } elseif (strlen($nome) > 100) {
            $this->errors['Nome'] = 'Il nome non può superare 100 caratteri.';
        }
    }

    private function validateRagioneSociale() {
        $ragioneSociale = trim($this->data['Ragionesociale'] ?? '');
        
        if (empty($ragioneSociale)) {
            $this->errors['Ragionesociale'] = 'La ragione sociale è obbligatoria.';
        } elseif (strlen($ragioneSociale) < 2) {
            $this->errors['Ragionesociale'] = 'La ragione sociale deve contenere almeno 2 caratteri.';
        } elseif (strlen($ragioneSociale) > 150) {
            $this->errors['Ragionesociale'] = 'La ragione sociale non può superare 150 caratteri.';
        }
    }

    private function validateIndirizzo() {
        $indirizzo = trim($this->data['Indirizzo'] ?? '');
        
        if (empty($indirizzo)) {
            $this->errors['Indirizzo'] = 'L\'indirizzo è obbligatorio.';
        } elseif (strlen($indirizzo) < 5) {
            $this->errors['Indirizzo'] = 'L\'indirizzo deve contenere almeno 5 caratteri.';
        } elseif (strlen($indirizzo) > 200) {
            $this->errors['Indirizzo'] = 'L\'indirizzo non può superare 200 caratteri.';
        }
    }

    private function validatePartitaIVA() {
        $partitaIVA = trim($this->data['Partitaiva'] ?? '');
        
        if (empty($partitaIVA)) {
            $this->errors['Partitaiva'] = 'La Partita IVA è obbligatoria.';
        } elseif (!preg_match('/^\d{11}$/', $partitaIVA)) {
            $this->errors['Partitaiva'] = 'La Partita IVA deve contenere esattamente 11 cifre numeriche.';
        }
    }

    public function getErrors() {
        return $this->errors;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getError($field) {
        return $this->errors[$field] ?? null;
    }
}
?>
