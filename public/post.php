<?php
include '../includes/header.php';
include '../config/config.php';

if (isset($_GET['id'])) {
    $postId = (int) $_GET['id'];

    $stmt = $pdo->prepare("
        SELECT posts.title, posts.content, posts.created_at, authors.username 
        FROM posts 
        JOIN authors ON posts.author_id = authors.id 
        WHERE posts.id = ? 
        LIMIT 1
    ");
    $stmt->execute([$postId]);
    $post = $stmt->fetch();

    if ($post): ?>
        <div class="post-preview">
            <h2><?= htmlspecialchars($post['title']) ?></h2>
            <p class="date">
                <small>Publié le <?= date('d/m/Y', strtotime($post['created_at'])) ?> par
                    <?= htmlspecialchars($post['username']) ?></small>
            </p>
            <p><?= nl2br(htmlspecialchars($post['content'])) ?></p>
        </div>

        <hr>

        <!-- Commentaires -->
        <h2>Commentaires</h2>
        <?php
        $stmt = $pdo->prepare("
            SELECT id, username, content, created_at 
            FROM comments 
            WHERE post_id = ? 
            ORDER BY created_at DESC
        ");
        $stmt->execute([$postId]);
        $comments = $stmt->fetchAll();

        if ($comments):
            foreach ($comments as $comment): ?>
                <div class="comment">
                    <p class="infos"><?= htmlspecialchars($comment['username'] ?? 'Anonyme') ?>
                        <em>le <?= date('d/m/Y H:i', strtotime($comment['created_at'])) ?></em>
                    </p>
                    <p><?= nl2br(htmlspecialchars($comment['content'])) ?></p>

                    <!-- Bouton de suppression -->
                    <form method="post" action="delete_comment.php" onsubmit="return confirm('Supprimer ce commentaire ?');">
                        <input type="hidden" name="comment_id" value="<?= (int) $comment['id'] ?>">
                        <input type="hidden" name="post_id" value="<?= $postId ?>">
                        <button type="submit">Supprimer</button>
                    </form>
                </div>
            <?php endforeach;
        else: ?>
            <p>Aucun commentaire pour le moment.</p>
        <?php endif; ?>

        <hr>

        <!-- Formulaire d'ajout -->
        <h2>Ajouter un commentaire</h2>
        <form method="post" action="add_comment.php">
            <input type="hidden" name="post_id" value="<?= $postId ?>">
            <div>
                <label for="username">Pseudo (vide pour Anonyme) :</label><br>
                <input type="text" name="username" id="username">
            </div>
            <div>
                <label for="content">Commentaire :</label><br>
                <textarea name="content" id="content" rows="4" required></textarea>
            </div>
            <button type="submit">Envoyer</button>
        </form>

    <?php else: ?>
        <p>Post non trouvé.</p>
    <?php endif;
} else {
    echo "<p>ID de post invalide.</p>";
}

include '../includes/footer.php';
