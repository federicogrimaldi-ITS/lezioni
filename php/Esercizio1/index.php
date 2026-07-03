<?php
/**
 * Esercizio 1 – Visualizzare il nome del file corrente
 */

class FileInfo {
    public function getCurrentFileName() {
        return basename(__FILE__);
    }

    public function getCurrentFilePath() {
        return __FILE__;
    }

    public function getScript() {
        return $_SERVER['SCRIPT_NAME'] ?? 'Non disponibile';
    }
}

$fileInfo = new FileInfo();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esercizio 1 - Nome File Corrente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .info { background-color: #f0f0f0; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .label { font-weight: bold; color: #333; }
    </style>
</head>
<body>
    <h1>Esercizio 1 – Visualizzare il nome del file corrente</h1>

    <div class="info">
        <p><span class="label">Nome del file:</span> <?php echo htmlspecialchars($fileInfo->getCurrentFileName()); ?></p>
        <p><span class="label">Percorso completo:</span> <?php echo htmlspecialchars($fileInfo->getCurrentFilePath()); ?></p>
        <p><span class="label">Script name:</span> <?php echo htmlspecialchars($fileInfo->getScript()); ?></p>
    </div>
</body>
</html>
