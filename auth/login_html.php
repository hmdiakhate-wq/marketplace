<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../style/style.css">
    <title>Marketplace : Connexion</title>
</head>

<body class="auth-page">
    <main class="auth-shell">
        <div class="panel">
            <h1>Connexion</h1>
            <form action="" method="post" id="form" class="auth-form">
                <p id="result" class="form-message"></p>

                <div>
                    <label for="email">Votre email :</label>
                    <input type="email" name="email" id="email" placeholder="Votre email..." required>
                </div>

                <div>
                    <label for="password">Votre mot de passe :</label>
                    <input type="password" name="password" id="password" placeholder="Votre mot de passe..." required>
                </div>

                <button type="submit" class="btn btn-primary">Se connecter</button>
                <p class="text-muted">Pas encore de compte ? <a href="register_html.php" class="link">S'inscrire</a></p>
            </form>
        </div>
    </main>

    <script>
        let result = document.getElementById("result");
        document.getElementById("form").addEventListener("submit", e => {
            e.preventDefault();

            let datas = new FormData();
            datas.append("email", document.getElementById("email").value.trim());
            datas.append("password", document.getElementById("password").value);

            fetch("login_php.php", {
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