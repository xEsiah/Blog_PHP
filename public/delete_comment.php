<?php
session_start();
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $commentId = (int) ($_POST['comment_id'] ?? 0);
    $postId = (int) ($_POST['post_id'] ?? 0);

    // Vérifie que l'utilisateur est connecté
    if (!isset($_SESSION['user'])) {
        die("Accès non autorisé.");
    }

    $userId = $_SESSION['user']['id'];
    $userRole = $_SESSION['user']['role'];

    // Récupère l'auteur du post
    $stmt = $pdo->prepare("
        SELECT posts.author_id 
        FROM posts 
        JOIN comments ON comments.post_id = posts.id 
        WHERE comments.id = ?
        LIMIT 1
    ");
    $stmt->execute([$commentId]);
    $postData = $stmt->fetch();

    if (!$postData) {
        die("Commentaire ou post introuvable.");
    }

    // Seul l'auteur du post ou un admin peut supprimer
    if ($userRole !== 'admin' && $userId !== (int) $postData['author_id']) {
        die("Vous n'avez pas les droits pour supprimer ce commentaire.");
    }

    // Suppression autorisée
    $stmt = $pdo->prepare("DELETE FROM comments WHERE id = ?");
    $stmt->execute([$commentId]);

    header("Location: post.php?id=" . $postId);
    exit;
} else {
    echo "Méthode non autorisée.";
}
