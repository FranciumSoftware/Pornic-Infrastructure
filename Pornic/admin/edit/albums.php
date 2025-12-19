<?php
session_start();
require_once '../../config.php';
if (empty($_SESSION['admin'])) { header('Location: ../index.php'); exit; }

$message = '';
if (!empty($_POST['album_name'])) {
    $name = trim($_POST['album_name']);
    $pdo->prepare("INSERT INTO albums (name) VALUES (?)")->execute([$name]);
    $dir = '../../photos/albums/' . $name;
    if (!is_dir($dir)) mkdir($dir, 0777, true);
    $message = "Album créé.";
}
$albums = $pdo->query("SELECT * FROM albums")->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gestion Albums</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Créer un album</h2>
    <div id="navbar">
        <a href="./">Accueil</a>
        <a href="jeunes.php">Jeunes</a>
        <a href="maitres.php">Maîtres</a>
        <a href="sauvetage.php">Sauvetage</a>
        <a href="bureau.php">Bureau</a>
        <a href="contact.php">Contact</a>
        <a href="news.php">Actualités</a>
        <a href="albums.php">Photos</a>
    </div> 
    <?php if ($message) echo "<p style='color:green;'>$message</p>"; ?>
    <form method="post">
        <input type="text" name="album_name" placeholder="Nom du nouvel album" required>
        <button type="submit">Créer</button>
    </form>
    <h2>Albums existants</h2>
    <ul>
        <?php foreach ($albums as $a): ?>
            <li>
                <?php if ($a['cover'] && file_exists('../../photos/' . $a['cover'])): ?>
                    <img src="../../photos/<?= htmlspecialchars($a['cover']) ?>" style="max-width:100px;vertical-align:middle;">
                <?php endif; ?>
                <?= htmlspecialchars($a['name']) ?>
                <a href="edit_album.php?album=<?= urlencode($a['name']) ?>">Gérer</a>
            </li>
        <?php endforeach; ?>
    </ul>
</body>
</html>