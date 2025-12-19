<?php 
session_start();
require_once '../api.php';
if (!isset($_SESSION['user'])) {
    header('Location: ../login.php');
}
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = [
        'id' => [],
        'qte' => [],
        'prix' => []
    ];
}
if (isset($_GET['del'])) {
    require_once 'includes/del.php';
    supprim_article($_GET['del']);
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../asset/icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css" integrity="sha512-2SwdPD6INVrV/lHTZbO2nodKhrnDdJK9/kg2XD1r9uGqPo1cUbujc+IYdlYdEErWNu69gVcYgdxlmVmzTWnetw==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <link rel="stylesheet" href="../asset/main.css">
    <link rel="stylesheet" href="../asset/nav.css">
    <link rel="stylesheet" href="asset/css/side.css">
    <script src="asset/js/app.js"></script>
    <title>Boutique</title>
</head>
<body>
    <!-- Loader overlay (visible by default, hidden by JS when page ready) -->
    <div id="pageLoader" class="page-loader" role="status" aria-live="polite" aria-busy="true"
         style="position:fixed;top:0;left:0;width:100%;height:100%;display:flex;align-items:center;justify-content:center;background-color:rgba(255,255,255,0.95);color:#333;font-size:1.25rem;z-index:99999;">
        <!-- Loader image will cycle through frames for a stop-motion effect -->
        <img id="loaderFrame" src="asset/icons/panier.svg" alt="Chargement" style="width:120px;height:auto;display:block;margin-bottom:8px;" />
        <span class="sr-only">Chargement...</span>
    </div>
    <script>
        // Ensure LOADER_FRAMES exists as early as possible and optionally preload first frame
        window.LOADER_FRAMES = window.LOADER_FRAMES || [];
        if (Array.isArray(window.LOADER_FRAMES) && window.LOADER_FRAMES.length > 0) {
            // preload the first frame immediately to avoid delay
            const img = new Image();
            img.src = window.LOADER_FRAMES[0];
        }
    </script>
    <header>
        <span class="h1"><i class="icon1"></i> Boutique</span>
        <span class="space"><form action="./" method="post"><input type="text" name="query" placeholder="rechercher un article..." value="<?php if (isset($_POST['query'])){echo $_POST['query'];} ?>">
        <?php if(isset($_POST['query'])): ?>
        <input type="button" class="icon1" value="" onclick="window.location='.'"></input>
        <?php endif; ?>
        <input type="submit" class="icon1" value="" aria-label="Rechercher"></input></form></span>
        <span><a href="../logout.php" class="icon1" aria-label="Se déconnecter"></a></span>
        <span><a href="javascript:void(0)" onclick="showCart()" class="icon1" aria-label="Afficher le panier"></a></span>
    </header>
        <nav>
            <ul>
                <li><a href="../"><i class="icon1"></i> <span>Accueil</span></a></li>
                <li><a href="../Perso"><i class="icon1"></i> <span>Adhésion</span></a></li>
                <li><a href="../Planning/"><i class="icon1"></i> <span>Planning</span></a></li>
                <li><a href="../Sondage/"><i class="icon1"></i> <span>Sondages</span></a></li>
                <li><a href="" class="active"><i class="icon1"></i> <span>Boutique</span></a></li>
            </ul>
        </nav>
        <?php if (isset($_POST['query'])):?>
        <?php if (isset($_POST['query']) || searchProduct($_POST['query']==null)):?>
        <div class="no-results">
            <i class="icon1 warning" aria-label="Attention"></i> Aucun résultat pour "<?=htmlspecialchars($_POST['query'])?>"
        </div>
    <div class="articles">
        
            <?php $e=0; ?>
            
            <?php else: 
                foreach(searchProduct($_POST['query']) as $product): ?>
                

                <div>
                    <script>
                        // searchProduct now returns full product objects — safely escape with json_encode
                        addToList(<?= json_encode($product->id) ?>, <?= json_encode($product->label ?? '') ?>, <?= json_encode(number_format((float)($product->price ?? 0), 2, '.', '')) ?>, <?= json_encode(articleImage($product->id)) ?>, <?= json_encode($product->description ?? '') ?>);
                    </script>
                    <a href="javascript:void(0)" onclick="clickedArticle(<?=htmlspecialchars($e) ?>)">
                        <img src="<?=htmlspecialchars(articleImage($product->id)) ?>" alt="">
                        <?=htmlspecialchars($product->label ?? $product->name ?? '') ?>
                    </a>
                </div>
                <?php $e++; ?>
            <?php endforeach?>
            <?php endif; ?>
        <?php else: ?>
        <?php $i = 0; ?>
        <div class="articles">
        <?php foreach(getListProducts() as $product): ?>
            <div>
                <script>
                    // product is an id in this loop — use json_encode to safely embed values for JS
                    addToList(<?= json_encode($product) ?>, <?= json_encode(articleName($product)) ?>, <?= json_encode(number_format((float)articlePrice($product), 2, '.', '')) ?>, <?= json_encode(articleImage($product)) ?>, <?= json_encode(articleDetails($product)) ?>);
                </script>
                <a href="javascript:void(0)" onclick="clickedArticle(<?=htmlspecialchars($i) ?>)">
                    <img src="<?=htmlspecialchars(articleImage($product)) ?>" alt="">
                    <?=htmlspecialchars(articleName($product)) ?>
                </a>
            </div>
        
        <?php $i++; ?>
        <?php endforeach?>
        </div>
        <?php endif;?>
        <script>
            // Appeler une fois après avoir rempli la liste client-side
            if (typeof callArrays === 'function') callArrays();
        </script>
        </div>
        <div class="panier" style="display:none">
            <div class="sub">
                
            </div> 
        </div>
    
    <div class="sidepanel" id="detailsPanel" style="display:none">
        <a onclick="hide()" class="close"><i class="fa-solid fa-xmark"></i></a>
        
        <h2 class="name" id="artName"></h2>
        <img src="" alt="" id="image">
        <h3 id="prix"></h3>
        <p id="desc"></p>
        <form action="includes/add.php" method="post" id="addToCartForm">
            <input type="hidden" name="id" value="" id="idArticle">
            <input type="hidden" name="prix" value="" id="prixArticle">
            <div class="quantity-selector">
                <button type="button" onclick="changeQty(-1)" id="p">−</button>
                <input type="number" name="qte" id="qte" value="1" min="1">
                <button type="button" onclick="changeQty(1)" id="m">+</button>
            </div>
            <input type="submit" value="Ajouter au panier">
        </form>
    </div>
<script>
    function changeQty(delta) {
        const input = document.getElementById('qte');
        let value = parseInt(input.value) || 1;
        value = Math.max(1, value + delta);
        input.value = value;
    }

</script>
<script>
  window.LOADER_FRAMES = [
    'asset/loader/1.svg',
    'asset/loader/2.svg',
    'asset/loader/3.svg',
    'asset/loader/4.svg',
    'asset/loader/5.svg',
    'asset/loader/6.svg',
    'asset/loader/7.svg',
    'asset/loader/8.svg',
    'asset/loader/9.svg',
    'asset/loader/10.svg',
    'asset/loader/11.svg',
    'asset/loader/12.svg',
    'asset/loader/13.svg',
    'asset/loader/14.svg',
    'asset/loader/15.svg',
    'asset/loader/16.svg',
    'asset/loader/17.svg',
    'asset/loader/18.svg'

    // etc.
  ];
</script>
<script src="asset/js/interact.js"></script>
</body>
</html>