<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Marketplace : Dashboard</title>
</head>

<body>
    <header>
        <div class="logo">
            <p>Dashboard</p>
            <a href="logout.php">Déconnexion</a>
        </div>
        <nav>
            <a href="../orders/orders_html.php">Commandes</a>
            <a href="../products/products_html.php">Mes produits</a>
            <a href="../products/catalog_html.php">Catalogue</a>
            <a href="../products/add_product_html.php">Ajouter un produit</a>
            <a href="../products/cart_html.php">Panier</a>
        </nav>
    </header>
    <section>
        <div class="hero-copy">
            <p class="eyebrow">Espace vendeur</p>
            <h1>Gérez votre activité simplement.</h1>
            <p>Ajoutez vos produits, suivez vos ventes et gardez une vue claire sur les commandes.</p>
        </div>
        <div class="stat-grid">
            <div class="card stat-card">
                <span>Produits publiés</span>
                <strong id="result">—</strong>
            </div>
            <a class="card action-card" href="../products/add_product_html.php"><strong>Ajouter un produit</strong><span>Présentez une nouvelle offre</span></a>
            <a class="card action-card" href="../orders/orders_html.php"><strong>Voir les commandes</strong><span>Suivez leur progression</span></a>
        </div>
    </section>


    <script>
        fetch("dashboard_php.php", {
            method: "POST",
            headers: {
                "content-type": "application/json"
            }
        })
        .then(res => res.json())
        .then(data => {
            if (data.success === false) {
                window.location.href = "login_html.php";
                return;
            }
            document.getElementById("result").textContent = data.result;

        })
        .catch(error => {
            console.log(err);
        });

    </script>

</body>

</html>