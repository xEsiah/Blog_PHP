<?php

// ▶ Chargement de Composer (.env + autoload)
require __DIR__ . '/../vendor/autoload.php';

// ▶ Détection du chemin racine du projet
$projectRoot = realpath(__DIR__ . '/..');
if ($projectRoot === false) {
    throw new RuntimeException("Impossible de déterminer le chemin du projet.");
}

// ▶ Chargement des variables d'environnement
$envFile = $projectRoot . '/.env';
if (file_exists($envFile)) {
    $dotenv = Dotenv\Dotenv::createImmutable($projectRoot);
    $dotenv->load();
}

// ▶ Variables d'environnement
$host = $_ENV['DB_HOST'] ?? getenv('DB_HOST');
$db = $_ENV['DB_DATABASE'] ?? getenv('DB_DATABASE');
$user = $_ENV['DB_USERNAME'] ?? getenv('DB_USERNAME');
$pass = $_ENV['DB_PASSWORD'] ?? getenv('DB_PASSWORD');
$port = $_ENV['DB_PORT'] ?? getenv('DB_PORT');

// ▶ Détection local vs prod
$isLocal = strpos($host, 'localhost') !== false || strpos($host, '127.0.0.1') !== false;

// ▶ Définition du protocole + BASE_URL
$hostName = $_SERVER['HTTP_HOST'] ?? 'cli';
$protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https://' : 'http://';

if (!defined('BASE_URL')) {
    if ($isLocal) {
        define('BASE_URL', $protocol . $hostName . '/projet_php_blog/public');
    } else {
        define('BASE_URL', 'https://projet-php-blog.onrender.com');
    }
}

// ▶ Création du DSN pour PDO
if ($isLocal) {
    $port = $port ?: 3306;
    $dsn = "mysql:host=$host;port=$port;dbname=$db;charset=utf8";
} else {
    $port = $port ?: 5432;
    $dsn = "pgsql:host=$host;port=$port;dbname=$db options='--client_encoding=UTF8'";
}

// ▶ Connexion PDO (si demandée)
if (!defined('SKIP_DB')) {
    try {
        $pdo = new PDO($dsn, $user, $pass);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (PDOException $e) {
        http_response_code(503);

        // Empêcher la redirection en boucle vers 503
        $currentScript = $_SERVER['SCRIPT_NAME'] ?? '';
        if (strpos($currentScript, '503.php') === false) {
            header("Location: " . BASE_URL . "/503.php");
            exit;
        }

        // Facultatif : log erreur
        error_log("[DB ERROR] " . $e->getMessage() . PHP_EOL, 3, $projectRoot . '/logs/db_error.log');
    }
}
