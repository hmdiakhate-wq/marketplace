<?php
session_start();
require_once '../config/database_connexion.php';

header("content-type: application/json");

$rawInput = file_get_contents("php://input");
$data = json_decode($rawInput, true);
if (!is_array($data)) {
    $data = $_POST;
}

$username = trim($data['username'] ?? '');
$email = trim($data['email'] ?? '');
$plain_password = $data['password'] ?? '';
$role = $data['role'] ?? 'buyer';
if (!in_array($role, ['buyer', 'seller'], true)) {
    $role = 'buyer';
}
if ($username === '' || !filter_var($email, FILTER_VALIDATE_EMAIL) || strlen($plain_password) < 6) {
    echo json_encode([
        "success" => false,
        "message" => "Le nom, l'email et un mot de passe d'au moins 6 caractères sont obligatoires."
    ]);
    exit;
}
$password = password_hash($plain_password, PASSWORD_DEFAULT);

//verifier si l'email existe déjà
try {
    $sql = $pdo->prepare("SELECT id FROM users WHERE email = ?");
    $sql->execute([
        $email
    ]);
    $result = $sql->fetch();
    if ($result) {
        echo json_encode([
            "success" => false,
            "message" => "Cet email existe déjà !"
        ]);
    } else {
        //Insérer les données au base de données mysql
        $sql = "INSERT INTO users (username, email, password, role) VALUES (?, ?, ?, ?)";
        $stm = $pdo->prepare($sql);
        $stm->execute([
            $username,
            $email,
            $password,
            $role
        ]);
        session_regenerate_id(true);
        $_SESSION['user_id'] = $pdo->lastInsertId();
        $_SESSION['role'] = $role;
        echo json_encode([
            "success" => true,
            "message" => "Données enregistrés avec success !"
        ]);
    }
} catch (PDOException $er) {
    echo json_encode([
        "success" => false,
        "message" => $er->getMessage()
    ]);
}
