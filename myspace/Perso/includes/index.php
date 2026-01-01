<?php
/*
* 2025 MySpace sous licence MIT
* (c) Pornic Natation toutes personne utilisant ce code se doit de retirer toutes mentions faisant
* référence au nom de l'association en question
*
* --------------Informations du fichier--------------
* Planning/includes/index.php
* Page principale pour afficher les événements du planning
* Utilise la session pour l'autentification 'user'
* /!\ Aucunes informations sensible est exposée et aucune ne doit l'être
* --------------Historique des versions--------------
* v1.0 - 01/01/2026 Création du fichier et des fonctions de base
 */
session_start();
require_once('../../api.php');
switch ($_GET['id']) {
    case 'changeAdresse':
        print_r(changeAdresse($_POST['Adresse'], $_POST['Zip'], $_POST['Town']));
        header('Location: ../');
        exit;
        break;
    case 'changeTelephone':
        print_r(changeTelephone($_POST['Tel']));
        header('Location: ../');
        exit;
        break;
    case 'changeEmail' :
        print_r(changeEmail($_POST['Email']));
        header('Location: ../');
        exit;
        break;
    default:
        header('Location: ../');
        exit;
        break;
}

function changeAdresse($newAdresse, $newZip, $newTown) {
    $member = $_SESSION['user']['member'];
    $data= json_encode([
        'address' => $newAdresse,
        'zip' => $newZip,
        'town' => $newTown
    ]);
    $json = callAPI('PUT',$_SESSION['user']['key'], baseURL."members/{$member}",$data);
    $details = json_decode($json, true);
    return $details;
}
function changeTelephone($newPhone) {
    $member = $_SESSION['user']['member'];
    $data= json_encode([
        'phone' => $newPhone,
        'phone_mobile' => $newPhone
    ]);
    $json = callAPI('PUT',$_SESSION['user']['key'], baseURL."members/{$member}",$data);
    $details = json_decode($json, true);
    return $details;
}
function changeEmail($newEmail) {
    $member = $_SESSION['user']['member'];
    $data= json_encode([
        'email' => $newEmail
    ]);
    $json = callAPI('PUT',$_SESSION['user']['key'], baseURL."members/{$member}",$data);
    $details = json_decode($json, true);
    return $details;
}