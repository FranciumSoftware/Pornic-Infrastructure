function clickedArticle(id){
    document.getElementById("detailsPanel").style.display="block";
    document.getElementById("artName").innerText=articleName[id];
    document.getElementById("image").src=articleImage[id];
    document.getElementById("prix").innerText=articlePrice[id]+" €";
    document.getElementById("desc").innerText=articleDescription[id];
    document.getElementById("idArticle").value=arcticleID[id];
    document.getElementById("prixArticle").value=articlePrice[id];
}
function hide(){
    document.getElementById("detailsPanel").style.display="none";
}
// Fonction pour recharger le contenu du panier
function rechargerPanier() {
    showLoader();
    fetch('includes/get_cart.php')
        .then(response => response.text())
        .then(html => {
            document.querySelector('.panier .sub').innerHTML = html;
        })
        .catch(error => {
            console.error('Erreur détaillée:', error);
        })
        .finally(() => {
            hideLoader();
        });
}

// Loader animation state
let __loaderFrames = window.LOADER_FRAMES || [];
let __loaderIndex = 0;
let __loaderInterval = null;
const __loaderIntervalMs = 120; // frame rate for stop-motion (ms)


// Appelle la fonction au chargement de la page
document.addEventListener('DOMContentLoaded', function(){
    // rechargerPanier gère le loader
    rechargerPanier();
    // fallback: ensure loader hidden after a short timeout if something unexpected happens
    setTimeout(hideLoader, 5000);
    // Preload loader frames for smooth stop-motion animation
    preloadLoaderFrames();
    // Ensure toast container exists
    ensureToastContainer();
});


// Écouteur pour les formulaires de modification de quantité
document.addEventListener('submit', function(e) {
    // show loader for quantity-correct form (AJAX) and for order form (full page submit)
    if (e.target.action.includes('includes/correct.php')) {
        e.preventDefault();
        const formData = new FormData(e.target);
        showLoader();
        fetch(e.target.action, {
            method: 'POST',
            body: formData
        })
        .then(response => response.text())
        .then(() => {
            rechargerPanier(); // Recharge le panier après modification
        })
        .catch(error => {
            console.error('Erreur:', error);
        })
        .finally(() => hideLoader());
    } else if (e.target.action && e.target.action.includes('includes/order.php')) {
        // allow the form to submit normally, but show the loader immediately
        // no preventDefault here because we want a full-page POST
        showLoader();
        // allow submit to continue
    } else if (e.target.action && e.target.action.includes('includes/add.php')) {
        // AJAX add-to-cart: prevent the full-page POST and handle via fetch
        e.preventDefault();
        const form = e.target;
        const formData = new FormData(form);
        // disable submit button to avoid double-clicks
        const submitBtn = form.querySelector('input[type="submit"], button[type="submit"]');
        if (submitBtn) submitBtn.disabled = true;
        showLoader();
        fetch(form.action, {
            method: 'POST',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data && data.success) {
                // close details panel and refresh cart content
                try { hide(); } catch(e){}
                rechargerPanier();
                showToast('Article ajouté au panier');
            } else {
                console.error('Add to cart failed', data);
                showToast('Impossible d\'ajouter au panier', true);
            }
        })
        .catch(err => {
            console.error('AJAX add error', err);
            showToast('Erreur réseau lors de l\'ajout au panier', true);
        })
        .finally(() => {
            hideLoader();
            if (submitBtn) submitBtn.disabled = false;
        });
    }
});
function showCart(){
    document.querySelector(".panier").style.display="block";
}
function hideCart(){
    document.querySelector(".panier").style.display="none";
}
// Fonction pour supprimer un article
function supprimerArticle(articleId) {
    console.log('Suppression de l\'article avec ID:', articleId); // Debug
    showLoader();
    fetch('includes/del.php', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: `id=${encodeURIComponent(articleId)}`
    })
    .then(response => {
        if (!response.ok) {
            throw new Error(`Erreur HTTP! Statut: ${response.status}`);
        }
        return response.json();
    })
    .then(data => {
        if (data.success) {
            rechargerPanier(); // Recharge le panier après suppression
        } else {
            alert(data.message); // Affiche un message d'erreur
        }
    })
    .catch(error => {
        console.error('Erreur:', error);
        alert('Une erreur est survenue lors de la suppression.');
    })
    .finally(() => hideLoader());
}

// Loader control functions
function showLoader(){
    const l = document.getElementById('pageLoader');
    const img = document.getElementById('loaderFrame');
    if (!l) return;
    l.classList.remove('hidden');
    l.setAttribute('aria-busy', 'true');

    // Start stop-motion animation if frames are provided
    if (__loaderInterval) clearInterval(__loaderInterval);
    if (Array.isArray(__loaderFrames) && __loaderFrames.length > 1) {
        // ensure starting index valid
        __loaderIndex = 0;
        if (img) img.src = __loaderFrames[0];
        __loaderInterval = setInterval(() => {
            __loaderIndex = (__loaderIndex + 1) % __loaderFrames.length;
            if (img) img.src = __loaderFrames[__loaderIndex];
        }, __loaderIntervalMs);
    } else if (Array.isArray(__loaderFrames) && __loaderFrames.length === 1) {
        if (img) img.src = __loaderFrames[0];
    }
}
function hideLoader(){
    const l = document.getElementById('pageLoader');
    const img = document.getElementById('loaderFrame');
    if (!l) return;
    l.classList.add('hidden');
    l.setAttribute('aria-busy', 'false');
    if (__loaderInterval) {
        clearInterval(__loaderInterval);
        __loaderInterval = null;
    }
    // optional: reset to first frame
    if (Array.isArray(__loaderFrames) && __loaderFrames.length && img) {
        img.src = __loaderFrames[0];
    }
}

/* Preload function to load all frames into memory for smoother animation */
function preloadLoaderFrames(){
    try {
        __loaderFrames = window.LOADER_FRAMES || [];
        if (!Array.isArray(__loaderFrames) || __loaderFrames.length === 0) return;
        // preload each frame into Image objects
        __loaderFrames = __loaderFrames.map(src => String(src));
        window.__LOADER_PRELOAD = window.__LOADER_PRELOAD || [];
        __loaderFrames.forEach((src, i) => {
            const img = new Image();
            img.src = src;
            window.__LOADER_PRELOAD[i] = img;
        });
    } catch (e) {
        console.warn('Preloading loader frames failed', e);
    }
}

/* Toast helper */
function ensureToastContainer(){
    if (!document.querySelector('.toast-container')){
        const c = document.createElement('div');
        c.className = 'toast-container';
        document.body.appendChild(c);
    }
}
function showToast(message, isError){
    ensureToastContainer();
    const container = document.querySelector('.toast-container');
    const t = document.createElement('div');
    t.className = 'toast';
    if (isError) t.style.background = 'rgba(200,50,50,0.95)';
    t.innerText = message;
    container.appendChild(t);
    // show
    requestAnimationFrame(() => t.classList.add('show'));
    // remove after timeout
    setTimeout(() => {
        t.classList.remove('show');
        setTimeout(() => { try{ container.removeChild(t); }catch(e){} }, 250);
    }, 3000);
}

