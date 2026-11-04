<?php
session_start();
require_once '../config/database_connexion.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Utilisateir non connecté !"
    ]);
    exit();
}
$user_id = $_SESSION['user_id'];

//recuperer le nombre de produits
try {
    $sql = $pdo->prepare("SELECT COUNT(*) AS products_number FROM products WHERE user_id = ?");
    $sql->execute([
        $user_id
    ]);
    $result = $sql->fetch(PDO::FETCH_ASSOC);
    echo json_encode([
        "success" => true,
        "result" => $result['products_number']
    ]);

} catch (PDOException $error) {
    echo json_encode([
        "success" => false,
        "message" => $error->getMessage()
    ]);

}