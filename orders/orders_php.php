<?php
session_start();
require_once '../config/database_connexion.php';
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false, 'login' => true]); exit(); }
try {
    $user_id = (int)$_SESSION['user_id'];
    $role = $_SESSION['role'] ?? 'buyer';
    $data = json_decode(file_get_contents('php://input'), true) ?: $_POST;
    if ($role === 'seller' && ($data['action'] ?? '') === 'status') {
        $allowed = ['pending', 'confirmed', 'shipped', 'delivered', 'cancelled'];
        $status = $data['status'] ?? '';
        if (!in_array($status, $allowed, true)) { echo json_encode(['success' => false, 'message' => 'Statut invalide.']); exit(); }
        $stm = $pdo->prepare("UPDATE orders o JOIN order_items oi ON oi.order_id = o.id SET o.status = ? WHERE o.id = ? AND oi.seller_id = ?");
        $stm->execute([$status, (int)$data['order_id'], $user_id]);
        echo json_encode(['success' => true]); exit();
    }
    if ($role === 'seller') {
        $stm = $pdo->prepare('SELECT DISTINCT o.id, o.total_amount, o.status, o.created_at, u.username AS customer_name, u.email AS customer_email FROM orders o JOIN order_items oi ON oi.order_id = o.id JOIN users u ON u.id = o.user_id WHERE oi.seller_id = ? ORDER BY o.created_at DESC');
        $stm->execute([$user_id]);
    } else {
        $stm = $pdo->prepare('SELECT o.id, o.total_amount, o.status, o.created_at, COUNT(oi.id) AS items_count FROM orders o LEFT JOIN order_items oi ON oi.order_id = o.id WHERE o.user_id = ? GROUP BY o.id ORDER BY o.created_at DESC');
        $stm->execute([$user_id]);
    }
    echo json_encode(['success' => true, 'role' => $role, 'orders' => $stm->fetchAll(PDO::FETCH_ASSOC)]);
} catch (PDOException $error) { echo json_encode(['success' => false, 'message' => 'Impossible de charger les commandes.']); }
