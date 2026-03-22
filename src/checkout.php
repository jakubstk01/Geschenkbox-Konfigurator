<?php
require_once __DIR__ . '/config.php';
if (!is_logged_in() || empty($_GET['id'])) {
    header('Location: /');
    exit;
}

$configId = (int)$_GET['id'];
$userId = current_user_id();

// Load configuration
$stmt = $pdo->prepare('SELECT box_size, box_style, message, ribbon_color FROM configurations WHERE id = ? AND user_id = ?');
$stmt->execute([$configId, $userId]);
$config = $stmt->fetch();

if (!$config) {
    header('Location: /my_configurations.php');
    exit;
}

// Load products
$stmt = $pdo->prepare('SELECT product_id FROM configuration_products WHERE configuration_id = ?');
$stmt->execute([$configId]);
$products = $stmt->fetchAll(PDO::FETCH_COLUMN);

// Store in session
$_SESSION['config'] = [
    'box_size' => $config['box_size'],
    'box_style' => $config['box_style'],
    'message' => $config['message'],
    'ribbon_color' => $config['ribbon_color'],
    'products' => $products
];

// Redirect to summary
header('Location: /configurator/summary.php');
exit;
