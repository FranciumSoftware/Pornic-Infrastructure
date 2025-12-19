<?php
// callback.php
require_once(__DIR__ . '/vendor/autoload.php');
require_once(__DIR__ . '/config.php');
session_start();

// Vérifie le state
// Debug: log incoming state and stored session state for diagnosis
if (defined('OAUTH_DEBUG') && OAUTH_DEBUG) {
    $incoming = $_GET['state'] ?? '(none)';
    $stored = $_SESSION['oauth_state'] ?? '(none)';
    $log = sprintf("%s | callback.php | session_id=%s | incoming_state=%s | stored_state=%s\n", date('c'), session_id(), $incoming, $stored);
    @file_put_contents(OAUTH_DEBUG_LOG, $log, FILE_APPEND | LOCK_EX);
    // Also log full GET params and cookies for diagnosis (safe in dev)
    $qs = http_build_query($_GET);
    $ck = json_encode($_COOKIE);
    $sess = json_encode($_SESSION);
    $extra = sprintf("%s | callback.php | GET=%s | COOKIE=%s | SESSION=%s\n", date('c'), $qs, $ck, $sess);
    @file_put_contents(OAUTH_DEBUG_LOG, $extra, FILE_APPEND | LOCK_EX);
}

// If the provider returned an error (for example unauthorized_client), show it and log details
if (isset($_GET['error'])) {
    $err = $_GET['error'];
    $desc = $_GET['error_description'] ?? '';
    $log = sprintf("%s | callback.php | oauth_error=%s | description=%s\n", date('c'), $err, $desc);
    @file_put_contents(OAUTH_DEBUG_LOG, $log, FILE_APPEND | LOCK_EX);
    http_response_code(400);
    die("Erreur OAuth: " . htmlspecialchars($err) . " - " . htmlspecialchars($desc));
}

if (!isset($_GET['code']) || !isset($_GET['state']) || $_GET['state'] !== ($_SESSION['oauth_state'] ?? '')) {
    http_response_code(400);
    die("Erreur d'authentification : état (state) invalide. Vérifiez que vous avez utilisé le même navigateur et que la session est active.");
}

// Échange le code contre un token
try {
    $httpClient = new \GuzzleHttp\Client();

    $response = $httpClient->post(HELLOASSO_OAUTH_BASE . '/oauth2/token', [
        'form_params' => [
            'grant_type' => 'authorization_code',
            'client_id' => CLIENT_ID,
            'client_secret' => CLIENT_SECRET,
            'redirect_uri' => REDIRECT_URI,
            'code' => $_GET['code'],
        ],
    ]);

    $tokenData = json_decode($response->getBody(), true);

    // Stocke le token d'accès en session
    $_SESSION['access_token'] = $tokenData['access_token'];

    // Redirige vers une page protégée
    header("Location: events.php");
    exit;
} catch (\GuzzleHttp\Exception\RequestException $e) {
    // If HelloAsso returned an error response, include the body for debugging (don't expose in prod)
    $body = null;
    if ($e->hasResponse()) {
        $body = (string)$e->getResponse()->getBody();
    }
    http_response_code(500);
    die("Erreur lors de la récupération du token: " . $e->getMessage() . "\nResponse body: " . ($body ?? 'n/a'));
} catch (Exception $e) {
    http_response_code(500);
    die("Erreur inattendue lors de la récupération du token : " . $e->getMessage());
}
?>
