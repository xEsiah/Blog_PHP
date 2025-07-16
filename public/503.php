<?php
define('SKIP_DB', true); // Bloque config.php depuis header
http_response_code(503);
require_once __DIR__ . '/../includes/header.php';
?>

<h2 style="color: #e74c3c; text-align: center; margin-top: 100px;">
    ⚠️ Service invalide pour le moment
</h2>

<p style="text-align: center; font-size: 1.2rem;">
    Nous rencontrons un problème de connexion à la base de données.<br>
    Merci de revenir plus tard.
</p>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>