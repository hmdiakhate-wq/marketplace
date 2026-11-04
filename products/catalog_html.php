<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css"><title>Marketplace : Catalogue</title>
</head>
<body>
<header class="site-header"><a class="brand" href="../auth/dashboard_html.php">Marché local</a><nav><a href="catalog_html.php">Catalogue</a><a href="cart_html.php">Mon panier</a><a href="../orders/orders_html.php">Mes commandes</a><a href="../auth/logout.php">Déconnexion</a></nav></header>
<main>
    <div class="hero-copy"><p class="eyebrow">Découvrir</p><h1>Des produits choisis avec soin.</h1><p>Explore les offres de nos vendeurs et trouve ce qu'il te faut.</p></div>
    <form class="toolbar" id="search-form"><input id="search" type="search" placeholder="Rechercher un produit..." aria-label="Rechercher un produit"><button class="btn" type="submit">Rechercher</button></form>
    <div id="message" class="form-message"></div><div id="products" class="product-grid"></div>
</main>
<script>
const products = document.getElementById('products');
const message = document.getElementById('message');
function escapeHtml(value) { return String(value ?? '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char])); }
async function loadProducts() {
    const query = encodeURIComponent(document.getElementById('search').value.trim());
    const response = await fetch(`catalog_php.php?q=${query}`);
    const data = await response.json();
    if (!data.success) { message.textContent = data.message; return; }
    if (!data.products.length) { products.innerHTML = '<div class="empty">Aucun produit ne correspond à cette recherche.</div>'; return; }
    products.innerHTML = data.products.map(product => `<article class="card product-card"><img src="${escapeHtml(product.product_image)}" alt="${escapeHtml(product.product_name)}"><div class="product-body"><p class="eyebrow">Par ${escapeHtml(product.seller_name)}</p><h3>${escapeHtml(product.product_name)}</h3><p>${escapeHtml(product.product_description || 'Aucune description')}</p><div class="price">${Number(product.product_price).toFixed(2)} €</div><div class="product-actions"><button class="btn" data-id="${product.id}">Ajouter au panier</button></div></div></article>`).join('');
    document.querySelectorAll('[data-id]').forEach(button => button.addEventListener('click', async () => {
        const response = await fetch('cart_php.php', {method: 'POST', headers: {'Content-Type': 'application/json'}, body: JSON.stringify({action: 'add', product_id: button.dataset.id})});
        const result = await response.json();
        if (result.success) { button.textContent = 'Ajouté'; button.classList.add('secondary'); } else if (result.login) window.location.href = '../auth/login_html.php'; else message.textContent = result.message;
    }));
}
document.getElementById('search-form').addEventListener('submit', event => { event.preventDefault(); loadProducts(); });
loadProducts().catch(() => message.textContent = 'Le catalogue est momentanément indisponible.');
</script>
</body>
</html>
