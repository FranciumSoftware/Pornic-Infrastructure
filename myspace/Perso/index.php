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
            <div class="files">
                <h3><i class="icon1"></i> Civilité</h3>
                <div class="content">
                    <span><strong>Date de Naissance </strong><?php if (getMemberIdBySocid()[0]['birth']!= ""): ?>
                        <?= htmlspecialchars(date('d/m/Y',intval(getMemberIdBySocid()[0]['birth']))) ?>
                    <?php else: ?>
                        Poisson panné 🐟
                    <?php endif; ?>
                </span>
                <span><strong>Adresse</strong> <?= htmlspecialchars(getMemberIdBySocid()[0]['address'] ?? 'Non défini') ?> <?= htmlspecialchars(getMemberIdBySocid()[0]['town']) ?> <button><i class="icon1"></i></button></span>
                <span><strong>Nationalité</strong> <?= htmlspecialchars(getMemberIdBySocid()[0]['country_code']) ?></span>
                
                </div>
                
            </div>
            <div class="files">
                <h3><i class="icon1"></i> Contact</h3>
                <div class="content">
                    <span><strong>Téléphone</strong> <?= htmlspecialchars(getMemberIdBySocid()[0]['phone'] ?? getMemberIdBySocid()[0]['phone_mobile']) ?>  <button><i class="icon1"></i></button></span>
                    <span><strong>Email </strong><?= htmlspecialchars(getMemberIdBySocid()[0]['email']) ?? 'non défini' ?>  <button><i class="icon1"></i></button></span>
                </div>
            </div>
            <div class="files">
                <h3><i class="icon1"></i> Adhésion</h3>
                <div class="content">
                    <span><strong>Rôle</strong> <?= htmlspecialchars(getMemberIdBySocid()[0]['type']) ?></span>
                    <span><strong>Valide jusqu'au</strong> <?= htmlspecialchars(date('d/m/Y', getMemberIdBySocid()[0]['last_subscription_date_end'])) ?></span>
                    <span><strong>Montant de la dernière adhésion</strong> <?= htmlspecialchars(getMemberIdBySocid()[0]['last_subscription_amount']) ?></span>
                </div>
            </div>
            <div class="files">
                <h3><i class="icon1"></i> Fichiers</h3>
                <?php foreach (getDocs() as $docs): ?>
                    <span><a href="<?= SITE_ROOT ?>document.php?hashp=<?= $docs['share'] ?>"><?= htmlspecialchars($docs['filename']) ?></a></span><br>
                <?php endforeach;?>
            </div>
        </div>
</body>
</html>