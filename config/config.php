<?php
require __DIR__ . '/../vendor/autoload.php';

$projectRoot = realpath(__DIR__ . '/..');

if ($projectRoot === false) {
    throw new RuntimeException("Impossible de déterminer le chemin du projet.");
}

$dotenv = Dotenv\Dotenv::createImmutable($projectRoot);
$dotenv->load(); // Utiliser load() pour lever les erreurs

$host = $_ENV['DB_HOST'];
$db = $_ENV['DB_DATABASE'];
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];

$dsn = "mysql:host=$host;dbname=$db;charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

// Détection automatique de l'environnement pour BASE_URL
$hostName = $_SERVER['HTTP_HOST'] ?? 'cli';

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

if (strpos($hostName, 'localhost') !== false || strpos($hostName, '127.0.0.1') !== false) {
    define('BASE_URL', $protocol . $hostName . '/projet_php_blog/public');
} else {
    define('BASE_URL', 'https://projet-php-blog.onrender.com');
}
