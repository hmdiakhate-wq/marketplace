<?php
session_start();
require_once '../config/database_connexion.php';

header("content-type: application/json");

$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);
if (!is_array($data)) {
    $data = $_POST;
}

$email = trim($data['email'] ?? '');
$password = $data['password'] ?? '';
if ($email === '' || $password === '') {
    echo json_encode([
        "success" => false,
        "message" => "Tous les champs sont obligatoires."
    ]);
    exit;
}

try {
    $sql = "SELECT * FROM users WHERE email = ?";
    $stm = $pdo->prepare($sql);
    $stm->execute([
        $email
    ]);
    $user = $stm->fetch();
    if ($user && password_verify($password, $user['password'])) {
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'] ?? 'buyer';
        echo json_encode([
            "success" => true,
            "message" => "Connexion réussie !"
        ]);
    } else {
        echo json_encode([
            "success" => false,
            "message" => "Email ou mot de passe incorrect !"
        ]);

    }
} catch (PDOException $er) {
    echo json_encode([
        "success" => false,
        "message" => $er->getMessage()
    ]);
}
