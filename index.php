<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Marché local, une marketplace simple pour acheter et vendre.">
    <link rel="stylesheet" href="style/style.css">
    <title>Marché local | Marketplace</title>
</head>
<body class="home-page">
    <header class="site-header">
        <a class="brand" href="index.php">Marché local</a>
        <nav aria-label="Navigation principale">
            <a href="products/catalog_html.php">Catalogue</a>
            <a href="auth/login_html.php">Connexion</a>
            <a class="btn secondary" href="auth/register_html.php">Créer un compte</a>
        </nav>
    </header>

    <main>
        <section class="home-hero">
            <div class="home-hero-copy">
                <p class="eyebrow">Acheter. Vendre. Simplement.</p>
                <h1>Le bon produit est peut-être déjà près de vous.</h1>
                <p>Découvrez des produits proposés par des vendeurs indépendants et donnez une nouvelle place à vos propres créations.</p>
                <div class="home-actions">
                    <a class="btn" href="products/catalog_html.php">Explorer le catalogue</a>
                    <a class="text-link" href="auth/register_html.php">Je veux vendre <span aria-hidden="true">&#8594;</span></a>
                </div>
            </div>
            <div class="home-visual" aria-label="Présentation de la marketplace">
                <div class="visual-note"><span class="eyebrow">Aujourd'hui</span><strong>Des offres qui trouvent leur public.</strong></div>
                <div class="visual-stamp">ML<br><small>LOCAL</small></div>
                <div class="visual-line"></div>
            </div>
        </section>

        <section class="home-features" aria-label="Fonctionnalités">
            <article class="feature-item"><span class="feature-number">01</span><h2>Pour les acheteurs</h2><p>Recherchez, ajoutez au panier et commandez en quelques étapes.</p><a class="text-link" href="products/catalog_html.php">Voir les produits <span aria-hidden="true">&#8594;</span></a></article>
            <article class="feature-item"><span class="feature-number">02</span><h2>Pour les vendeurs</h2><p>Publiez vos produits et suivez chaque commande depuis votre espace.</p><a class="text-link" href="auth/register_html.php">Ouvrir ma boutique <span aria-hidden="true">&#8594;</span></a></article>
            <article class="feature-item feature-highlight"><span class="feature-number">03</span><h2>Un parcours clair</h2><p>Un espace simple, pensé pour passer de la découverte à l'achat sans détour.</p></article>
        </section>
    </main>
</body>
</html>
