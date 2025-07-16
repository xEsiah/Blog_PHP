<?php
http_response_code(503);
?>

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
    </header>
    <div class="margin_body_sauf_headerfooter"></div>
    <h2 style="color: #e74c3c; text-align: center; margin-top: 100px;">
        ⚠️ Service invalide pour le moment
    </h2>
    <p style="text-align: center; font-size: 1.2rem;">
        Base de données inaccessible. Merci de revenir plus tard.
    </p>

    </div>
    <footer>
        <p>&copy; <?= date('Y') ?> Esiah's Corner — Tous droits réservés.</p>
        <p><a href="<?= BASE_URL ?>/mentions.php">Mentions légales</a></p>
    </footer>
</body>

</html>