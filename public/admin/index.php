<?php
session_start();
include '../includes/header.php';
include '../config/config.php';

// Fonction vérifiant si l'auteur est un administrateur
function isAdministration(PDO $pdo, int $userId): bool
{
    $stmt = $pdo->prepare("SELECT is_Administration FROM authors WHERE id = ?");
    $stmt->execute([$userId]);
    $user = $stmt->fetch();

    return $user && $user['is_Administration'];
}

// Sécurité : redirection si non connecté ou non admin
if (!isset($_SESSION['user']['id']) || !isAdministration($pdo, $_SESSION['user']['id'])) {
    exit('<h2>Accès refusé.</h2>');
}

// Liste des posts
$query = "SELECT id, title, content, created_at FROM posts ORDER BY created_at";
$stmt = $pdo->query($query);
$posts = $stmt->fetchAll();
?>

<h2 class="Administrationh3">Liste des posts</h2>

<?php foreach ($posts as $post): ?>
    <div class="post-list">
        <h3 style="color: #1e90ff;" class="infos"><?= htmlspecialchars($post['title']) ?></h3>
        <p><?= nl2br(substr($post['content'], 0, 150)) ?>...</p>
        <p style="color: #1e90ff;">
            <small>Publié le <?= date('d/m/Y', strtotime($post['created_at'])) ?></small>
        </p>

        <form action="functions/delete_post.php" method="POST" onsubmit="return confirm('Confirmer la suppression ?');"
            style="display:inline;">
            <input type="hidden" name="id" value="<?= $post['id'] ?>">
            <button type="submit">🗑 Supprimer</button>
        </form>

        <a href="edit.php?id=<?= $post['id'] ?>">
            <button>✏️ Éditer</button>
        </a>
    </div>
<?php endforeach; ?>

<h2 class="Administrationh3">Demandes d'adhésion</h2>

<?php
$stmt = $pdo->query("SELECT * FROM access_requests");
$requests = $stmt->fetchAll();

foreach ($requests as $request): ?>
    <div class="post-list">
        <p class="infos">Nom :</p>
        <p><?= htmlspecialchars($request['first_name']) ?>     <?= htmlspecialchars($request['last_name']) ?></p>

        <p class="infos">Nom d'utilisateur :</p>
        <p><?= htmlspecialchars($request['username']) ?></p>

        <p class="infos">Motivation :</p>
        <p><?= nl2br(htmlspecialchars($request['motivation'])) ?></p>

        <form action="approve_request.php" method="POST">
            <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
            <input type="submit" name="action" value="✔️ Accepter">
            <input type="submit" name="action" value="🛑 Rejeter">
        </form>
    </div>
<?php endforeach; ?>

<?php include 'functions/categ_managment.php'; ?>
<?php include '../includes/footer.php'; ?>