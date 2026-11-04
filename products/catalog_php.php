<?php
session_start();
require_once '../config/database_connexion.php';
header('Content-Type: application/json');

try {
    $search = trim($_GET['q'] ?? '');
    $sql = "SELECT p.*, u.username AS seller_name FROM products p JOIN users u ON u.id = p.user_id";
    $params = [];
    if ($search !== '') {
        $sql .= " WHERE p.product_name LIKE ? OR p.product_description LIKE ?";
        $params = ["%$search%", "%$search%"];
    }
    $sql .= " ORDER BY p.created_at DESC";
    $stm = $pdo->prepare($sql);
    $stm->execute($params);
    echo json_encode(['success' => true, 'products' => $stm->fetchAll(PDO::FETCH_ASSOC)]);
} catch (PDOException $error) {
    echo json_encode(['success' => false, 'message' => 'Impossible de charger le catalogue.']);
}
