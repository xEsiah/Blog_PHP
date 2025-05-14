<?php
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/includes/header.php';

// Récupérer les 5 derniers posts
try {
    $query = "SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC LIMIT 5";
    $stmt = $pdo->query($query);
    $posts = $stmt->fetchAll();
} catch (PDOException $e) {
    die("Erreur lors de la récupération des posts : " . $e->getMessage());
}
?>

<h2>Venez découvrir nos publications</h2>

<?php foreach ($posts as $post): ?>
    <div class="post-preview">
        <h3>
            <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>">
                <?= htmlspecialchars($post['title']) ?>
            </a>
        </h3>
        <p>
            <?= nl2br(htmlspecialchars(substr($post['content'], 0, 150))) ?>...
            <a href="post.php?id=<?= htmlspecialchars($post['id']) ?>">Lire la suite</a>
        </p>
        <p>
            <small>Publié le <?= date('d/m/Y', strtotime($post['created_at'])) ?></small>
        </p>
    </div>
<?php endforeach; ?>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>