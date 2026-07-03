<?php
/**
 * Esercizio 6 – Inserimento dati azienda
 */

require_once 'Company.php';
require_once 'CompanyFormValidator.php';

class CompanyFormHandler {
    private $company;
    private $validator;
    private $submitted = false;
    private $isValid = false;

    public function __construct() {
        $this->validator = new CompanyFormValidator();
        $this->company = new Company();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->submitted = true;
            $this->handleSubmission();
        }
    }

    private function handleSubmission() {
        if ($this->validator->validate($_POST)) {
            $this->company->setNome($_POST['Nome'] ?? '');
            $this->company->setRagioneSociale($_POST['Ragionesociale'] ?? '');
            $this->company->setIndirizzo($_POST['Indirizzo'] ?? '');
            $this->company->setPartitaIVA($_POST['Partitaiva'] ?? '');
            $this->isValid = true;
        }
    }

    public function isSubmitted() {
        return $this->submitted;
    }

    public function isValid() {
        return $this->isValid;
    }

    public function getValidator() {
        return $this->validator;
    }

    public function getCompany() {
        return $this->company;
    }

    public function getPostValue($field) {
        return htmlspecialchars($_POST[$field] ?? '', ENT_QUOTES, 'UTF-8');
    }
}

$formHandler = new CompanyFormHandler();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esercizio 6 - Inserimento Dati Azienda</title>
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
        }
        input[type="text"]:focus {
            outline: none;
            border-color: #4CAF50;
            box-shadow: 0 0 5px rgba(76, 175, 80, 0.5);
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            cursor: pointer;
            border: none;
            width: 100%;
            font-size: 16px;
            font-weight: bold;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
        .error-message {
            color: #c00;
            font-size: 12px;
            margin-top: 3px;
        }
        .form-group.error input {
            border-color: #f44336;
            background-color: #ffebee;
        }
        .success-message {
            background-color: #d4edda;
            color: #155724;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            border: 1px solid #c3e6cb;
        }
        .success-message h3 {
            margin-top: 0;
        }
        .company-data {
            background-color: #e8f4f8;
            padding: 15px;
            border-radius: 5px;
            margin: 10px 0;
        }
        .company-data p {
            margin: 8px 0;
        }
        .company-data strong {
            display: inline-block;
            width: 150px;
            color: #333;
        }
        .company-data span {
            color: #0066cc;
        }
    </style>
</head>
<body>
    <h1>Esercizio 6 – Inserimento dati azienda</h1>

    <?php if ($formHandler->isValid()): ?>
        <div class="success-message">
            <h3>✓ Dati azienda inseriti con successo!</h3>
            <div class="company-data">
                <p><strong>Nome:</strong> <span><?php echo htmlspecialchars($formHandler->getCompany()->getNome()); ?></span></p>
                <p><strong>Ragione Sociale:</strong> <span><?php echo htmlspecialchars($formHandler->getCompany()->getRagioneSociale()); ?></span></p>
                <p><strong>Indirizzo:</strong> <span><?php echo htmlspecialchars($formHandler->getCompany()->getIndirizzo()); ?></span></p>
                <p><strong>Partita IVA:</strong> <span><?php echo htmlspecialchars($formHandler->getCompany()->getPartitaIVA()); ?></span></p>
            </div>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST" action="ditta.php">
            <?php $validator = $formHandler->getValidator(); ?>

            <div class="form-group <?php echo $validator->getError('Nome') ? 'error' : ''; ?>">
                <label for="nome">Nome:</label>
                <input type="text" id="nome" name="Nome" value="<?php echo $formHandler->getPostValue('Nome'); ?>" placeholder="Nombre dell'azienda">
                <?php if ($validator->getError('Nome')): ?>
                    <div class="error-message"><?php echo htmlspecialchars($validator->getError('Nome')); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo $validator->getError('Ragionesociale') ? 'error' : ''; ?>">
                <label for="ragionesociale">Ragione Sociale:</label>
                <input type="text" id="ragionesociale" name="Ragionesociale" value="<?php echo $formHandler->getPostValue('Ragionesociale'); ?>" placeholder="Ragione sociale">
                <?php if ($validator->getError('Ragionesociale')): ?>
                    <div class="error-message"><?php echo htmlspecialchars($validator->getError('Ragionesociale')); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo $validator->getError('Indirizzo') ? 'error' : ''; ?>">
                <label for="indirizzo">Indirizzo:</label>
                <input type="text" id="indirizzo" name="Indirizzo" value="<?php echo $formHandler->getPostValue('Indirizzo'); ?>" placeholder="Indirizzo completo">
                <?php if ($validator->getError('Indirizzo')): ?>
                    <div class="error-message"><?php echo htmlspecialchars($validator->getError('Indirizzo')); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group <?php echo $validator->getError('Partitaiva') ? 'error' : ''; ?>">
                <label for="partitaiva">Partita IVA:</label>
                <input type="text" id="partitaiva" name="Partitaiva" value="<?php echo $formHandler->getPostValue('Partitaiva'); ?>" placeholder="11 cifre numeriche">
                <?php if ($validator->getError('Partitaiva')): ?>
                    <div class="error-message"><?php echo htmlspecialchars($validator->getError('Partitaiva')); ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <input type="submit" value="Invia">
            </div>
        </form>
    </div>
</body>
</html>
