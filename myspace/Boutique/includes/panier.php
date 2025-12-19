<?php
function montant_panier()
{
    /* On initialise le montant */
    $montant = 0;
    /* Comptage des articles du panier */
    $nb_articles = count($_SESSION['panier']['id']);
    /* On va calculer le total par article */
    for($i = 0; $i < $nb_articles; $i++)
    {
        $montant += $_SESSION['panier']['qte'][$i] * $_SESSION['panier']['prix'][$i];
    }
    /* On retourne le résultat */
    return $montant;
}
function vider_panier()
{
    $vide = false;
    unset($_SESSION['panier']);
    if(!isset($_SESSION['panier']))
    {
        $vide = true;
    }
    return $vide;
}
$action=$_GET['action'] ?? '';
if ($action == 'vider') {
    vider_panier();
    header('Location: ../');
}  