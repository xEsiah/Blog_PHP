<?php
include '../includes/header.php';

// Connexion à la BDD
$host = 'localhost';
$db = 'projet_php_blog';
$user = 'root';
$pass = '';
$charset = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$db;charset=$charset";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO(dsn: $dsn, username: $user, password: $pass, options: $options);
} catch (\PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer les derniers posts (5 derniers)
$query = "SELECT id, title, content, created_at FROM posts ORDER BY created_at DESC LIMIT 5";
$stmt = $pdo->query($query);
$posts = $stmt->fetchAll();

?>

<h2>Venez découvrir nos publications</h2>

<?php foreach ($posts as $post): ?>
    <div class="post-preview">
        <h3><a href="post.php?id=<?= $post['id'] ?>"><?= htmlspecialchars(string: $post['title']) ?></a></h3>
        <p><?= nl2br(string: substr(string: $post['content'], offset: 0, length: 150)) ?>...</p>
        <!-- Extrait de 150 caractères --><a href="post.php?id=<?= $post['id'] ?>">Lire la suite</a>
        <p><small>Publié le <?= date(format: 'd/m/Y', timestamp: strtotime($post['created_at'])) ?></small></p>

    </div>
<?php endforeach; ?>

<?php include '../includes/footer.php'; ?>