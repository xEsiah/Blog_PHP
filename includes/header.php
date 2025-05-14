<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Esiah's Corner</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="icon" type="image/x-icon" href="/includes/icon.ico">

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
            <a href="index.php">Accueil</a>
            <?php if (isset($_SESSION['user'])): ?>
                <!-- L'utilisateur est connecté, on affiche le lien d'Administration -->
                <a href="/admin/article_creation.php">Créer un post</a>
                <a href="/admin/index.php">Administration</a>

                <a href="/public/logout.php">Déconnexion</a>
            <?php else: ?>
                <!-- L'utilisateur n'est pas connecté, on affiche le lien pour faire une demande d'adhésion -->
                <a href="/public/request_access.php">Faire une demande d'adhésion</a>
                <a href="/public/login.php">Se connecter</a>
            <?php endif; ?>
        </nav>
    </header>
    <div class="margin_body_sauf_headerfooter">