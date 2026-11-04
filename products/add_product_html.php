<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Marketplace : Ajouter un produit</title>
</head>

<body>
    <section>
        <div class="add-product-card">
            <h1>Ajouter un produit</h1>
            <form action="" method="post" id="form" enctype="multipart/form-data">
                <p id="message" class="form-message"></p>
                <label for="name">Nom du produit : </label> <br>
                <input type="text" id="product_name" placeholder="Nom du produit..." required> <br>
                <label for="description">Description du produit : </label> <br>
                <input type="text" id="product_description" placeholder="Description du produit..."> <br>
                <label for="price">Prix du produit : </label> <br>
                <input type="number" id="product_price" min="0" step="0.01" placeholder="Prix du produit..." required> <br>
                <label for="image">Image du produit : </label> <br>
                <input type="file" id="product_image" accept="image/jpeg,image/png,image/webp" required> <br>
                <input type="submit" value="Ajouter" id="submit">
            </form>
        </div>
    </section>


    <script>
        document.getElementById("form").addEventListener("submit", (e) => {
            e.preventDefault();

            // recuperer les données du produit
            let product_name = document.getElementById("product_name").value.trim();
            let product_description = document.getElementById("product_description").value.trim();
            let product_price = document.getElementById("product_price").value.trim();
            let product_image = document.getElementById("product_image").files[0];

            if (!product_name || !product_price || !product_image) {
                document.getElementById("message").textContent = "Le nom, le prix et l'image sont obligatoires.";
                document.getElementById("message").className = "form-message error";
                return;
            }

            // créer les données à envoyer
            let formData = new FormData();

            formData.append("name", product_name);
            formData.append("description", product_description);
            formData.append("price", product_price);
            formData.append("image", product_image);

            // envoyer les données au back-end PHP
            fetch("add_product_php.php", {
                    method: "POST",
                    body: formData
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success === false && data.message === "Utilisateur non connecté !") {
                        window.location.href = "../auth/login_html.php";
                        return;
                    }
                    if (data.success === true) {
                        document.getElementById("message").textContent = data.message;
                        document.getElementById("message").className = "form-message";
                        document.getElementById("form").reset();
                    } else {
                        document.getElementById("message").textContent = data.message || "L'ajout du produit a échoué.";
                        document.getElementById("message").className = "form-message error";
                    }
                })
                .catch(err => {
                    document.getElementById("message").textContent = "Le serveur est indisponible. Vérifie qu'Apache et MySQL sont démarrés.";
                    document.getElementById("message").className = "form-message error";
                    console.error(err);
                });
        });
    </script>

</body>

</html>