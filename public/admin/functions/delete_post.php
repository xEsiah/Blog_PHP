<?php
session_start();
include __DIR__ . '/../../../config/config.php';

function isAuthor(PDO $pdo, int $userId): bool
{
    $stmt = $pdo->prepare("SELECT 1 FROM authors WHERE id = ?");
    $stmt->execute([$userId]);
    return (bool) $stmt->fetchColumn();
}

// Redirection si non connecté ou auteur non trouvé
if (empty($_SESSION['user']['id']) || !isAuthor($pdo, $_SESSION['user']['id'])) {
    header('Location: /Projet_PHP_BLOG/index.php');
    exit;
}

// Suppression du post si requête POST valide
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['id'])) {
    $stmt = $pdo->prepare("DELETE FROM posts WHERE id = ?");
    $stmt->execute([$_POST['id']]);
}

// Redirection finale après suppression
header('Location: /Projet_PHP_BLOG/admin/index.php');
exit;
