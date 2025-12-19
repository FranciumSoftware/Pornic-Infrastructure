<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../style.css">
        <link rel="stylesheet" href="style.css">
        <link rel="stylesheet" href="font/css/fontawesome.css">
        <link rel="shortcut icon" href="../Logo.png" type="image/png">
        <title>Pornic Natation pays de Retz</title>
    </head>
    <body>
        <nav class="navbar">
        <a href="../" class="logo"><img class="icon" src="../Icon.png" alt="Logo du club"></a>
            <div class="navlinks">
                <ul>
                    <li class="link"><a href="../">Accueil</a></li>
                    <li class="link"><a href="" class="active">Notre club</a></li>
                    <li class="link"><a href="../photos">Photos</a></li>
                    <li class="link"><a href="../news">Actualités</a></li>
                    <li class="link"><a href="../contact">Nous contacter</a></li>
                    <li class="link"><a href="../boutique">Boutique</a></li>
                </ul>
            </div>    
            <img src="../bars-solid.png" class="menu"> 
        </nav>
        <div class="content">
            <div class="body">        
                <h2>Bienvenue sur le site de Pornic Natation Pays de Retz</h2>
                    <section class="about">
                        <h3>Sélectionnez une catégorie :</h3>
                        <div class="wrapper">
                            <div class="element el1"><a href="Jeunes">Groupe Jeunes</a></div>
                            <div class="element el2"><a href="Maitres">Groupe Maîtres</a></div>
                            <div class="element el3"><a href="Sauvetage">Section Sauvatage</a></div>
                            <div class="element el4"><a href="Bureau">Le Bureau</a></div>
                        </div>
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