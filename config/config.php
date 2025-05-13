<?php
// config.php

$host = 'localhost';
$dbname = 'projet_php_blog';
$username = 'root';
$password = ''; // à adapter selon ton environnement

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8mb4", $username, $password);
    // Définir les erreurs PDO comme exceptions
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Connexion échouée : " . $e->getMessage());
}
