<?php
/**
 * Esercizio 7 – Controllo formale Codice Fiscale
 */

require_once 'CodiceFiscaleValidator.php';

class CodiceFiscaleCheckerHandler {
    private $validator;
    private $submitted = false;
    private $codiceFiscale = '';

    public function __construct() {
        $this->validator = new CodiceFiscaleValidator();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->submitted = true;
            $this->codiceFiscale = trim($_POST['codicefiscale'] ?? '');
            $this->validator->setCodiceFiscale($this->codiceFiscale);
        }
    }

    public function isSubmitted() {
        return $this->submitted;
    }

    public function getCodiceFiscale() {
        return $this->codiceFiscale;
    }

    public function getValidator() {
        return $this->validator;
    }

    public function isValid() {
        return $this->submitted && $this->validator->isValid();
    }
}

$handler = new CodiceFiscaleCheckerHandler();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esercizio 7 - Controllo Codice Fiscale</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            max-width: 600px;
        }
        .form-container {
            background-color: #f9f9f9;
            padding: 20px;
            border-radius: 5px;
            border: 1px solid #ddd;
        }
        .form-group {
            margin: 15px 0;
        }
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
            color: #333;
        }
        input[type="text"],
        input[type="submit"] {
            padding: 10px;
            border-radius: 3px;
        }
        input[type="text"] {
            width: 100%;
            border: 1px solid #ccc;
            box-sizing: border-box;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-family: monospace;
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #2196F3;
            box-shadow: 0 0 5px rgba(33, 150, 243, 0.5);
        }
        input[type="submit"] {
            background-color: #2196F3;
            color: white;
            cursor: pointer;
            border: none;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #1976D2;
        }
        .result-success {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .result-error {
            background-color: #f8d7da;
            color: #721c24;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #f5c6cb;
        }
        .result-success h3,
        .result-error h3 {
            margin-top: 0;
        }
        .result-success ul,
        .result-error ul {
            margin: 10px 0;
            padding-left: 20px;
        }
        .result-success li,
        .result-error li {
            margin: 5px 0;
        }
        .info-box {
            background-color: #e3f2fd;
            padding: 15px;
            border-radius: 5px;
            border-left: 4px solid #2196F3;
            margin: 20px 0;
        }
        .info-box h4 {
            margin-top: 0;
            color: #1565c0;
        }
        .info-box p {
            margin: 5px 0;
            font-size: 14px;
        }
        .data-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin-top: 15px;
        }
        .data-item {
            background-color: #f0f0f0;
            padding: 10px;
            border-radius: 3px;
        }
        .data-item strong {
            display: block;
            color: #333;
            font-size: 12px;
            margin-bottom: 5px;
        }
        .data-item span {
            color: #2196F3;
            font-size: 16px;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <h1>Esercizio 7 – Controllo formale Codice Fiscale</h1>

    <div class="info-box">
        <h4>Formato Codice Fiscale Italiano</h4>
        <p>Il Codice Fiscale è un codice alfanumerico di 16 caratteri:</p>
        <p style="font-family: monospace; font-weight: bold;">XXXXXX 99 X 99 X 999 X</p>
        <ul style="margin: 10px 0; padding-left: 20px; font-size: 13px;">
            <li>6 caratteri: Cognome (consonanti)</li>
            <li>6 caratteri: Nome (consonanti)</li>
            <li>2 cifre: Anno di nascita</li>
            <li>1 lettera: Mese di nascita</li>
            <li>2 cifre: Giorno di nascita</li>
            <li>1 lettera: Comune di nascita</li>
            <li>3 cifre: Progressivo</li>
            <li>1 lettera: Carattere di controllo</li>
        </ul>
    </div>

    <?php if ($handler->isValid()): ?>
        <div class="result-success">
            <h3>✓ Codice Fiscale Valido</h3>
            <p>Il codice fiscale <strong><?php echo htmlspecialchars($handler->getCodiceFiscale()); ?></strong> è formalmente corretto.</p>
            
            <?php $info = $handler->getValidator()->extractInfo(); ?>
            <h4>Informazioni estratte:</h4>
            <div class="data-grid">
                <div class="data-item">
                    <strong>Giorno nascita</strong>
                    <span><?php echo $info['giorno_nascita']; ?></span>
                </div>
                <div class="data-item">
                    <strong>Mese nascita</strong>
                    <span><?php echo $info['mese_nascita']; ?></span>
                </div>
                <div class="data-item">
                    <strong>Anno nascita</strong>
                    <span><?php echo $info['anno_nascita']; ?></span>
                </div>
                <div class="data-item">
                    <strong>Sesso</strong>
                    <span><?php echo $info['sesso']; ?></span>
                </div>
            </div>
        </div>
    <?php endif; ?>

    <?php if ($handler->isSubmitted() && !$handler->isValid()): ?>
        <div class="result-error">
            <h3>✗ Codice Fiscale Non Valido</h3>
            <p>Il codice fiscale inserito contiene i seguenti errori:</p>
            <ul>
                <?php foreach ($handler->getValidator()->getErrors() as $errore): ?>
                    <li><?php echo htmlspecialchars($errore); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label for="codicefiscale">Inserisci Codice Fiscale:</label>
                <input type="text" id="codicefiscale" name="codicefiscale" value="<?php echo htmlspecialchars($handler->getCodiceFiscale()); ?>" placeholder="Es: RSSMRA90A70H501X" maxlength="16">
            </div>
            <div class="form-group">
                <input type="submit" value="Verifica">
            </div>
        </form>
    </div>

    <div class="info-box" style="margin-top: 20px;">
        <h4>Esempi di test:</h4>
        <p style="font-family: monospace; font-size: 13px; margin: 5px 0;">
            Valido: RSSMRA90A70H501X<br>
            (prova a inserirlo per test)
        </p>
    </div>
</body>
</html>
