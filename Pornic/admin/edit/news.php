<?php
session_start();
require_once 'config.php';

// Vérification admin
if (empty($_SESSION['admin'])) {
    header('Location: ../index.php');
    exit;
}

$message = '';

// Suppression d'une actualité et de ses images
if (isset($_GET['delete'])) {
    $stmt = $pdo->prepare("SELECT Content FROM news WHERE ID = ?");
    $stmt->execute([$_GET['delete']]);
    $row = $stmt->fetch();
    if ($row && preg_match_all('/<img[^>]+src="([^"]+)"/i', $row['Content'], $matches)) {
        foreach ($matches[1] as $imgUrl) {
            $parsed = parse_url($imgUrl, PHP_URL_PATH);
            $imgFile = $_SERVER['DOCUMENT_ROOT'] . $parsed;
            if (file_exists($imgFile)) unlink($imgFile);
        }
    }
    $stmt = $pdo->prepare("DELETE FROM news WHERE ID = ?");
    $stmt->execute([$_GET['delete']]);
    $message = "Actualité supprimée.";
}

// Création ou modification
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['title']) && !empty($_POST['content'])) {
    if (!empty($_POST['edit_id'])) {
        $stmt = $pdo->prepare("UPDATE news SET Title = ?, Content = ? WHERE ID = ?");
        $stmt->execute([$_POST['title'], $_POST['content'], $_POST['edit_id']]);
        $message = "Actualité modifiée !";
    } else {
        $stmt = $pdo->prepare("INSERT INTO news (Date, Title, Content) VALUES (NOW(), ?, ?)");
        $stmt->execute([$_POST['title'], $_POST['content']]);
        $message = "Actualité publiée !";
    }
}

// Récupération des actualités et de l'actu à éditer
$stmt = $pdo->query("SELECT * FROM news ORDER BY Date DESC");
$all_news = $stmt->fetchAll();

$edit_news = null;
if (isset($_GET['edit'])) {
    $stmt = $pdo->prepare("SELECT * FROM news WHERE ID = ?");
    $stmt->execute([$_GET['edit']]);
    $edit_news = $stmt->fetch();
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Poster une actualité</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Poster une actualité</h2>
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

    <h2>Actualités existantes</h2>
    <table border="1" cellpadding="5">
        <tr>
            <th>Titre</th>
            <th>Date</th>
            <th>Actions</th>
        </tr>
        <?php foreach ($all_news as $n): ?>
        <tr>
            <td><?= htmlspecialchars($n['Title']) ?></td>
            <td><?= htmlspecialchars($n['Date']) ?></td>
            <td>
                <a href="?edit=<?= $n['ID'] ?>" class="edit"><i class="fa-solid fa-pen"></i> Modifier</a>
                <a href="?delete=<?= $n['ID'] ?>" class="delete" onclick="return confirm('Supprimer cette actualité ?');"><i class="fa-solid fa-trash"></i> Supprimer</a>
            </td>
        </tr>
        <?php endforeach; ?>
    </table>
    <br>
    <form method="post" enctype="multipart/form-data">
        <?php if ($edit_news): ?>
            <input type="hidden" name="edit_id" value="<?= $edit_news['ID'] ?>">
        <?php endif; ?>
        <label>Titre :<br>
            <input type="text" name="title" required style="width:400px;" value="<?= htmlspecialchars($edit_news['Title'] ?? '') ?>">
        </label><br><br>
        <label>Contenu (HTML autorisé) :<br>
            <textarea name="content" rows="10" cols="80" id="edit"><?= htmlspecialchars($edit_news['Content'] ?? '') ?></textarea>
        </label><br><br>
        <button type="submit"><?= $edit_news ? 'Mettre à jour' : 'Publier' ?></button>
    </form>
</body>
<script src="../../tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#edit',
        plugins: 'lists link image code',
        language: 'fr_FR',
        toolbar: 'undo redo | bold italic | bullist numlist | link image | code',
        images_upload_url: 'upload_image.php',
        automatic_uploads: true,
        menubar: false,
        images_upload_credentials: true,
        relative_urls: false,
        remove_script_host: false,
        convert_urls: true
    });

    // Forcer la sauvegarde du contenu TinyMCE dans le textarea avant la soumission
    document.querySelector('form').addEventListener('submit', function(e) {
        if (tinymce.get('edit')) {
            tinymce.get('edit').save();
        }
    });
</script>
</html>