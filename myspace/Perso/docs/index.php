<?php
/*
* 2025 MySpace sous licence MIT
* (c) Pornic Natation toutes personne utilisant ce code se doit de retirer toutes mentions faisant
* référence au nom de l'association en question
*
* --------------Informations du fichier--------------
* Perso/docs/index.php
* Page principale pour afficher les documents
* Inclus actions.php
* Utilise les feuilles de styles ../../asset/icons.css, ../../asset/main.css et ../../asset/nav.css
* Utilise la session pour l'autentification 'user'
* Possible redirection vers ../login.php si non autentifié
* /!\ Aucunes informations sensible est exposée et aucune ne doit l'être
* --------------Historique des versions--------------
* v1.0 - 18/12/2025 Création du fichier et des fonctions de base
 */ 
require_once('../actions.php');
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
    <title>Documents</title>
    <link rel="stylesheet" href="../../asset/icons.css">
    <link rel="stylesheet" href="../asset/main.css">
    <link rel="stylesheet" href="../asset/nav.css">
    <link rel="stylesheet" href="asset/member.css">
</head>
<body>
    
</body>
</html>