<?php
session_start();
require_once('../../api.php');
require_once('../../config.php');
$r=order();
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
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Commande effectuée</title>
</head>
<body>
    <h1>Commande effectuée !</h1>
    <p>Votre commande a été passée avec succès. Vous allez être normalement redirigé vers la page pour procéder au payement.</p>
    <script>
        <?php
            $url = SITE_ROOT . 'public/payment/newpayment.php?source=order&ref=' . urlencode(getPaymantUrl($r));
        ?>
        window.open('<?php echo addslashes($url); ?>', '_blank');
    </script>
    <p><a href="../index.php">Retour à la boutique</a></p>
    <?php vider_panier(); ?>
</body>
</html>
