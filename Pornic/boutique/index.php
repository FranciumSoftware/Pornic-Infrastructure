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
            #haWidget{
                height:75vh;
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
                    <li class="link"><a href="../contact"  >Nous contacter</a></li>
                    <li class="link"><a href="" class="active">Boutique</a></li>
                </ul>
            </div>    
            <img src="../bars-solid.png" class="menu"> 
        </nav>
        <div class="content">
            <div class="body">        
                    <section class="about">
                        <p>
                            <iframe id="haWidget" allowtransparency="true" src="https://www.helloasso-sandbox.com/associations/pornic-natation-pays-de-retz/boutiques/boutique-principale/widget" style="width: 100%; border: none;" onload="window.addEventListener( 'message', function(e) { const dataHeight = e.data.height; const haWidgetElement = document.getElementById('haWidget'); haWidgetElement.height = dataHeight + 'px'; } )" ></iframe>                        </p>
                    </section>
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