<?php
/*
* 2025 MySpace sous licence MIT
* (c) Pornic Natation toutes personne utilisant ce code se doit de retirer toutes mentions faisant
* référence au nom de l'association en question
*
* --------------Informations du fichier--------------
* Planning/actions.php
* Fichier regroupant les fonctions servant à récupérer les événements du calendrier
* Inclus dans Planning/index.php
* Inclus ../config.php et ../api.php
* Utilise la session pour l'autentification 'user'
* /!\ Aucunes informations sensible est exposée et aucune ne doit l'être
* --------------Historique des versions--------------
* v1.0 - 29/11/2025 Création du fichier et des fonctions de base
 */


session_start();
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
    exit;
}
require_once('../config.php');
require_once('../api.php');
// Récupère la liste des évènements sous forme d'un tableau d'IDs pour l'utilisateur connecté
function getListEvents() {
    $userId = getUserIdByLogin($_SESSION['user']['name']);
    if (empty($userId)) {
        return null;
    }
    $url = baseURL . 'agendaevents?sortfield=t.id&sortorder=ASC&limit=100&user_ids=' . urlencode($userId);
    $json = callAPI('GET', $_SESSION['user']['key'], $url);
    $details = json_decode($json, true);
    // Extraire tous les id rencontrés dans la réponse (robuste aux objets/array mixtes)
    
    $ids = [];
    collectIds($details, $ids);
    if (getEventDate($ids[0])>=time()-86400){
        echo "debug : ".getEventDate($ids[0])." >= ".(time()-86400);
        return $ids; // retourne un tableau d'ids (ex: [5,6,7,...])
    }
}

/**
 * Parcours récursif d'une valeur décodée JSON et ajoute toutes les valeurs
 * trouvées dans des clés `id` au tableau $out.
 * Gère les objets stdClass et les tableaux associatifs / indexés.
 */
function collectIds($data, array & $out) {
    if (is_null($data)) return;

    // Si c'est un objet, on peut le parcourir avec foreach
    if (is_object($data)) {
        foreach ($data as $key => $value) {
            if ($key === 'id' && (is_scalar($value) || is_numeric($value))) {
                $out[] = $value;
                continue;
            }
            // récursif si tableau/objet
            if (is_array($value) || is_object($value)) {
                collectIds($value, $out);
            }
        }
        return;
    }

    // Si c'est un tableau
    if (is_array($data)) {
        // cas simple : tableau d'éléments contenant 'id'
        foreach ($data as $key => $value) {
            // si l'élément est un tableau avec 'id'
            if (is_array($value) && array_key_exists('id', $value)) {
                $out[] = $value['id'];
                continue;
            }
            // si l'élément est un objet avec ->id
            if (is_object($value) && (isset($value->id) || property_exists($value,'id'))) {
                $out[] = $value->id;
                continue;
            }
            // sinon on descend récursivement
            if (is_array($value) || is_object($value)) {
                collectIds($value, $out);
            }

            // Parfois la clé est littéralement 'id' au niveau supérieur
            if ($key === 'id' && (is_scalar($value) || is_numeric($value))) {
                $out[] = $value;
            }
        }
    }
}
// Récupère l'ID utilisateur Dolibarr à partir du login. Utile pour getListEvents
function getUserIdByLogin($login) {
    // Appel API et décodage JSON en tableau associatif
    $json = callAPI('GET', $_SESSION['user']['key'], baseURL.'users');

    $users = json_decode($json, true);

    if (is_array($users)) {
        foreach ($users as $user) {
            // gère les réponses en tableau ou objets
            $userLogin = null;
            if (is_array($user)) {
                $userLogin = $user['login'] ?? null;
                $userId = $user['id'] ?? null;
            } else if (is_object($user)) {
                $userLogin = $user->login ?? null;
                $userId = $user->id ?? null;
            }

            if ($userLogin !== null && $userLogin === $login) {
                return $userId;
            }
        }
        // aucun utilisateur trouvé
        return null;
    }

    // erreur lors du décodage ou réponse inattendue
    return null;
}
// Récupère la date de l'évènement sous forme de timestamp Unix
function getEventDate($eventId) {
    $url = baseURL .'agendaevents/'. urlencode($eventId);
    $json = callAPI('GET', $_SESSION['user']['key'], $url);
    $details = json_decode($json, true);

    if (is_array($details) && isset($details['datep'])) {
        return $details['datep'];
    }

    return null;
}
// Récupère le nom de l'évènement
function getEventLabel($eventId){
    $url = baseURL .'agendaevents/'. urlencode($eventId);
    $json = callAPI('GET', $_SESSION['user']['key'], $url);
    $details = json_decode($json, true);
    return $details['label'] ?? null;
}
// Récupère le lieu de l'évènement
function getEventLocation($eventId){
    $url = baseURL .'agendaevents/'. urlencode($eventId);
    $json = callAPI('GET', $_SESSION['user']['key'], $url);
    $details = json_decode($json, true);
    if ($details==""){
        return 'Aucun lieu spécifié';
    }
    return $details['location'];
}

function getUpcomingEvents() {
    $userId = getUserIdByLogin($_SESSION['user']['name']);
    if (empty($userId)) {
        return [];
    }
    $url = baseURL . 'agendaevents?sortfield=t.id&sortorder=ASC&limit=100&user_ids=' . urlencode($userId);
    $json = callAPI('GET', $_SESSION['user']['key'], $url);
    $events = json_decode($json, true);
    $upcoming = [];
    $now = time();
    if (is_array($events)) {
        foreach ($events as $event) {
            if (isset($event['datep']) && $event['datep'] > $now) {
                $upcoming[] = $event;
            }
        }
    }
    return $upcoming;
}
