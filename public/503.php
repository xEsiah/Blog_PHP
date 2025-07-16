<?php
define('SKIP_DB', true);
http_response_code(503);
require_once __DIR__ . '/../includes/header.php';
?>

<h2 style="color: #e74c3c; text-align: center; margin-top: 100px;">
    ⚠️ Service invalide pour le moment
</h2>
<p style="text-align: center; font-size: 1.2rem;">
    Base de données inaccessible. Merci de revenir plus tard.
</p>

<?php require_once __DIR__ . '/../includes/footer.php'; ?>