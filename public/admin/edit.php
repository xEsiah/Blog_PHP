<?php
session_start();
require '../includes/header.php';
require '../config/config.php';

// Vérifie si l'utilisateur est un auteur
function isAuthor(PDO $pdo, int $userId): bool
{
    $stmt = $pdo->prepare("SELECT 1 FROM authors WHERE id = ?");
    $stmt->execute([$userId]);
    return (bool) $stmt->fetchColumn();
}

if (empty($_SESSION['user']['id']) || !isAuthor($pdo, $_SESSION['user']['id'])) {
    exit('<p>Accès réservé aux auteurs connectés.</p>');
}

if (empty($_GET['id']) || !ctype_digit($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$id = (int) $_GET['id'];

// Récupération du post
$stmt = $pdo->prepare("SELECT * FROM posts WHERE id = ?");
$stmt->execute([$id]);
$post = $stmt->fetch();

if (!$post) {
    exit('<h2>Post introuvable.</h2>');
}

// Mise à jour après soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $title = trim($_POST['title'] ?? '');
    $content = trim($_POST['content'] ?? '');

    if ($title && $content) {
        // Mise à jour du post
        $stmt = $pdo->prepare("UPDATE posts SET title = ?, content = ? WHERE id = ?");
        $stmt->execute([$title, $content, $id]);

        // Redirection après mise à jour
        header('Location: index.php');
        exit;
    } else {
        // Message d'erreur si les champs sont vides
        echo '<p style="color:red;">Tous les champs sont obligatoires.</p>';
    }
}
?>

<h2>Modifier l'article</h2>
<div>
    <!-- Formulaire de modification -->
    <form class="editing" method="POST">
        <div class="left-col">
            <input type="text" name="title" value="<?= htmlspecialchars($post['title']) ?>" required>
            <button type="submit">💾 Mettre à jour</button>
        </div>
        <textarea name="content" required><?= htmlspecialchars($post['content']) ?></textarea>
    </form>
</div>

<a class="centrer_retour_index" href="index.php">← Retour à l'administration</a>

<?php require '../includes/footer.php'; ?>