<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <link rel="stylesheet" href="../style/style.css">
    <title>Marketplace : Mes produits</title>
</head>

<body>

    <header class="site-header"><a class="brand" href="../auth/dashboard_html.php">Marché local</a><nav><a href="products_html.php">Mes produits</a><a href="add_product_html.php">Ajouter</a><a href="../orders/orders_html.php">Commandes</a><a href="../auth/logout.php">Déconnexion</a></nav></header>
    <main>
        <div class="hero-copy"><p class="eyebrow">Espace vendeur</p><h1>Mes produits.</h1><p>Modifiez vos offres ou retirez celles qui ne sont plus disponibles.</p></div>
        <div id="message" class="form-message"></div><div id="products" class="product-grid"></div>
    </main>

    <script>
        const card = document.querySelector("#products");
        const message = document.querySelector("#message");
        const escapeHtml = value => String(value ?? '').replace(/[&<>'"]/g, char => ({'&':'&amp;','<':'&lt;','>':'&gt;',"'":'&#039;','"':'&quot;'}[char]));

        async function send(payload) { const response = await fetch('products_php.php', {method:'POST', headers:{'Content-Type':'application/json'}, body:JSON.stringify(payload)}); const data = await response.json(); message.textContent = data.message || ''; if (data.success) loadProducts(); }
        function loadProducts() {
            return fetch("products_php.php").then(res => res.json()).then(data => {
                if (data.success === false) { if (data.message === "Utilisateur non connecté !") window.location.href = "../auth/login_html.php"; message.textContent = data.message; return; }
                if (!data.products.length) { card.innerHTML = '<div class="empty">Aucun produit. <a href="add_product_html.php">Créer votre première offre</a></div>'; return; }
                card.innerHTML = data.products.map(product => `<article class="card product-card"><img src="${escapeHtml(product.product_image)}" alt="${escapeHtml(product.product_name)}"><div class="product-body"><h3>${escapeHtml(product.product_name)}</h3><p>${escapeHtml(product.product_description || 'Aucune description')}</p><div class="price">${Number(product.product_price).toFixed(2)} €</div><div class="product-actions"><button class="btn secondary edit" data-id="${product.id}" data-name="${escapeHtml(product.product_name)}" data-description="${escapeHtml(product.product_description || '')}" data-price="${product.product_price}">Modifier</button><button class="btn danger delete" data-id="${product.id}">Supprimer</button></div></div></article>`).join('');
                document.querySelectorAll('.delete').forEach(button => button.addEventListener('click', async () => { if (confirm('Supprimer ce produit ?')) await send({action:'delete', id:button.dataset.id}); }));
                document.querySelectorAll('.edit').forEach(button => button.addEventListener('click', async () => { const name=prompt('Nom du produit',button.dataset.name), price=prompt('Prix',button.dataset.price), description=prompt('Description',button.dataset.description); if(name!==null&&price!==null&&description!==null) await send({action:'update',id:button.dataset.id,name,price,description}); }));
            });
        }
        loadProducts();
    </script>

</body>

</html>