<?php
session_start();
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [
        'id' => [],
        'qte' => [],
        'prix' => []
    ];
}
require_once '../../api.php';
?>
    <span class="titlebar">
        <h1>Panier</h1>
        <a onclick="hideCart()" class="xmark"><i class="fa-solid fa-xmark"></i></a>
    </span>
    
    
    <?php if (count($_SESSION['panier']['id']) == 0): ?>
        <p>Votre panier est vide.</p>
    <?php else: ?>
        <ul>
            <?php for ($i = 0; $i < count($_SESSION['panier']['id']); $i++): ?>
                <li>
                    <img src="<?=htmlspecialchars(articleImage($_SESSION['panier']['id'][$i])) ?>" alt="Image de l'article <?= htmlspecialchars(articleName($_SESSION['panier']['id'][$i])) ?>"><br>
                    <span class="name"><?= htmlspecialchars(articleName($_SESSION['panier']['id'][$i])) ?></span><br>
                    <?= htmlspecialchars((float)$_SESSION['panier']['prix'][$i]) ?> € à l'unité<br>
                    <div class="actions">
                        <form action="includes/correct.php" method="post">
                        <input type="hidden" name="id" value="<?= htmlspecialchars($_SESSION['panier']['id'][$i]) ?>">
                        <div class="quantity-selector-small">
                            <button type="button" onclick="changeQty(-1)" id="p">−</button>
                            <input type="number" name="qte" id="qte" value="<?= htmlspecialchars((int)$_SESSION['panier']['qte'][$i]) ?>" min="1">
                            <button type="button" onclick="changeQty(1)" id="m">+</button>
                        </div>
                        <input type="submit" value="Modifier la quantité">
                    </form>
                    <button
                        class="supprimer-article"
                        onclick="supprimerArticle(<?= htmlspecialchars($_SESSION['panier']['id'][$i], ENT_QUOTES) ?>)"
                    >Supprimer</button>
                    </div>
                    
                    <br>

                    Prix total pour l'article:
                    <?= htmlspecialchars((float)($_SESSION['panier']['qte'][$i] * $_SESSION['panier']['prix'][$i])) ?> €
                    
                </li>
            <?php endfor; ?>
        </ul>
        <form action="includes/order.php" method="post" onsubmit="showLoader()">
            <input type="submit" value="Commander">
        </form>
        <script>
            function correctQty(delta) {
                const input = document.getElementById('qt');
                let value = parseInt(input.value) || 1;
                value = Math.max(1, value + delta);
                input.value = value;
            }

        </script>
    <?php endif; ?>

