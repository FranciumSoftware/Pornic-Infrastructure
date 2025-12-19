<?php 
session_start();
if (!isset($_SESSION['user'])) {
        header('Location: login.php');
        exit;
    }?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="asset/icons.css">
    <link rel="stylesheet" href="asset/main.css">
    <link rel="stylesheet" href="asset/nav.css">

    <title>Accueil</title>
</head>
<body>
    <header>
        <span class="h1"><i class="icon1"></i> Accueil</span>
        <span class="space"></span>
        <span><a href="logout.php" class="icon1" aria-label="Se déconnecter"></a></span>
    </header>
    <?php echo $_SESSION['user']['socid']; ?>
        <nav>
            <ul>
                <li><a href="" class="active"><i class="icon1"></i> <span>Accueil</span></a></li>
                <li><a href="Perso"><i class="icon1"></i> <span>Adhésion</span></a></li>
                <li><a href="Planning/"><i class="icon1"></i> <span>Planning</span></a></li>
                <li><a href="Sondage/"><i class="icon1"></i> <span>Sondages</span></a></li>
                <li><a href="Boutique"><i class="icon1"></i> <span>Boutique</span></a></li>
            </ul>
        </nav>
</body>
</html>