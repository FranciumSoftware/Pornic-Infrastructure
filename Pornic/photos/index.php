<?php
require_once '../config.php';
$albums = $pdo->query("SELECT * FROM albums")->fetchAll();
$selected = $_GET['album'] ?? null;
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="font/css/fontawesome.css">
        <link rel="shortcut icon" href="../Logo.png" type="image/png">

        <title>Pornic Natation pays de Retz</title>
        <style>
            .list{
                display: flex;
                margin: 10px;
            }
            .list li a{
                font-size:1.5em;
                color: blue;
            }
            .list li a img{
                height: 100px;
                width: auto;
            }
            .list li a:hover{
                text-decoration: underline;
                transition: all ease 0.5s;
            }
        </style>
    </head>
    <body>
        <nav class="navbar">
        <a href="../" class="logo"><img class="icon" src="../Icon.png" alt="Logo du club"></a>
            <div class="navlinks">
                <ul>
                    <li class="link"><a href="../">Accueil</a></li>
                    <li class="link"><a href="../organisation">Notre club</a></li>
                    <li class="link"><a href="" class="active">Photos</a></li>
                    <li class="link"><a href="../contact">Nous contacter</a></li>
                    <li class="../link"><a href="news">Actualités</a></li>
                    <li class="link"><a href="../boutique">Boutique</a></li>
                </ul>
            </div>    
            <img src="../bars-solid.png" class="menu"> 
        </nav>
        <div class="content">
            <div class="body">        
                <h2>Bienvenue sur le site de Pornic Natation Pays de Retz</h2>
                    <section class="about">
                    <?php if (!$selected): ?>
                    <ul>
                        <?php foreach ($albums as $a): ?>
                            <li>
                            <?php if ($a['cover'] && file_exists($a['cover'])): ?>
                                <img src="<?= htmlspecialchars($a['cover']) ?>" style="max-width:100px;vertical-align:middle;"><br>
                            <?php else: ?>
                                <img src="../ressources/album.png" style="max-width:100px;vertical-align:middle;"><br>
                            <?php endif; ?>
                                <a href="?album=<?= urlencode($a['name']) ?>"><?= htmlspecialchars($a['name']) ?></a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                    <?php endif; ?>
                    <?php if ($selected): ?>
                        <h3><a href="./"><i class="fa-solid fa-arrow-left"></i></a>     <?= htmlspecialchars($selected) ?></h3>
                        <div class="gallery">
                            
                        <?php
                            $coverFile = null;
                            foreach ($albums as $a) {
                                if ($a['name'] === $selected && $a['cover']) {
                                    // On récupère juste le nom du fichier de couverture (ex: cover_123456.jpg)
                                    $coverFile = basename($a['cover']);
                                    break;
                                }
                            }
                            $dir = __DIR__ . '/albums/' . basename($selected);
                            if (is_dir($dir)) {
                                foreach (scandir($dir) as $file) {
                                    if ($file === '.' || $file === '..') continue;
                                    // On saute la photo de couverture
                                    if ($coverFile && $file === $coverFile) continue;
                                    $ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
                                    if (in_array($ext, ['jpg','jpeg','png','gif'])) {
                                        echo '<img class="lightbox-item" src="albums/' . rawurlencode($selected) . '/' . rawurlencode($file) . '" style="max-height:300px;max-width:100vw;margin:5px;" data-type="img">';
                                    }
                                    if (in_array($ext, ['mp4','webm','ogg'])) {
                                        echo '<video class="lightbox-item" style="max-height:300px;max-width:100vw;margin:5px;" data-type="video" data-src="albums/' . rawurlencode($selected) . '/' . rawurlencode($file) . '"><source src="albums/' . rawurlencode($selected) . '/' . rawurlencode($file) . '"></video>';
                                    }
                                }
                            }
                        ?>
                        </div>
                    <?php endif; ?>
                    </section>
            </div>
        </div>
        <!-- Lightbox -->
        <div id="lightbox" style="display:none;position:fixed;top:0;left:0;width:100vw;height:100vh;background:rgba(0,0,0,0.9);justify-content:center;align-items:center;z-index:9999;flex-direction:column;">
            <span id="closeLightbox" style="position:absolute;top:20px;right:40px;font-size:3em;color:white;cursor:pointer;">&times;</span>
            <span id="prevLightbox" style="position:absolute;top:50%;left:30px;font-size:3em;color:white;cursor:pointer;">&#10094;</span>
            <span id="nextLightbox" style="position:absolute;top:50%;right:30px;font-size:3em;color:white;cursor:pointer;">&#10095;</span>
            <div id="lightboxContent"></div>
        </div>
    </body>
    <script src="https://kit.fontawesome.com/aa12b9fbf5.js" crossorigin="anonymous"></script>
    <script>
         const menuHamburger = document.querySelector(".menu")
    const navLinks = document.querySelector(".navlinks")
    let clicked=false;
    var newsource="../xmark.svg";

    menuHamburger.addEventListener('click', () => {
        navLinks.classList.toggle('mobile-menu')
        if (clicked===false){
            menuHamburger.src=newsource
            clicked=true;
        } else{
            menuHamburger.src="../bars-solid.png"
            clicked=false;
        }

    })
    </script>
    <script>
const items = Array.from(document.querySelectorAll('.lightbox-item'));
const lightbox = document.getElementById('lightbox');
const lightboxContent = document.getElementById('lightboxContent');
let currentIndex = -1;

function showLightbox(index) {
    if (index < 0 || index >= items.length) return;
    currentIndex = index;
    lightboxContent.innerHTML = '';
    const el = items[index];
    if (el.tagName === 'IMG') {
        const img = document.createElement('img');
        img.src = el.src;
        img.style.maxWidth = '90vw';
        img.style.maxHeight = '80vh';
        lightboxContent.appendChild(img);
    } else if (el.tagName === 'VIDEO') {
        const video = document.createElement('video');
        video.src = el.getAttribute('data-src') || el.querySelector('source').src;
        video.controls = true;
        video.autoplay = true;
        video.style.maxWidth = '90vw';
        video.style.maxHeight = '80vh';
        lightboxContent.appendChild(video);
    }
    lightbox.style.display = 'flex';
}
items.forEach((el, i) => {
    el.addEventListener('click', e => {
        e.preventDefault();
        showLightbox(i);
    });
});
document.getElementById('closeLightbox').onclick = () => lightbox.style.display = 'none';
document.getElementById('prevLightbox').onclick = () => showLightbox((currentIndex-1+items.length)%items.length);
document.getElementById('nextLightbox').onclick = () => showLightbox((currentIndex+1)%items.length);
// Fermer lightbox sur fond noir
lightbox.addEventListener('click', e => {
    if (e.target === lightbox) lightbox.style.display = 'none';
});
</script>
</html>