<?php
include '../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $postId = (int) $_POST['post_id'];
    $username = trim($_POST['username']);
    $content = trim($_POST['content']);

    if ($content !== '') {
        if ($username === '') {
            $username = 'Anonyme';
        }

        $stmt = $pdo->prepare("
            INSERT INTO comments (post_id, username, content, created_at)
            VALUES (?, ?, ?, NOW())
        ");
        $stmt->execute([$postId, $username, $content]);
    }

    header("Location: post.php?id=" . $postId);
    exit;
} else {
    echo "Méthode non autorisée.";
}