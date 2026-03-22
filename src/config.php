<?php
// PDO database connection
session_start();
header('Content-Type: text/html; charset=utf-8');

$dbHost = getenv('DB_HOST') ?: 'db';
$dbName = getenv('MYSQL_DATABASE') ?: 'giftbox';
$dbUser = getenv('MYSQL_USER') ?: 'user';
$dbPass = getenv('MYSQL_PASSWORD') ?: 'userpass';

try {
    $pdo = new PDO("mysql:host=$dbHost;dbname=$dbName;charset=utf8mb4", $dbUser, $dbPass, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (Exception $e) {
    echo "Datenbankverbindung fehlgeschlagen: " . htmlspecialchars($e->getMessage());
    exit;
}

function is_logged_in() {
    return !empty($_SESSION['user_id']);
}

function current_user_id() {
    return $_SESSION['user_id'] ?? null;
}

