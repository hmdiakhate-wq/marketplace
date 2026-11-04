<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Marketplace : Inscription</title>
</head>

<body class="auth-page">
    <main class="auth-shell">
        <div class="panel">
            <h1>Inscription</h1>
            <form action="" method="post" id="form" class="auth-form">
                <p id="result" class="form-message"></p>

                <div>
                    <label for="name">Votre nom :</label>
                    <input type="text" name="username" id="username" placeholder="Votre nom..." required>
                </div>

                <div>
                    <label for="email">Votre email :</label>
                    <input type="email" name="email" id="email" placeholder="Votre email..." required>
                </div>

                <div>
                    <label for="password">Votre mot de passe :</label>
                    <input type="password" name="password" id="password" placeholder="Votre mot de passe..." required>
                </div>

                <div>
                    <label for="role">Je souhaite :</label>
                    <select name="role" id="role">
                        <option value="buyer">Acheter des produits</option>
                        <option value="seller">Vendre mes produits</option>
                    </select>
                </div>

                <button type="submit" class="btn btn-primary">S'inscrire</button>
                <p class="text-muted">Déjà un compte ? <a href="login_html.php" class="link">Se connecter</a></p>
            </form>
        </div>
    </main>

    <script>
        let result = document.getElementById("result");
        document.getElementById("form").addEventListener("submit", e => {
            e.preventDefault();

            let datas = new FormData();
            datas.append("username", document.getElementById("username").value.trim());
            datas.append("email", document.getElementById("email").value.trim());
            datas.append("password", document.getElementById("password").value);
            datas.append("role", document.getElementById("role").value);

            fetch("register_php.php", {
                    method: "POST",
                    body: datas
                })
                .then(res => res.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = "dashboard_html.php";
                        return;
                    }

                    result.textContent = data.message;
                    result.className = "form-message error";
                    setTimeout(() => {
                        document.getElementById("form").reset();
                    }, 400);
                })
                .catch(err => {
                    result.textContent = "Le serveur est indisponible. Vérifie Apache et MySQL.";
                    result.className = "form-message error";
                    console.error(err);
                });
        });
    </script>
</body>

</html>