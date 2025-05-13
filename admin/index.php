<?php
session_start();
include '../includes/header.php';

include '../config/config.php';

// Fonction vérifiant si l'auteur est connecté
function isAdministration(PDO $pdo, int $userId): bool
{
    $stmt = $pdo->prepare(query: "SELECT id FROM authors WHERE id = ?");
    $stmt->execute(params: [$userId]);
    $user = $stmt->fetch();

    return $user !== false; // Vérifie si l'utilisateur existe dans la table des auteurs
}

if (!isset($_SESSION['user']['id']) || !isAdministration($pdo, $_SESSION['user']['id'])) {
    exit('<h2>Accès refusé.</h2>');
}
$query = "SELECT id, title, content, created_at FROM posts ORDER BY created_at";
$stmt = $pdo->query($query);
$posts = $stmt->fetchAll();
?>

<h2 class="Administrationh3">Liste des posts</h2>
<?php foreach ($posts as $post): ?>
    <div class="post-list">
        <h3 style="color: #1e90ff;" class="infos"><?= htmlspecialchars(string: $post['title']) ?></h3>
        <p><?= nl2br(string: substr(string: $post['content'], offset: 0, length: 150)) ?>...</p>
        <p style="color: #1e90ff;"><small>Publié le
                <?= date(format: 'd/m/Y', timestamp: strtotime(datetime: $post['created_at'])) ?></small></p>

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
        <p class="infos">Nom:</p>
        <p><?= htmlspecialchars(string: $request['first_name']) ?>
            <?= htmlspecialchars(string: $request['last_name']) ?>
        </p>
        <p class="infos">Nom d'utilisateur:</p>
        <p> <?= htmlspecialchars(string: $request['username']) ?></p>
        <p class="infos">Motivation:</p>
        <p> <?= nl2br(string: htmlspecialchars(string: $request['motivation'])) ?></p>
        <form action="approve_request.php" method="POST">
            <input type="hidden" name="request_id" value="<?= $request['id'] ?>">
            <input type="submit" name="action" value="✔️ Accepter">
            <input type="submit" name="action" value="🛑 Rejeter">
        </form>
    </div>

<?php endforeach; ?>

<?php include 'functions/categ_managment.php'; ?>

<?php include '../includes/footer.php'; ?>