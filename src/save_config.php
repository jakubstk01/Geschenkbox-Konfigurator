<?php
require_once __DIR__ . '/config.php';
if (!is_logged_in()) { header('HTTP/1.1 403 Forbidden'); echo 'Login required'; exit; }
$cfg = $_SESSION['config'] ?? null;
if (!$cfg) { header('Location: index.php'); exit; }
$userId = current_user_id();
$total = 0.0;
$products = $cfg['products'] ?? [];
if ($products) {
    $in = implode(',', array_map('intval', $products));
    $stmt = $pdo->query("SELECT id, price FROM products WHERE id IN ($in)");
    $rows = $stmt->fetchAll();
    foreach ($rows as $r) $total += $r['price'];
}
$ins = $pdo->prepare('INSERT INTO configurations (user_id, box_size, box_style, message, packaging, ribbon_color, total_price) VALUES (?,?,?,?,?,?,?)');
$ins->execute([$userId, $cfg['box_size'] ?? null, $cfg['box_style'] ?? null, $cfg['message'] ?? null, null, $cfg['ribbon_color'] ?? null, $total]);
$configId = $pdo->lastInsertId();
if ($products) {
    $stmt = $pdo->prepare('INSERT INTO configuration_products (configuration_id, product_id) VALUES (?,?)');
    foreach ($products as $pid) $stmt->execute([$configId, (int)$pid]);
}
header('Location: index.php'); exit;
