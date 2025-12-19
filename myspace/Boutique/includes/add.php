<?php
session_start();

// Initialisation du panier si besoin
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [
        'id' => [],
        'qte' => [],
        'prix' => []
    ];
}

// Récupération des données du formulaire
$id = isset($_POST['id']) ? $_POST['id'] : null;
$qte = isset($_POST['qte']) ? (int)$_POST['qte'] : 0;
$prix = isset($_POST['prix']) ? (float)$_POST['prix'] : 0.0;

if ($id !== null && $qte > 0 && $prix > 0) {
    // Ajout des données au panier
    $_SESSION['panier']['id'][] = $id;
    $_SESSION['panier']['qte'][] = $qte;
    $_SESSION['panier']['prix'][] = $prix;
}
// If this is an AJAX request, return JSON instead of redirecting
$isAjax = false;
if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
    $isAjax = true;
}
if (!$isAjax) {
    // Redirection vers la page principale
    header('Location: ../');
    exit;
} else {
    header('Content-Type: application/json; charset=UTF-8');
    echo json_encode([
        'success' => true,
        'cart_count' => count($_SESSION['panier']['id'])
    ]);
    exit;
}
