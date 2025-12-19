<?php
require_once 'config.php';

function getContentByClass(PDO $pdo, string $class) {
    $stmt = $pdo->prepare("SELECT content FROM Accueil WHERE class = ?");
    $stmt->execute([$class]);
    $row = $stmt->fetch();
    return $row ? $row['content'] : '';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="font/css/fontawesome.css">
    <link rel="shortcut icon" href="Logo.png" type="image/png">
    <title>Pornic Natation pays de Retz</title>
    <style>
        /* Lightbox styles */
        p img{
            width:100%;
        }
        .lightbox {
            display: none;
            position: fixed;
            z-index: 1000;
            padding-top: 60px;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0,0,0);
            background-color: rgba(0,0,0,0.9);
        }

        .lightbox-image {
            margin: auto;
            display: block;
            width: 80%;
            max-width: 700px;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 35px;
            color: #f1f1f1;
            font-size: 40px;
            font-weight: bold;
            transition: 0.3s;
        }

        .close:hover,
        .close:focus {
            color: #bbb;
            text-decoration: none;
            cursor: pointer;
        }

        /* Slideshow styles */
        .slideshow-container {
        max-width: 1000px;
        position: relative;
        margin: auto;
        }

        /* Caption text */
        .text {
        color: #8ebbf5;
        font-size: 15px;
        padding: 8px 12px;
        position: absolute;
        bottom: 8px;
        width: 100%;
        text-align: center;
        }
        .text a{
            color:white;
            text-shadow:20px #fff;
            font-size:2em;
            transition: font-size 0.3s ease;
        }
        .text a:hover{
            font-size:3em;
 
        }

        /* Number text (1/3 etc) */
        .numbertext {
        color: #f2f2f2;
        font-size: 12px;
        padding: 8px 12px;
        position: absolute;
        top: 0;
        }

        /* The dots/bullets/indicators */
        .dot {
        height: 15px;
        width: 15px;
        margin: 0 2px;
        background-color: #bbb;
        border-radius: 50%;
        display: inline-block;
        transition: background-color 0.6s ease;
        }

        .activeslide {
        background-color: #717171;
        }

        /* Fading animation */
        .fade {
        animation-name: fade;
        animation-duration: 1.5s;
        }

        @keyframes fade {
        from {opacity: .4}
        to {opacity: 1}
        }

        /* On smaller screens, decrease text size */
        @media only screen and (max-width: 300px) {
        .text {font-size: 11px}
        }

        /* Cookie banner styles */
        .cookie-banner {
            position: fixed;
            bottom: 0;
            left: 0;
            width: 100%;
            background-color: rgba(0, 0, 0, 0.8);
            color: #fff;
            text-align: center;
            padding: 10px;
            z-index: 1000;
        }
        .cookie-banner button {
            background-color: #0087ca;
            color: white;
            border: none;
            padding: 10px 20px;
            cursor: pointer;
        }
        .cookie-banner button:hover {
            background-color:rgb(2, 123, 184);
        }
    </style>
</head>

<body>
    <nav class="navbar">
        <a href="" class="logo"><img class="icon" src="Icon.png" alt="Logo du club"></a>
        <div class="navlinks">
            <ul>
                <li class="link"><a href="" class="active">Accueil</a></li>
                <li class="link"><a href="organisation">Notre club</a></li>
                <li class="link"><a href="photos">Photos</a></li>
                <li class="link"><a href="news">Actualités</a></li>
                <li class="link"><a href="contact">Nous contacter</a></li>
                <li class="link"><a href="boutique">Boutique</a></li>
            </ul>
        </div>
        <img src="bars-solid.png" alt="Menu" class="menu">
    </nav>
    <div class="content">
        <div class="body">
            <h2><?= getContentByClass($pdo, 'h2')  ?: 'Bienvenue sur le site de Pornic Natation Pays de Retz'?></h2>

            <section class="about">
                <p><img src="<?= getContentByClass($pdo, 'about.img') ?>" class="home" alt="Accueil"></p>
            </section>
            <section class="about">
                    <h3>
                        <?= getContentByClass($pdo, 'about.H3') ?: 'À propos de nous'; ?>
                    </h3>
            

                <p>
                    <?= getContentByClass($pdo, 'about.p') ?: 'À propos de nous'; ?>
                </p>
            </section>
            <div class="slideshow-container" style="text-align:center;">

                <div class="mySlides fade" style="text-align:center;">
                <div class="numbertext">1 / 6</div>
                <img src="<?= getContentByClass($pdo, 'slideshow.img.1') ?>" style="width:75%">
                <div class="text"><a href="<?= getContentByClass($pdo, 'slideshow.lnk.1') ?>"><?= getContentByClass($pdo, 'slideshow.txt.1') ?> <li class="fa-solid fa-arrow-right"></li></a></div>
                </div>

                <div class="mySlides fade" style="text-align:center;">
                <div class="numbertext">2 / 6</div>
                <img src="<?= getContentByClass($pdo, 'slideshow.img.2') ?>" style="width:75%">
                <div class="text"><a href="<?= getContentByClass($pdo, 'slideshow.lnk.2') ?>"><?= getContentByClass($pdo, 'slideshow.txt.2') ?> <li class="fa-solid fa-arrow-right"></li></a></div>
                </div>

                <div class="mySlides fade" style="text-align:center;">
                <div class="numbertext">3 / 6</div>
                <img src="<?= getContentByClass($pdo, 'slideshow.img.3') ?>" style="width:75%">
                <div class="text"><a href="<?= getContentByClass($pdo, 'slideshow.lnk.3') ?>"><?= getContentByClass($pdo, 'slideshow.txt.3') ?><li class="fa-solid fa-arrow-right"></li></a></div>
                </div>
                <div class="mySlides fade" style="text-align:center;">
                <div class="numbertext">4 / 6</div>
                <img src="<?= getContentByClass($pdo, 'slideshow.img.4') ?>" style="width:75%">
                <div class="text"><a href="<?= getContentByClass($pdo, 'slideshow.lnk.4') ?>"><?= getContentByClass($pdo, 'slideshow.txt.4') ?> <li class="fa-solid fa-arrow-right"></li></a></div>
                </div>

                <div class="mySlides fade" style="text-align:center;">
                <div class="numbertext">5 / 6</div>
                <img src="<?= getContentByClass($pdo, 'slideshow.img.5') ?>" style="width:75%">
                <div class="text"><a href="<?= getContentByClass($pdo, 'slideshow.lnk.5') ?>"><?= getContentByClass($pdo, 'slideshow.txt.5') ?><li class="fa-solid fa-arrow-right"></li></a></div>
                </div>

                <div class="mySlides fade" style="text-align:center;">
                <div class="numbertext">6 / 6</div>
                <img src="<?= getContentByClass($pdo, 'slideshow.img.6') ?>" style="width:75%">
                <div class="text"><a href="<?= getContentByClass($pdo, 'slideshow.lnk.6') ?>"><?= getContentByClass($pdo, 'slideshow.txt.6') ?><li class="fa-solid fa-arrow-right"></li></a></div>
                </div>

                </div>
                <br>

                <div style="text-align:center">
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                <span class="dot"></span>
                </div>
            </section>
            <section class="localisation">
                <h3><?= getContentByClass($pdo, 'local.h3') ?></h3>
                <iframe class="map"
                    src="<?= getContentByClass($pdo, 'local.lnk') ?>" style="border:0;" allowfullscreen="" loading="lazy"
                    referrerpolicy="no-referrer-when-downgrade"></iframe>
            </section>
        </div>
    </div>
    <div id="lightbox" class="lightbox">
        <span class="close">&times;</span>
        <img class="lightbox-image" id="lightbox-image" src="" alt="">
    </div>
    <div class="cookie-banner" id="cookieBanner">
        Ce site utilise des cookies pour améliorer votre expérience. En continuant à naviguer, vous acceptez leur utilisation.
        <button onclick="acceptCookies()">Accepter</button>
    </div>
</body>
<footer>
    <p>Pornic Natation Pays de Retz</p>
    <a href="legal">Mentions légales</a>
</footer>
<script src="https://kit.fontawesome.com/aa12b9fbf5.js" crossorigin="anonymous"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const lightbox = document.getElementById('lightbox');
        const lightboxImage = document.getElementById('lightbox-image');
        const closeBtn = document.querySelector('.close');

        // Ajouter un gestionnaire d'événements pour chaque image
        document.querySelectorAll('.view a').forEach(function(link) {
            link.addEventListener('click', function(event) {
                event.preventDefault();
                lightboxImage.src = this.href;
                lightbox.style.display = 'block';
            });
        });

        // Fermer la lightbox lorsque le bouton de fermeture est cliqué
        closeBtn.addEventListener('click', function() {
            lightbox.style.display = 'none';
        });

        // Fermer la lightbox lorsque l'utilisateur clique en dehors de l'image
        lightbox.addEventListener('click', function(event) {
            if (event.target === lightbox) {
                lightbox.style.display = 'none';
            }
        });
    });

    const menuHamburger = document.querySelector(".menu")
    const navLinks = document.querySelector(".navlinks")
    let clicked=false;
    var newsource="xmark.svg";

    menuHamburger.addEventListener('click', () => {
        navLinks.classList.toggle('mobile-menu')
        if (clicked===false){
            menuHamburger.src=newsource
            clicked=true;
            document.body.classList.add('noscroll'); // Bloque le scroll du body
        } else{
            menuHamburger.src="bars-solid.png"
            clicked=false;
            document.body.classList.remove('noscroll'); // Rétablit le scroll du body
        }
    })

    let slideIndex = 0;
    showSlides();

    function showSlides() {
    let i;
    let slides = document.getElementsByClassName("mySlides");
    let dots = document.getElementsByClassName("dot");
    for (i = 0; i < slides.length; i++) {
        slides[i].style.display = "none";
    }
    slideIndex++;
    if (slideIndex > slides.length) {slideIndex = 1}
    for (i = 0; i < dots.length; i++) {
        dots[i].className = dots[i].className.replace(" activeslide", "");
    }
    slides[slideIndex-1].style.display = "block";
    dots[slideIndex-1].className += " activeslide";
    setTimeout(showSlides, 5000); // Change image every 2 seconds
    }

    function acceptCookies() {
        document.getElementById('cookieBanner').style.display = 'none';
        // Vous pouvez également enregistrer le consentement dans un cookie ou localStorage
        localStorage.setItem('cookieConsent', 'accepted');
    }

    // Vérifiez si le consentement a déjà été donné
    if (localStorage.getItem('cookieConsent') === 'accepted') {
        document.getElementById('cookieBanner').style.display = 'none';
    }
</script>

</html>
