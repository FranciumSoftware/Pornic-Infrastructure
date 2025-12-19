<?php
session_start();
$admin_password = 'votre_mot_de_passe'; // À changer !
$error = '';

if (isset($_POST['password'])) {
    if ($_POST['password'] === $admin_password) {
        $_SESSION['admin'] = true;
        header('Location: edit/');
        exit;
    } else {
        $error = "Mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Connexion Admin</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <h2>Connexion Admin</h2>
    <?php if (!empty($error)) echo "<p style='color:red;'>$error</p>"; ?>
    <form method="post">
        <label>Mot de passe : <input type="password" name="password" required></label>
        <button type="submit">Valider</button>
    </form>
</body>
</html>