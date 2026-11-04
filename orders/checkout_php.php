<?php
session_start();
require_once '../config/database_connexion.php';
header('Content-Type: application/json');
if (!isset($_SESSION['user_id'])) { echo json_encode(['success' => false, 'login' => true]); exit(); }
try {
    $pdo->beginTransaction();
    $user_id = (int)$_SESSION['user_id'];
    $stm = $pdo->prepare('SELECT c.product_id, c.quantity, p.product_name, p.product_price, p.user_id AS seller_id FROM cart c JOIN products p ON p.id = c.product_id WHERE c.user_id = ? FOR UPDATE');
    $stm->execute([$user_id]);
    $items = $stm->fetchAll(PDO::FETCH_ASSOC);
    if (!$items) { throw new RuntimeException('Votre panier est vide.'); }
    $total = 0;
    foreach ($items as $item) { $total += (float)$item['product_price'] * (int)$item['quantity']; }
    $stm = $pdo->prepare('INSERT INTO orders (user_id, total_amount) VALUES (?, ?)');
    $stm->execute([$user_id, $total]);
    $order_id = $pdo->lastInsertId();
    $stm = $pdo->prepare('INSERT INTO order_items (order_id, product_id, seller_id, product_name, unit_price, quantity) VALUES (?, ?, ?, ?, ?, ?)');
    foreach ($items as $item) { $stm->execute([$order_id, $item['product_id'], $item['seller_id'], $item['product_name'], $item['product_price'], $item['quantity']]); }
    $pdo->prepare('DELETE FROM cart WHERE user_id = ?')->execute([$user_id]);
    $pdo->commit();
    echo json_encode(['success' => true, 'order_id' => $order_id]);
} catch (Throwable $error) {
    if ($pdo->inTransaction()) { $pdo->rollBack(); }
    echo json_encode(['success' => false, 'message' => $error->getMessage()]);
}
