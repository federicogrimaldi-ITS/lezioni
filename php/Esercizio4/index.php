<?php
/**
 * Esercizio 4 – Verificare HTTP o HTTPS
 */

class ProtocolChecker {
    public function isHTTPS() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ||
               $_SERVER['SERVER_PORT'] == 443;
    }

    public function getProtocol() {
        return $this->isHTTPS() ? 'HTTPS' : 'HTTP';
    }

    public function getFullURL() {
        $protocol = $this->getProtocol();
        $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        return $protocol . '://' . $host . $uri;
    }

    public function getSecurityLevel() {
        return $this->isHTTPS() ? 'Sicura ✓' : 'Non Sicura ✗';
    }
}

$protocol = new ProtocolChecker();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esercizio 4 - HTTP o HTTPS</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .info { background-color: #f0f0f0; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .label { font-weight: bold; color: #333; }
        .https { color: #4CAF50; font-weight: bold; }
        .http { color: #f44336; font-weight: bold; }
    </style>
</head>
<body>
    <h1>Esercizio 4 – Verificare HTTP o HTTPS</h1>

    <div class="info">
        <p>
            <span class="label">Protocollo:</span> 
            <span class="<?php echo $protocol->isHTTPS() ? 'https' : 'http'; ?>">
                <?php echo $protocol->getProtocol(); ?>
            </span>
        </p>
        <p><span class="label">Livello di sicurezza:</span> <?php echo $protocol->getSecurityLevel(); ?></p>
        <p><span class="label">URL completo:</span> <?php echo htmlspecialchars($protocol->getFullURL()); ?></p>
        <p><span class="label">Porta:</span> <?php echo htmlspecialchars($_SERVER['SERVER_PORT'] ?? 'Non disponibile'); ?></p>
    </div>
</body>
</html>
