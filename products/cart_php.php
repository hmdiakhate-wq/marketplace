<?php
session_start();
require_once '../config/database_connexion.php';
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false, 'login' => true]); exit(); }
$user_id = (int)$_SESSION['user_id'];
try {
    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    $action = $data['action'] ?? 'list';
    if ($action === 'add') {
        $product_id = (int)($data['product_id'] ?? 0);
        $stm = $pdo->prepare('INSERT INTO cart (user_id, product_id, quantity) VALUES (?, ?, 1) ON DUPLICATE KEY UPDATE quantity = quantity + 1');
        $stm->execute([$user_id, $product_id]);
        echo json_encode(['success' => true, 'message' => 'Produit ajouté au panier.']); exit();
    }
    if ($action === 'update') {
        $quantity = max(1, (int)($data['quantity'] ?? 1));
        $stm = $pdo->prepare('UPDATE cart SET quantity = ? WHERE id = ? AND user_id = ?');
        $stm->execute([$quantity, (int)$data['id'], $user_id]);
        echo json_encode(['success' => true]); exit();
    }
    if ($action === 'remove') {
        $stm = $pdo->prepare('DELETE FROM cart WHERE id = ? AND user_id = ?');
        $stm->execute([(int)$data['id'], $user_id]);
        echo json_encode(['success' => true]); exit();
    }
    $stm = $pdo->prepare('SELECT c.id, c.quantity, p.id AS product_id, p.product_name, p.product_price, p.product_image, (c.quantity * p.product_price) AS subtotal FROM cart c JOIN products p ON p.id = c.product_id WHERE c.user_id = ? ORDER BY c.created_at DESC');
    $stm->execute([$user_id]);
    $items = $stm->fetchAll(PDO::FETCH_ASSOC);
    echo json_encode(['success' => true, 'items' => $items, 'total' => array_sum(array_column($items, 'subtotal'))]);
} catch (PDOException $error) { echo json_encode(['success' => false, 'message' => 'Action panier impossible.']); }
