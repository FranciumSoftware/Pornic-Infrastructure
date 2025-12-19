<?php
    require_once 'api.php';
    require_once 'config.php';

    session_start();

    // Initialisation de la session si elle n'existe pas
    if (!isset($_SESSION['user'])){
        $_SESSION['user'] = [
            'key' => [],
            'name' => [],
            'socid' => [],
            'member' => [],
            'socid' => [],
            'id' => []
        ];
    }

    if (isset($_POST['username']) && $_POST['username'] != "") {
        $json = callAPI("POST", null, baseURL . 'login?login=' . urlencode($_POST['username']) . "&password=" . urlencode($_POST['password']));
        $details = json_decode($json);
        // Vérifiez si la réponse contient un objet "success"
        if (isset($details->success) && $details->success->code == 200) {
            $token = $details->success->token;
            $_SESSION['user']['key'] = $token;
            $_SESSION['user']['name'] = $_POST['username'];
            $_SESSION['user']['id'] = getID($_POST['username'], $token);
            $_SESSION['user']['member'] = getMember($_POST['username'], $token);
            $_SESSION['user']['socid'] = getSocid($_POST['username'], $token);
            header('Location: ./index.php');
            exit(); // Toujours appeler exit() après un header('Location: ...')
        } else {
            // Afficher le code d'erreur si disponible
            $errorCode = isset($details->success->code) ? $details->success->code : "inconnu";
            echo "Login failed. Code: " . $errorCode;
        }
    }
    function getSocid($i, $token){
        // Try to get socid from user's thirdparty (customer/supplier)
        $url = baseURL . "users?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(login%3Alike%3A'".urlencode($i)."')";
        $json = callAPI('GET', $token, $url);
        $details = json_decode($json, true);
        
        if (isset($details[0]['socid']) && !empty($details[0]['socid'])) {
            return $details[0]['socid'];
        }
        
        // If user has no direct socid, try to get customer contact socid
        if (isset($details[0]['id'])) {
            $userId = $details[0]['id'];
            $contactUrl = baseURL . "contacts?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(fk_user_creat%3Alike%3A'".$userId."')";
            $contactJson = callAPI('GET', $token, $contactUrl);
            $contactDetails = json_decode($contactJson, true);
            if (isset($contactDetails[0]['socid'])) {
                return $contactDetails[0]['socid'];
            }
        }
        
        return null;
    }
    function getMember($i, $token){
        $url = baseURL . "users?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(login%3Alike%3A'".urlencode($i)."')";
        $json = callAPI('GET', $token, $url);
        $details = json_decode($json, true);
        return isset($details[0]['fk_member']) ? $details[0]['fk_member'] : null;
    }
    function getID($i, $token){
        $url = baseURL . "users?sortfield=t.rowid&sortorder=ASC&limit=100&sqlfilters=(login%3Alike%3A'".urlencode($i)."')";
        $json = callAPI('GET', $token, $url);
        $details = json_decode($json, true);
        return isset($details[0]['id']) ? $details[0]['id'] : null;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
</head>
<body>
    <form action="login.php" method="post">
        <label for="username">Username:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Login">
    </form>
</body>
</html>
