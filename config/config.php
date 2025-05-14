<?php
require __DIR__ . '/../vendor/autoload.php';

// On calcule le chemin absolu vers la racine du projet
$projectRoot = realpath(__DIR__ . '/..');

if ($projectRoot === false) {
    throw new RuntimeException("Impossible de déterminer le chemin du projet.");
}

// On charge les variables d'environnement
$dotenv = Dotenv\Dotenv::createImmutable($projectRoot);
$dotenv->safeLoad();

// Récupérer les valeurs des variables d'environnement
$host = $_ENV['DB_HOST'];
$db = $_ENV['DB_DATABASE'];
$user = $_ENV['DB_USERNAME'];
$pass = $_ENV['DB_PASSWORD'];

// Nouveau DSN pour PostgreSQL
$dsn = "pgsql:host=$host;port=5432;dbname=$db";

try {
    $pdo = new PDO($dsn, $user, $pass);
    // echo "Connexion réussie à la base de données.";
} catch (PDOException $e) {
    echo "Connexion échouée : " . $e->getMessage();
}
define('BASE_URL', 'https://projet-php-blog.onrender.com');