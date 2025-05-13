<?php
session_start();
include '../includes/header.php';
include '../config/config.php';

if (!isset($_SESSION['user'])) {
    exit('<p>Accès réservé aux auteurs connectés.</p>');
}

// Récupération des catégories pour le formulaire
$categories = $pdo->query("SELECT id, name FROM categories ORDER BY name")->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['title'], $_POST['content'], $_POST['category_id']) && !empty($_POST['title']) && !empty($_POST['content'])) {
        $stmt = $pdo->prepare(query: "INSERT INTO posts (title, content, author_id, category_id, created_at) VALUES (?, ?, ?, ?, NOW())");
        $stmt->execute(params: [
            $_POST['title'],
            $_POST['content'],
            $_SESSION['user']['id'],
            $_POST['category_id']
        ]);
        echo "<p>Post publié avec succès !</p>";
    } else {
        echo "<p>Veuillez remplir tous les champs.</p>";
    }
}
?>

<h2>Créer un nouveau post</h2>
<form method="POST">
    <label for="title">Titre :</label><br>
    <input type="text" id="title" name="title" maxlength="255" required><br><br>

    <label for="content">Contenu :</label><br>
    <textarea id="content" name="content" rows="8" required></textarea><br><br>

    <label for="category_id">Catégorie :</label><br>
    <select id="category_id" name="category_id" required>
        <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>"><?= htmlspecialchars(string: $cat['name']) ?></option>
        <?php endforeach; ?>
    </select><br><br>
    <input type="submit" value="Publier">
</form>
<a class="centrer_retour_index" href=" index.php" aria-label="Retour vers la liste des articles">← Retour à
    l'Administration</a>
<?php include '../includes/footer.php'; ?>