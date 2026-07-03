<?php
/**
 * Esercizio 5 – Form di Benvenuto
 */

class WelcomeForm {
    private $name = '';
    private $submitted = false;
    private $errors = [];

    public function __construct() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->submitted = true;
            $this->handleSubmission();
        }
    }

    private function handleSubmission() {
        $this->name = trim($_POST['nome'] ?? '');

        if (empty($this->name)) {
            $this->errors[] = 'Il nome è obbligatorio.';
        } elseif (strlen($this->name) < 2) {
            $this->errors[] = 'Il nome deve contenere almeno 2 caratteri.';
        } elseif (!preg_match('/^[a-zA-Z\s\'àèìòùáéíóúâêîôûäëïöü]+$/i', $this->name)) {
            $this->errors[] = 'Il nome contiene caratteri non validi.';
        }
    }

    public function isSubmitted() {
        return $this->submitted;
    }

    public function hasErrors() {
        return !empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getName() {
        return $this->name;
    }

    public function isValid() {
        return $this->submitted && !$this->hasErrors();
    }
}

$form = new WelcomeForm();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esercizio 5 - Form di Benvenuto</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; max-width: 500px; }
        .form-container { background-color: #f9f9f9; padding: 20px; border-radius: 5px; }
        .form-group { margin: 15px 0; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="submit"] { padding: 10px; border-radius: 3px; }
        input[type="text"] { width: 100%; border: 1px solid #ccc; box-sizing: border-box; }
        input[type="submit"] { background-color: #4CAF50; color: white; cursor: pointer; border: none; }
        input[type="submit"]:hover { background-color: #45a049; }
        .error { background-color: #ffcccc; color: #c00; padding: 10px; border-radius: 3px; margin: 10px 0; }
        .success { background-color: #ccffcc; color: #060; padding: 15px; border-radius: 5px; margin: 10px 0; font-size: 18px; }
        .error-list { list-style-type: none; padding: 0; }
        .error-list li { margin: 5px 0; }
        .error-list li:before { content: "✗ "; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Esercizio 5 – Form di Benvenuto</h1>

    <?php if ($form->isValid()): ?>
        <div class="success">
            Benvenuto, <strong><?php echo htmlspecialchars($form->getName()); ?></strong>! 👋
        </div>
    <?php endif; ?>

    <?php if ($form->hasErrors()): ?>
        <div class="error">
            <strong>Errori nel form:</strong>
            <ul class="error-list">
                <?php foreach ($form->getErrors() as $error): ?>
                    <li><?php echo htmlspecialchars($error); ?></li>
                <?php endforeach; ?>
            </ul>
        </div>
    <?php endif; ?>

    <div class="form-container">
        <form method="POST">
            <div class="form-group">
                <label for="nome">Il tuo nome:</label>
                <input type="text" id="nome" name="nome" value="<?php echo htmlspecialchars($form->getName()); ?>" placeholder="Inserisci il tuo nome">
            </div>
            <div class="form-group">
                <input type="submit" value="Invia">
            </div>
        </form>
    </div>
</body>
</html>
