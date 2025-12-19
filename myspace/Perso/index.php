<?php
/*
* 2025 MySpace sous licence MIT
* (c) Pornic Natation toutes personne utilisant ce code se doit de retirer toutes mentions faisant
* référence au nom de l'association en question
*
* --------------Informations du fichier--------------
* Perso/index.php
* Page principale pour afficher les détails de l'adhérent
* Inclus actions.php
* Utilise les feuilles de styles ../asset/icons.css, ../asset/main.css et ../asset/nav.css
* Utilise la session pour l'autentification 'user'
* Possible redirection vers ../login.php si non autentifié
* /!\ Aucunes informations sensible est exposée et aucune ne doit l'être
* --------------Historique des versions--------------
* v1.0 - 06/12/2025 Création du fichier et des fonctions de base
 */ 
require_once('actions.php');
    if (!isset($_SESSION['user'])) {
        header('Location: ../login.php');
        exit;
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/icons.css">
    <link rel="stylesheet" href="../asset/main.css">
    <link rel="stylesheet" href="../asset/nav.css">
    <link rel="stylesheet" href="asset/member.css">

    <title>Adhésion</title>
</head>
<body>
    <header>
        <span class="h1"><i class="icon1"></i> Adhésion</span>
        <span class="space"></span>
        <span><a href="../logout.php" class="icon1" aria-label="Se déconnecter"></a></span>
    </header>
        <nav>
            <ul>
                <li><a href="../"><i class="icon1"></i> <span>Accueil</span></a></li>
                <li><a href="" class="active"><i class="icon1"></i> <span>Adhésion</span></a></li>
                <li><a href="../Planning/"><i class="icon1"></i> <span>Planning</span></a></li>
                <li><a href="../Sondage/"><i class="icon1"></i> <span>Sondages</span></a></li>
                <li><a href="../Boutique"><i class="icon1"></i> <span>Boutique</span></a></li>
            </ul>
        </nav>
        <div class="core">
            <h2>
                <?php try { ?>
                    <?= htmlspecialchars(getMemberIdBySocid()[0]['firstname']) ?> <?= htmlspecialchars(getMemberIdBySocid()[0]['lastname']) ?>
                <?php } catch (Exception $e) { ?>
                    Membre inconnu
                <?php } ?>
            </h2>
            <div class="details">
                <ul>
                    <li><strong>Adresse</strong> <?= htmlspecialchars(getMemberIdBySocid()[0]['address'] ?? 'Non défini') ?> <?= htmlspecialchars(getMemberIdBySocid()[0]['town']) ?> <button>Modifier</button></li>
                    <li><strong>Téléphone</strong> <?= htmlspecialchars(getMemberIdBySocid()[0]['phone'] ?? getMemberIdBySocid()[0]['phone_mobile']) ?>  <button>Modifier</button></li>
                    <li><strong>Date de naissance</strong>
                    <?php if (getMemberIdBySocid()[0]['birth']!= ""): ?>
                        <?= htmlspecialchars(date('d/m/Y',intval(getMemberIdBySocid()[0]['birth']))) ?>
                    <?php else: ?>
                        Poisson panné 🐟
                    <?php endif; ?>  
                    <button>Modifier</button></li>
                    <li><strong>Fait partie de </strong><?= htmlspecialchars(getMemberIdBySocid()[0]['type']) ?>  <button>Modifier</button></li>
                    <li><strong>Email </strong><?= htmlspecialchars(getMemberIdBySocid()[0]['email']) ?? 'non défini' ?>  <button>Modifier</button></li>
                    <li><strong>Adhésion valide jusqu'au </strong><?= htmlspecialchars(date('d/m/Y',getMemberIdBySocid()[0]['datefin'])) ?></li>
                </ul>
            </div>
            <div class="files">
                <h3>Fichiers</h3>
                <?php foreach (getDocs() as $docs): ?>
                    <span><a href="data:<?php echo getDownloadFile($docs['fullpath_orig'], $docs['position'])[0]['filetype'] ?>,"?><?= htmlspecialchars($docs['filename']) ?></a></span>
                <?php endforeach;?>
            </div>
        </div>
</body>
</html>