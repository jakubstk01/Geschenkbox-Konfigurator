<?php
require_once __DIR__ . '/config.php';
if (!is_logged_in()) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || empty($_POST['config_id'])) {
    header('Location: my_configurations.php');
    exit;
}

$configId = (int)$_POST['config_id'];
$userId = current_user_id();

// Verify ownership
$stmt = $pdo->prepare('SELECT user_id FROM configurations WHERE id = ?');
$stmt->execute([$configId]);
$config = $stmt->fetch();

if (!$config || $config['user_id'] !== $userId) {
    header('HTTP/1.1 403 Forbidden');
    exit;
}

// Delete associated products first
$pdo->prepare('DELETE FROM configuration_products WHERE configuration_id = ?')->execute([$configId]);

// Delete configuration
$pdo->prepare('DELETE FROM configurations WHERE id = ?')->execute([$configId]);

header('Location: /my_configurations.php');
exit;
