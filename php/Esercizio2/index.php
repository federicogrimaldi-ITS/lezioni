<?php
/**
 * Esercizio 2 – Visualizzare il Browser dell'utente
 */

class BrowserDetector {
    private $userAgent;

    public function __construct() {
        $this->userAgent = $_SERVER['HTTP_USER_AGENT'] ?? 'Non disponibile';
    }

    public function getBrowserName() {
        if (strpos($this->userAgent, 'Opera') || strpos($this->userAgent, 'OPR/')) {
            return 'Opera';
        } elseif (strpos($this->userAgent, 'Edge') || strpos($this->userAgent, 'Edg/')) {
            return 'Edge';
        } elseif (strpos($this->userAgent, 'Chrome')) {
            return 'Chrome';
        } elseif (strpos($this->userAgent, 'Safari')) {
            return 'Safari';
        } elseif (strpos($this->userAgent, 'Firefox')) {
            return 'Firefox';
        } elseif (strpos($this->userAgent, 'MSIE') || strpos($this->userAgent, 'Trident/')) {
            return 'Internet Explorer';
        } else {
            return 'Sconosciuto';
        }
    }

    public function getOS() {
        if (preg_match('/windows|win32/i', $this->userAgent)) {
            return 'Windows';
        } elseif (preg_match('/macintosh|mac os x/i', $this->userAgent)) {
            return 'macOS';
        } elseif (preg_match('/linux/i', $this->userAgent)) {
            return 'Linux';
        } elseif (preg_match('/iphone|ipad|ipod/i', $this->userAgent)) {
            return 'iOS';
        } elseif (preg_match('/android/i', $this->userAgent)) {
            return 'Android';
        } else {
            return 'Sconosciuto';
        }
    }

    public function getUserAgent() {
        return $this->userAgent;
    }
}

$browser = new BrowserDetector();
?>

<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Esercizio 2 - Browser dell'Utente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .info { background-color: #f0f0f0; padding: 15px; border-radius: 5px; margin: 10px 0; }
        .label { font-weight: bold; color: #333; }
    </style>
</head>
<body>
    <h1>Esercizio 2 – Visualizzare il Browser dell'utente</h1>

    <div class="info">
        <p><span class="label">Browser:</span> <?php echo htmlspecialchars($browser->getBrowserName()); ?></p>
        <p><span class="label">Sistema Operativo:</span> <?php echo htmlspecialchars($browser->getOS()); ?></p>
        <p><span class="label">User Agent:</span> <?php echo htmlspecialchars($browser->getUserAgent()); ?></p>
    </div>
</body>
</html>
