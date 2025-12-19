<?php
/*
* 2025 MySpace sous licence MIT
* (c) Pornic Natation toutes personne utilisant ce code se doit de retirer toutes mentions faisant
* référence au nom de l'association en question
*
* --------------Informations du fichier--------------
* Perso/actions.php
* Fichier regroupant les fonctions servant à récupérer les événements du calendrier
* Inclus dans Planning/index.php
* Inclus ../config.php et ../api.php
* Utilise la session pour l'autentification 'user'
* /!\ Aucunes informations sensible est exposée et aucune ne doit l'être
* --------------Historique des versions--------------
* v1.0 - 06/12/2025 Création du fichier et des fonctions de base
 */

require_once('../config.php');
require_once('../api.php');
session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}
function getMemberIdBySocid(){
    $url = baseURL . "members?user_id=" . $_SESSION['user']['id'];
    $json = callAPI('GET', $_SESSION['user']['key'], $url);
    $details = json_decode($json, true);
    return $details;
}
function memberState(){
    $memberState = getMemberIdBySocid()[0]['status'];
    switch ($memberState) {
        case -1:
            return "À valider";
        case 1:
            return "Actif";
        case -2:
            return "Exclu";
        case 0:
            return "Résilié";
        default:
            return "Inconnu";
    }
}
function getDocs(){
    $url = "http://localhost/dolibarr/api/index.php/documents?modulepart=member&id=".urlencode($_SESSION['user']['member'])."&limit=100";
    $json = callAPI('GET', $_SESSION['user']['key'] ,$url);
    $cleanedJson = cleanJson($json);
    $details = json_decode($cleanedJson, true);
    return $details;
}
function getDownloadFile($filename, $folder){
    $url=baseURL.'documents/download?modulepart=member&original_file='.$folder.'/'.urlencode($filename);
    $json = callAPI('GET', $_SESSION['user']['key'] ,$url);
    $details = json_decode($json, true);
    return $details;
}