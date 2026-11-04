<?php
session_start();
require_once '../config/database_connexion.php';

header("Content-Type: application/json");

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        "success" => false,
        "message" => "Utilisateur non connecté !"
    ]);
    exit();
}
if (($_SESSION['role'] ?? 'buyer') !== 'seller') {
    echo json_encode(["success" => false, "message" => "Accès réservé aux vendeurs."]);
    exit();
}
$user_id = $_SESSION['user_id'];
try {
    $method = $_SERVER['REQUEST_METHOD'];
    if ($method === 'GET') {
        $stm = $pdo->prepare("SELECT * FROM products WHERE user_id = ? ORDER BY created_at DESC");
        $stm->execute([$user_id]);
        $products = $stm->fetchAll(PDO::FETCH_ASSOC);
        echo json_encode(["success" => true, "products" => $products]);
        exit();
    }

    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $action = $data['action'] ?? '';
    $product_id = (int)($data['id'] ?? 0);

    if ($action === 'delete' && $product_id > 0) {
        $stm = $pdo->prepare("DELETE FROM products WHERE id = ? AND user_id = ?");
        $stm->execute([$product_id, $user_id]);
        echo json_encode(["success" => $stm->rowCount() > 0, "message" => "Produit supprimé."]);
        exit();
    }

    if ($action === 'update' && $product_id > 0) {
        $name = trim($data['name'] ?? '');
        $description = trim($data['description'] ?? '');
        $price = filter_var($data['price'] ?? null, FILTER_VALIDATE_FLOAT);
        if ($name === '' || $price === false || $price < 0) {
            echo json_encode(["success" => false, "message" => "Données produit invalides."]);
            exit();
        }
        $stm = $pdo->prepare("UPDATE products SET product_name = ?, product_description = ?, product_price = ? WHERE id = ? AND user_id = ?");
        $stm->execute([$name, $description, $price, $product_id, $user_id]);
        echo json_encode(["success" => true, "message" => "Produit modifié."]);
        exit();
    }

    echo json_encode(["success" => false, "message" => "Action inconnue."]);
} catch (PDOException $err) {
    echo json_encode([
        "success" => false,
        "message" => $err->getMessage()
    ]);
}
