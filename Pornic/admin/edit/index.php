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
require_once 'fields.php';

// Traitement de l'édition
if (isset($_POST['contents']) && is_array($_POST['contents'])) {
    foreach ($classes as $class => $label) {
        if (isset($_POST['contents'][$class])) {
            $stmt = $pdo->prepare("UPDATE Accueil SET content = :content WHERE class = :class");
            $stmt->execute(['content' => $_POST['contents'][$class], 'class' => $class]);
        }
    }
    $message = "Contenus mis à jour avec succès.";
}

// Récupération des contenus actuels
$contents = [];
$stmt = $pdo->query("SELECT class, content FROM Accueil");
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
        'about.img',
        'slideshow.img.1',
        'slideshow.img.2',
        'slideshow.img.3',
        'slideshow.img.4',
        'slideshow.img.5',
        'slideshow.img.6',
        // Ajoute ici d'autres champs image si besoin
    ];
    foreach ($imageFields as $field) {
        $inputName = str_replace('.', '_', $field); // about.img => about_img
        if (isset($_FILES[$inputName]) && $_FILES[$inputName]['error'] === UPLOAD_ERR_OK) {
            $uploadDir = '../../Ressources/';
            $ext = pathinfo($_FILES[$inputName]['name'], PATHINFO_EXTENSION);
            $newName = $field . '.' . $ext;
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
    <h2>Éditer les contenus d'accueil</h2>
     <div id="navbar">
        <a href="">Accueil</a>
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
            <textarea name="contents[h2]" rows="1" cols="80" class="h2"><?= htmlspecialchars($contents['h2'] ?? '') ?></textarea><br>
            <span>
                <?php if (!empty($contents['about.img'])): ?>
                        <img src="../../<?= htmlspecialchars($contents['about.img']) ?>" alt="Image accueil" style="max-width:200px;display:block;">
                    <?php endif; ?>
                    <input type="file" name="about_img" accept="image/*" value="Changer">
                    <input type="hidden" name="contents[about.img]" value="<?= htmlspecialchars($contents['about.img'] ?? '') ?>">
            </span>
        </section>
        <section>
            <textarea name="contents[about.H3]" rows="1" cols="80" class="h3"><?= htmlspecialchars($contents['about.H3'] ?? '') ?></textarea><br>
            <textarea name="contents[about.p]" rows="3" cols="80" class="p" id="edit"><?= htmlspecialchars($contents['about.p'] ?? '') ?></textarea>
        </section>
        <section style="display:flex;" class="slideshow-images">
            <span>
                <?php if (!empty($contents['slideshow.img.1'])): ?>
                        <img src="../../<?= htmlspecialchars($contents['slideshow.img.1']) ?>" alt="Image accueil" style="max-width:200px;display:block;">
                    <?php endif; ?>
                    <input type="file" name="slideshow_img_1" accept="image/*" value="Changer">
                    <input type="hidden" name="contents[slideshow.img.1]" value="<?= htmlspecialchars($contents['slideshow.img.1'] ?? '') ?>">
            </span>
            <span>
                <?php if (!empty($contents['slideshow.img.2'])): ?>
                        <img src="../../<?= htmlspecialchars($contents['slideshow.img.2']) ?>" alt="Image accueil" style="max-width:200px;display:block;">
                    <?php endif; ?>
                    <input type="file" name="slideshow_img_2" accept="image/*" value="Changer">
                    <input type="hidden" name="contents[slideshow.img.2]" value="<?= htmlspecialchars($contents['slideshow.img.2'] ?? '') ?>">
            </span>
            <span>
                <?php if (!empty($contents['slideshow.img.3'])): ?>
                        <img src="../../<?= htmlspecialchars($contents['slideshow.img.3']) ?>" alt="Image accueil" style="max-width:200px;display:block;">
                    <?php endif; ?>
                    <input type="file" name="slideshow_img_3" accept="image/*" value="Changer">
                    <input type="hidden" name="contents[slideshow.img.3]" value="<?= htmlspecialchars($contents['slideshow.img.3'] ?? '') ?>">
            </span>
            <span>
                <?php if (!empty($contents['slideshow.img.4'])): ?>
                        <img src="../../<?= htmlspecialchars($contents['slideshow.img.4']) ?>" alt="Image accueil" style="max-width:200px;display:block;">
                    <?php endif; ?>
                    <input type="file" name="slideshow_img_4" accept="image/*" value="Changer">
                    <input type="hidden" name="contents[slideshow.img.4]" value="<?= htmlspecialchars($contents['slideshow.img.4'] ?? '') ?>">
            </span>
            <span>
                <?php if (!empty($contents['slideshow.img.5'])): ?>
                        <img src="../../<?= htmlspecialchars($contents['slideshow.img.5']) ?>" alt="Image accueil" style="max-width:200px;display:block;">
                    <?php endif; ?>
                    <input type="file" name="slideshow_img_5" accept="image/*" value="Changer">
                    <input type="hidden" name="contents[slideshow.img.5]" value="<?= htmlspecialchars($contents['../../ressources/slideshow.img.5'] ?? '') ?>">
            </span>
            <span>
                    <?php if (!empty($contents['slideshow.img.6'])): ?>
                        <img src="../../<?= htmlspecialchars($contents['slideshow.img.6']) ?>" alt="Image accueil" style="max-width:200px;display:block;">
                    <?php endif; ?>
                    <input type="file" name="slideshow_img_6" accept="image/*" value="Changer">
                    <input type="hidden" name="contents[slideshow.img.6]" value="<?= htmlspecialchars($contents['slideshow.img.6'] ?? '') ?>">
            </span>
        </section>
        <section style="display:flex;" class="slideshow-text">
            <span>
                <textarea name="contents[slideshow.txt.1]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.txt.1'] ?? '') ?></textarea>
            </span>
            <span>
                <textarea name="contents[slideshow.txt.2]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.txt.2'] ?? '') ?></textarea>
            </span>
            <span>
                <textarea name="contents[slideshow.txt.3]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.txt.3'] ?? '') ?></textarea>
            </span>
            <span>
                <textarea name="contents[slideshow.txt.4]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.txt.4'] ?? '') ?></textarea>
            </span>
            <span>
                <textarea name="contents[slideshow.txt.5]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.txt.5'] ?? '') ?></textarea>
            </span>
            <span>
                <textarea name="contents[slideshow.txt.6]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.txt.6'] ?? '') ?></textarea>
            </span>
        </section>
        <section style="display:flex;" class="slideshow-links">
            <span>
                <i class="fa-solid fa-link" style="  margin-right:2%"></i><textarea class="lnk" name="contents[slideshow.lnk.1]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.lnk.1'] ?? '') ?></textarea>
            </span>
            <span>
                <i class="fa-solid fa-link" style=" margin-right:2%"></i><textarea class="lnk" name="contents[slideshow.lnk.2]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.lnk.2'] ?? '') ?></textarea>
            </span>
            <span>
                <i class="fa-solid fa-link" style=" margin-right:2%"></i><textarea class="lnk" name="contents[slideshow.lnk.3]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.lnk.3'] ?? '') ?></textarea>
            </span>
            <span>
                <i class="fa-solid fa-link" style=" margin-right:2%"></i><textarea class="lnk" name="contents[slideshow.lnk.4]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.lnk.4'] ?? '') ?></textarea>
            </span>
            <span>
                <i class="fa-solid fa-link" style=" margin-right:2%"></i><textarea class="lnk" name="contents[slideshow.lnk.5]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.lnk.5'] ?? '') ?></textarea>
            </span>
            <span>
                <i class="fa-solid fa-link" style=" margin-right:2%"></i><textarea class="lnk" name="contents[slideshow.lnk.6]" rows="1" cols="80"><?= htmlspecialchars($contents['slideshow.lnk.6'] ?? '') ?></textarea>
            </span>
        </section>
        <section>
            <textarea name="contents[local.h3]" rows="1" cols="80" class="h3"><?= htmlspecialchars($contents['local.h3'] ?? '') ?></textarea><br>
            <i class="fa-solid fa-link" style="font-size:2em; margin-right:2%"></i><textarea class="lnk" name="contents[local.lnk]" rows="1" cols="80"><?= htmlspecialchars($contents['local.lnk'] ?? '') ?></textarea>
        </section>
        <button type="submit">Enregistrer tous les changements</button>
    </form>
    <form method="post">
        <button type="submit" name="logout" value="1">Déconnexion</button>
    </form>
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
</body>
</html>