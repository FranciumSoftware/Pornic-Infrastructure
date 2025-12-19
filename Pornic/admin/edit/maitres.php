<?php
session_start();
if (empty($_SESSION['admin'])) {
    header('Location: ../');
    exit;
}

// Configuration de la base de données
$host = 'localhost';
$db   = 'pornic';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];
try {
    $pdo = new PDO($dsn, $user, $pass, $options);
} catch (\PDOException $e) {
    exit('Erreur de connexion à la base de données.');
}

// Liste des classes à éditer en dur
require_once 'fields/maitres.php'; // Assurez-vous que ce fichier contient le tableau $classes

// Traitement de l'édition
if (isset($_POST['contents']) && is_array($_POST['contents'])) {
    foreach ($classes as $class => $label) {
        if (isset($_POST['contents'][$class])) {
            $stmt = $pdo->prepare("UPDATE jeunes SET content = :content WHERE class = :class");
            $stmt->execute(['content' => $_POST['contents'][$class], 'class' => $class]);
        }
    }
    $message = "Contenus mis à jour avec succès.";
}

// Récupération des contenus actuels
$contents = [];
$stmt = $pdo->query("SELECT class, content FROM jeunes");
while ($row = $stmt->fetch()) {
    $contents[$row['class']] = $row['content'];
}

// Déconnexion
if (isset($_POST['logout'])) {
    session_destroy();
    header("Location: ../");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pour chaque champ image à gérer
    $imageFields = [
        // Ajoute ici d'autres champs image si besoin
    ];
    foreach ($imageFields as $field) {
        $inputName = str_replace('.', '_', $field); // about.img => about_img
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../Ressources/';
            $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
            $newName = $field . '_' . time() . '.' . $ext;
            $newName = str_replace('.', '_', $newName); // pour éviter les points dans le nom
            $targetFile = $uploadDir . $newName;

            // Supprimer l'ancienne image si elle existe
            if (!empty($contents[$field]) && file_exists('../../' . $contents[$field])) {
                unlink('../../' . $contents[$field]);
            }

            // Déplacer la nouvelle image
            if (move_uploaded_file($_FILES[$inputName]['tmp_name'], $targetFile)) {
                // Mettre à jour la valeur à enregistrer dans la base
                $_POST['contents'][$field] = 'Ressources/' . $newName;
                $contents[$field] = 'Ressources/' . $newName;
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Édition Accueil</title>
    <link rel="stylesheet" href="../style.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
</head>
<body>
    <h2>Éditer les contenus de la page Maîtres</h2>
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
    <?php if (!empty($message)) echo "<p style='color:green;'>$message</p>"; ?>
    <form method="post" enctype="multipart/form-data">
    <section>
        <textarea name="contents[about.h3]" rows="1" cols="80" class="h3"><?= htmlspecialchars($contents['about.h3'] ?? '') ?></textarea><br>
        <textarea name="contents[about.ul]" rows="5" cols="80" class="ul" id="edit"><?= htmlspecialchars($contents['about.ul'] ?? '') ?></textarea><br>
    </section>
    <section>
        <h3>Horaires</h3>
        <textarea name="contents[horaires.jour]" rows="1" cols="80" class="jour"><?= htmlspecialchars($contents['horaires.jour'] ?? '') ?></textarea><br>
        <textarea name="contents[horaires.heure]" rows="1" cols="80" class="heure"><?= htmlspecialchars($contents['horaires.heure'] ?? '') ?></textarea><br>
    </section>
    <section>
        <textarea name="contents[inscriptions.h3]" rows="1" cols="80" class="h3"><?= htmlspecialchars($contents['inscriptions.h3'] ?? '') ?></textarea><br>
        <textarea name="contents[inscriptions.p]" rows="5" cols="80" class="p" id="edit"><?= htmlspecialchars($contents['inscriptions.p'] ?? '') ?></textarea><br>
    </section>    
        <button type="submit">Enregistrer tous les changements</button>
    </form>
    <form method="post">
        <button type="submit" name="logout" value="1">Déconnexion</button>
    </form>
</body>
<script src="../../tinymce/tinymce.min.js"></script>
<script>
    tinymce.init({
        selector: '#edit',
        plugins: 'lists link image code',
        language: 'fr_FR',
        toolbar: 'undo redo | styleselect | bold italic underline |  bullist numlist outdent indent | link | code',
        menubar: false,
        height: 300, // Assurez-vous de gérer l'upload des images
        automatic_uploads: true,
        }
    );
</script>
</html>