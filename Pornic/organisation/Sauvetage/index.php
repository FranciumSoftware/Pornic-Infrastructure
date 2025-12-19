<?php
require_once '../../config.php';

function getContentByClass(PDO $pdo, string $class) {
    $stmt = $pdo->prepare("SELECT content FROM sauvetage WHERE class = ?");
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
        <link rel="stylesheet" href="../../style.css">
        <link rel="stylesheet" href="../font/css/fontawesome.css">
        <link rel="shortcut icon" href="../../Logo.png" type="image/png">
        <title>Pornic Natation pays de Retz</title>
        <style>
            h2{
                background-image: url('../Sauvetage.jpg');
                background-size: cover;
                background-position: center;
                color:aliceblue;
                font-size: 2.5em;
            }
            .coachs{
                list-style-type:circle;
            }
            .about{
                padding: 10px;
            }
            .horaires{
                text-decoration: underline;
            }
            .coachs li{
                list-style-type: disc;
                margin-left: 20px;
            }
        </style>
    </head>
    <body>
        <nav class="navbar">
            <a href="../../" class="logo"><img class="icon" src="../../Icon.png" alt="Logo du club"></a>
            <div class="navlinks">
                <ul>
                    <li class="link"><a href="../../">Accueil</a></li>
                    <li class="link"><a href="../">Notre club</a></li>
                    <li class="link"><a href="../../photos">Photos</a></li>
                    <li class="link"><a href="../../news">Actualités</a></li>
                    <li class="link"><a href="../../contact">Nous contacter</a></li>
                    <li class="link"><a href="../../boutique">Boutique</a></li>
                </ul>
            </div>    
            <img src="../../bars-solid.png" class="menu"> 
        </nav>
        <div class="content">
            <div class="body">        
                <h2>Section Sauvetage</h2>
                    <section class="about">
                        <h3><?= getContentByClass($pdo, 'about.h3') ?></h3>
                        <p>
                            <ul class="coachs">
                                <?= getContentByClass($pdo, 'about.ul') ?>
                            </ul>
                    </section>
                    <section class="about">
                        <h3>Horaires</h3>
                        <p>
                            <span class="horaires"><?= getContentByClass($pdo, 'horaires.jour') ?></span> <?= getContentByClass($pdo, 'horaires.heure') ?>
                        </p>
                    </section>
                    <section class="about">
                        <h3><?= getContentByClass($pdo, 'inscription.h3') ?></h3>
                        <p><?= getContentByClass($pdo, 'inscription.p') ?></p>
                    </section>
            </div>
        </div>
    </body>
    <script src="https://kit.fontawesome.com/aa12b9fbf5.js" crossorigin="anonymous"></script>
    <script>
        const menuHamburger = document.querySelector(".menu")
    const navLinks = document.querySelector(".navlinks")
    let clicked=false;
    var newsource="../../xmark.svg";

    menuHamburger.addEventListener('click', () => {
        navLinks.classList.toggle('mobile-menu')
        if (clicked===false){
            menuHamburger.src=newsource
            clicked=true;
        } else{
            menuHamburger.src="../../bars-solid.png"
            clicked=false;
        }

    })
    </script>
</html>