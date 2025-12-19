<?php
require_once '../config.php';

function getContentByClass(PDO $pdo, string $class) {
    $stmt = $pdo->prepare("SELECT content FROM contact WHERE class = ?");
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
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="font/css/fontawesome.css">
        <link rel="shortcut icon" href="../Logo.png" type="image/png">
        <title>Pornic Natation pays de Retz</title>
        <style>
            .social{
                height:auto;
                padding:20px;
            }
            i{
                margin-right:10px;
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
                    <li class="link"><a href="../photos">Photos</a></li>
                    <li class="link"><a href="../news">Actualités</a></li>
                    <li class="link"><a href=""  class="active">Nous contacter</a></li>
                    <li class="link"><a href="../boutique">Boutique</a></li>
                </ul>
            </div>    
            <img src="../bars-solid.png" class="menu"> 
        </nav>
        <div class="content">
            <div class="body">        
                <h2>Nous contacter</h2>
                    <section class="about">
                        <h3>E-mail:</h3>
                        <p>
                            <?= getContentByClass($pdo, 'mail.p') ?>
                        </p>
                    </section>
                    <section class="about">
                        <h3>Téléphone</h3>
                        <p>
                            <?= getContentByClass($pdo, 'tel.p') ?>
                        </p>
                    </section>
                    <section class="social">
                        <a href="<?= getContentByClass($pdo, 'facebook') ?>" target="_blank"><i class="fa-brands fa-facebook fa-3x" style="color: #005eff;"></i></a><a href="<?= getContentByClass($pdo, 'insta') ?>" target="_blank"><i class="fa-brands fa-instagram fa-3x" style="color: #ff0080;"></i></a>
            </div>
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
</html>