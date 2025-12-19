<?php
// filepath: c:\xampp\htdocs\Pornic\admin\edit\edit_album.php
session_start();
require_once '../../config.php';
if (empty($_SESSION['admin'])) { header('Location: ../index.php'); exit; }

$album = $_GET['album'] ?? '';
$dir = '../../photos/albums/' . $album;
if (!is_dir($dir)) die('Album inexistant.');

$message = '';

// Upload de fichiers
if (!empty($_FILES['media']['name'][0])) {
    foreach ($_FILES['media']['tmp_name'] as $i => $tmp) {
        $name = basename($_FILES['media']['name'][$i]);
        $target = $dir . '/' . $name;
        if (!move_uploaded_file($tmp, $target)) {
            error_log("Erreur upload pour $name : code " . $_FILES['media']['error'][$i]);
        }
    }
    // Réponse AJAX
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
        http_response_code(200);
        exit;
    }
    $message = "Fichiers ajoutés.";
}

// Suppression d’un fichier
if (!empty($_GET['delete'])) {
    $file = $dir . '/' . basename($_GET['delete']);
    if (file_exists($file)) unlink($file);
    $message = "Fichier supprimé.";
}

// Liste des fichiers
$files = array_diff(scandir($dir), ['.','..']);

// Définir l'image de couverture
if (!empty($_POST['set_cover']) && !empty($_FILES['cover']['tmp_name'])) {
    $ext = strtolower(pathinfo($_FILES['cover']['name'], PATHINFO_EXTENSION));
    if (in_array($ext, ['jpg','jpeg','png','gif'])) {
        $coverName = 'cover_' . time() . '.' . $ext;
        $coverPath = $dir . '/' . $coverName;
        move_uploaded_file($_FILES['cover']['tmp_name'], $coverPath);
        // Enregistre le chemin relatif en base
        $pdo->prepare("UPDATE albums SET cover = ? WHERE name = ?")->execute(["albums/$album/$coverName", $album]);
        $message = "Image d'illustration définie.";
    }
}
$cover = $pdo->prepare("SELECT cover FROM albums WHERE name = ?");
$cover->execute([$album]);
$coverPath = $cover->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Gérer l’album <?= htmlspecialchars($album) ?></title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">
    <link rel="stylesheet" href="../style.css">
</head>
<body>
    <h2>Gérer l’album : <?= htmlspecialchars($album) ?></h2>
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
    <form id="coverForm" method="post" enctype="multipart/form-data">
        <label>Image d'illustration (jpg/png) :
            <?php if ($coverPath && file_exists('../../photos/' . $coverPath)): ?>
                <img src="../../photos/<?= htmlspecialchars($coverPath) ?>" style="max-width:100px;display:block;margin-bottom:10px;">
            <?php endif; ?>
            <input type="file" name="cover" accept="image/*">
        </label>
        <button type="submit" name="set_cover" value="1">Définir comme illustration</button>
    </form>
    <form id="uploadForm" method="post" enctype="multipart/form-data">
        <label>Ajouter des fichiers :</label>
        <div id="dropzone" style="border:2px dashed #888;padding:30px;text-align:center;cursor:pointer;margin-bottom:10px;">
            Glissez-déposez vos images/vidéos ici ou cliquez pour sélectionner
            <input type="file" id="fileInput" name="media[]" multiple accept="image/*,video/*" style="display:none;">
        </div>
        <button type="submit">Ajouter</button>
        <progress id="uploadProgress" value="0" max="100" style="width:100%;display:none;margin-top:10px;"></progress>
        <span id="progressText" style="margin-left:10px;"></span>
    </form>
    
    <h3>Fichiers de l’album</h3>
    <ul>
        <?php foreach ($files as $f): ?>
            <li>
                <?= htmlspecialchars($f) ?>
                <?php if ($coverPath && basename($coverPath) === $f): ?>
                    <strong>(illustration)</strong>
                <?php else: ?>
                    <a href="?album=<?= urlencode($album) ?>&delete=<?= urlencode($f) ?>" onclick="return confirm('Supprimer ce fichier ?');">Supprimer</a>
                <?php endif; ?>
            </li>
        <?php endforeach; ?>
    </ul>
    <a href="albums.php">Retour aux albums</a>

    <script>
const dropzone = document.getElementById('dropzone');
const fileInput = document.getElementById('fileInput');
const uploadForm = document.getElementById('uploadForm');
const progressBar = document.getElementById('uploadProgress');
const progressText = document.getElementById('progressText');

dropzone.addEventListener('click', () => fileInput.click());

dropzone.addEventListener('dragover', e => {
    e.preventDefault();
    dropzone.style.background = '#eef';
});
dropzone.addEventListener('dragleave', e => {
    e.preventDefault();
    dropzone.style.background = '';
});
dropzone.addEventListener('drop', e => {
    e.preventDefault();
    dropzone.style.background = '';
    fileInput.files = e.dataTransfer.files;
    fileInput.dispatchEvent(new Event('change'));
});

fileInput.addEventListener('change', () => {
    dropzone.textContent = fileInput.files.length + " fichier(s) sélectionné(s)";
});

uploadForm.addEventListener('submit', function(e) {
    e.preventDefault();
    if (!fileInput.files.length) return;

    const formData = new FormData();
    for (let i = 0; i < fileInput.files.length; i++) {
        formData.append('media[]', fileInput.files[i]);
    }

    progressBar.style.display = 'block';
    progressBar.value = 0;
    progressText.textContent = "0%";

    const xhr = new XMLHttpRequest();
    xhr.open('POST', '', true);

    xhr.upload.onprogress = function(e) {
        if (e.lengthComputable) {
            const percent = Math.round((e.loaded / e.total) * 100);
            progressBar.value = percent;
            progressText.textContent = percent + "%";
        }
    };

    xhr.onload = function() {
        progressBar.value = 100;
        progressText.textContent = "Terminé";
        setTimeout(() => {
            progressBar.style.display = 'none';
            progressText.textContent = "";
            window.location.reload();
        }, 800);
    };

    xhr.onerror = function() {
        progressText.textContent = "Erreur lors de l'upload";
    };

    xhr.send(formData);
});

<?php
if (!empty($_FILES['media']['error'])) {
    foreach ($_FILES['media']['error'] as $i => $err) {
        if ($err !== UPLOAD_ERR_OK) {
            echo "<p style='color:red'>Erreur upload pour " . htmlspecialchars($_FILES['media']['name'][$i]) . " : code $err</p>";
        }
    }
}
?>
</script>
</body>
</html>