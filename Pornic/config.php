<?php


$host = 'localhost';
$db   = 'Pornic';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
     $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
     throw new \PDOException($e->getMessage(), (int)$e->getCode());
}
?>
<?php
// OAuth / application configuration
// Keep these values in one place so auth.php and callback.php use the same values.
// Update these values to match the ones in your HelloAsso application settings.
define('CLIENT_ID', 'ceaca19ac9454fcca421f31673cf6eff');
define('CLIENT_SECRET', 'i7tKjEpnvg7cmbZWqtIj3F9gvouXl83Z');
// Make sure this EXACT URL is registered in HelloAsso (no leading/trailing spaces)
define('REDIRECT_URI', 'https://psychrometric-unlighted-mandie.ngrok-free.dev/Pornic/callback.php');

// Optional: sandbox vs production host
define('HELLOASSO_OAUTH_BASE', 'https://api.helloasso-sandbox.com');

// Note: don't include vendor/autoload here; individual scripts can include it as needed.
// Debug helpers (set to false in production)
define('OAUTH_DEBUG', true);
define('OAUTH_DEBUG_LOG', __DIR__ . '/oauth_debug.log');
?>

