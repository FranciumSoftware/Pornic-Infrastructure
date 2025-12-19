<?php
session_start();
header('Content-Type: application/json'); // Indique que la réponse est en JSON

/**
 * Supprimer un article du panier
 *
 * @param string $ref_article ID de l'article à supprimer
 * @return bool TRUE si suppression effectuée, FALSE sinon
 */
function supprim_article($ref_article)
{
    if (!isset($_SESSION['panier'])) {
        return false;
    }
    $panier_tmp = ['id' => [], 'qte' => [], 'prix' => []];
    $nb_articles = count($_SESSION['panier']['id']);
    $suppression = false;
    for ($i = 0; $i < $nb_articles; $i++) {
        if ($_SESSION['panier']['id'][$i] != $ref_article) {
            $panier_tmp['id'][] = $_SESSION['panier']['id'][$i];
            $panier_tmp['qte'][] = $_SESSION['panier']['qte'][$i];
            $panier_tmp['prix'][] = $_SESSION['panier']['prix'][$i];
        } else {
            $suppression = true;
        }
    }
    $_SESSION['panier'] = $panier_tmp;
    return $suppression;
}

// Gère une requête POST (pour AJAX)
if (isset($_POST['id'])) {
    $result = supprim_article($_POST['id']);
    echo json_encode([
        'success' => $result,
        'message' => $result ? 'Article supprimé avec succès.' : 'Article non trouvé dans le panier.'
    ]);
    exit;
}

// Gère une requête GET (pour compatibilité)
if (isset($_GET['id'])) {
    $result = supprim_article($_GET['id']);
    header('Location: ../');
    exit;
}
?>
