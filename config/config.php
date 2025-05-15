<?php
require __DIR__ . '/../vendor/autoload.php';

$projectRoot = realpath(__DIR__ . '/..');

if ($projectRoot === false) {
    throw new RuntimeException("Impossible de déterminer le chemin du projet.");
}

$envFile = $projectRoot . '/.env';
if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::createImmutable($projectRoot);
    $dotenv->load();
}

// Maintenant, on récupère les variables d'environnement, que ce soit du .env local ou de Render
$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$db = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE');
$user = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME');
$pass = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');

$dsn = "mysql:host=$host;dbname=$db;charset=utf8";

try {
    $pdo = new PDO($dsn, $user, $pass);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}

$hostName = $_SERVER['HTTP_HOST'] ?? 'cli';

$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

if (strpos($hostName, 'localhost') !== false || strpos($hostName, '127.0.0.1') !== false) {
    define('BASE_URL', $protocol . $hostName . '/projet_php_blog/public');
} else {
    define('BASE_URL', 'https://projet-php-blog.onrender.com');
}
