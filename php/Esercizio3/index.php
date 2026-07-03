<?php
/**
 * Esercizio 3 – Ottenere l'indirizzo IP del client
 */

class ClientInfo {
    public function getClientIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        } elseif (!empty($_SERVER['REMOTE_ADDR'])) {
            $ip = $_SERVER['REMOTE_ADDR'];
        } else {
            $ip = 'Non disponibile';
        }
        return $ip;
    }

    public function isIPValid($ip) {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }

    public function isIPv4($ip) {
        return filter_var($ip, FILTER_VALIDATE_IPV4) !== false;
    }

    public function isIPv6($ip) {
        return filter_var($ip, FILTER_VALIDATE_IPV6) !== false;
    }
}

$clientInfo = new ClientInfo();
$clientIP = $clientInfo->getClientIP();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esercizio 3 - IP del Client</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .info { background-color: #f0f0f0; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .label { font-weight: bold; color: #333; }
        .badge { display: inline-block; padding: 5px 10px; border-radius: 3px; margin: 5px 5px 5px 0; }
        .badge-valid { background-color: #4CAF50; color: white; }
        .badge-invalid { background-color: #f44336; color: white; }
    </style>
</head>
<body>
    <h1>Esercizio 3 – Ottenere l'indirizzo IP del client</h1>

    <div class="info">
        <p><span class="label">Indirizzo IP:</span> <?php echo htmlspecialchars($clientIP); ?></p>
        
        <?php if ($clientIP !== 'Non disponibile'): ?>
            <p>
                <span class="label">Validazione:</span>
                <?php if ($clientInfo->isIPValid($clientIP)): ?>
                    <span class="badge badge-valid">✓ IP valido</span>
                <?php else: ?>
                    <span class="badge badge-invalid">✗ IP non valido</span>
                <?php endif; ?>
            </p>
            <p>
                <span class="label">Tipo:</span>
                <?php if ($clientInfo->isIPv4($clientIP)): ?>
                    <span class="badge badge-valid">IPv4</span>
                <?php elseif ($clientInfo->isIPv6($clientIP)): ?>
                    <span class="badge badge-valid">IPv6</span>
                <?php else: ?>
                    <span class="badge badge-invalid">Tipo sconosciuto</span>
                <?php endif; ?>
            </p>
        <?php endif; ?>
    </div>
</body>
</html>
