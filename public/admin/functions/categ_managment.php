<h2 class="Administrationh3">Gestionnaire des catégories</h2>

<?php
$inputValue = ''; // Valeur du champ d'ajout

// Gestion de l'ajout d'une catégorie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_category'])) {
    $name = trim($_POST['new_category']);
    $inputValue = htmlspecialchars($name);

    if ($name !== '') {
        // Vérification si la catégorie existe déjà
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE LOWER(name) = LOWER(?)");
        $stmt->execute([$name]);
        $existing = $stmt->fetch();

        if ($existing) {
            echo '<p class="error">❌ Cette catégorie existe déjà.</p>';
        } else {
            // Insertion de la nouvelle catégorie
            $stmt = $pdo->prepare("INSERT INTO categories (name) VALUES (?)");
            $stmt->execute([$name]);

            echo '<meta http-equiv="refresh" content="1;url=index.php">'; // Redirection après ajout
            exit;
        }
    } else {
        echo '<p class="error">❌ Le nom ne peut pas être vide.</p>';
    }
}

// Gestion de la suppression d'une catégorie par nom
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_category'])) {
    $nameToDelete = trim($_POST['delete_category']);
    $nameToDelete = htmlspecialchars($nameToDelete); // Sécurisation du nom

    if ($nameToDelete !== '') {
        // Recherche de la catégorie à supprimer
        $stmt = $pdo->prepare("SELECT id FROM categories WHERE LOWER(name) = LOWER(?)");
        $stmt->execute([$nameToDelete]);
        $category = $stmt->fetch();

        if ($category) {
            // Suppression de la catégorie
            $stmt = $pdo->prepare("DELETE FROM categories WHERE id = ?");
            $stmt->execute([$category['id']]);

            echo '<meta http-equiv="refresh" content="1;url=index.php">'; // Redirection après suppression
            exit;
        } else {
            echo '<p class="error">❌ La catégorie n\'existe pas.</p>';
        }
    } else {
        echo '<p class="error">❌ Le nom de la catégorie ne peut pas être vide.</p>';
    }
}
?>

<div class="category-manager">
    <!-- Liste des catégories existantes -->
    <ul class="category-list">
        <?php
        // Récupération des catégories existantes
        $stmt = $pdo->query("SELECT name FROM categories ORDER BY name");
        $categories = $stmt->fetchAll();

        foreach ($categories as $cat): ?>
            <li><?= htmlspecialchars($cat['name']) ?></li>
        <?php endforeach; ?>
    </ul>

    <!-- Formulaire pour ajouter une nouvelle catégorie -->
    <form method="POST" class="category-form">
        <input type="text" name="new_category" placeholder="Catégorie à ajouter" required value="<?= $inputValue ?>">
        <button type="submit">➕ Ajouter</button>
    </form>

    <!-- Formulaire pour supprimer une catégorie -->
    <form method="POST" class="category-form">
        <input type="text" name="delete_category" placeholder="Catégorie à supprimer" required>
        <button type="submit">🗑️ Supprimer</button>
    </form>
</div>