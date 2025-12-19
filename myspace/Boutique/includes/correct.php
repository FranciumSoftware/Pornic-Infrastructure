<?php
session_start();

/**
 * Modifie la quantité d'un article dans le panier
 *
 * @param string $ref_article Identifiant de l'article à modifier
 * @param int $qte Nouvelle quantité à enregistrer
 * @return bool TRUE si la modification a bien eu lieu, FALSE sinon
 */
function modif_qte($ref_article, $qte)
{
    if (!isset($_SESSION['panier'])) return false;

    $nb_articles = count($_SESSION['panier']['id']);
    $modifie = false;

    for ($i = 0; $i < $nb_articles; $i++) {
        if ($_SESSION['panier']['id'][$i] == $ref_article) {
            $_SESSION['panier']['qte'][$i] = $qte;
            $modifie = true;
            break; // On peut sortir dès qu'on a trouvé
        }
    }

    return $modifie;
}

// Traitement si appel via formulaire GET ou POST
if (isset($_POST['id']) && isset($_POST['qte'])) {
    $id = $_POST['id'];
    $qte = (int)$_POST['qte'];

    if ($qte > 0) {
        modif_qte($id, $qte);
    }
}

header('Location: ../?action=1');
exit;
?>
