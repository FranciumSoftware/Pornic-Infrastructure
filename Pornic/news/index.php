<?php
require_once '../config.php';

$stmt = $pdo->query("SELECT * FROM news ORDER BY Date DESC");
$news = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="font/css/fontawesome.css">
        <link rel="stylesheet" href="acticle.css">
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
                    <li class="link"><a href="" class="active">Actualités</a></li>
                    <li class="link"><a href="../contact">Nous contacter</a></li>
                    <li class="link"><a href="../boutique">Boutique</a></li>
                </ul>
            </div>    
            <img src="../bars-solid.png" class="menu"> 
        </nav>
        <div class="content">
            <div class="body">        
                <h2>Actualités</h2>
                <section class="cont">
                    <?php foreach ($news as $post): ?>
                        <article class="news-post">
                            <section class="head">
                                <h3><?= htmlspecialchars($post['Title']) ?></h3>
                                <p class="date"><?= date('d/m/Y', strtotime($post['Date'])) ?></p>
                            </section>
                            <hr>
                            <div class="news-content"><?= $post['Content'] ?></div>
                        </article>
                    <?php endforeach; ?>
                </section>
                    
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