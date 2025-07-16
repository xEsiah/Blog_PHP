<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Ne charge config.php que si SKIP_DB n'est pas défini
if (!defined('SKIP_DB')) {
    require_once __DIR__ . '/../config/config.php';
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <title>Esiah's Corner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="<?= BASE_URL ?>/images/icon.ico">
    <link rel="stylesheet" href="<?= BASE_URL ?>/css/index.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet" />
</head>

<body>
    <header>
        <div>
            <h1>Esiah's Corner</h1>
            <p>Le forum de l'élitisme</p>
        </div>
        <nav>
            <a href="<?= BASE_URL ?>">Accueil</a>
            <?php if (isset($_SESSION['user'])): ?>
                <a href="<?= BASE_URL ?>/admin/article_creation.php">Créer un post</a>
                <a href="<?= BASE_URL ?>/admin/index.php">Administration</a>
                <a href="<?= BASE_URL ?>/logout.php">Déconnexion</a>
            <?php else: ?>
                <a href="<?= BASE_URL ?>/request_access.php">Faire une demande d'adhésion</a>
                <a href="<?= BASE_URL ?>/login.php">Se connecter</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="margin_body_sauf_headerfooter">