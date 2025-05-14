<?php
include __DIR__ . '/../../includes/header.php';
include __DIR__ . '/../../config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['request_id'])) {
    $requestId = (int) $_POST['request_id'];
    $action = $_POST['action'];

    try {
        if ($action === '✔️ Accepter') {
            $stmt = $pdo->prepare("SELECT first_name, last_name, username, password FROM access_requests WHERE id = ?");
            $stmt->execute([$requestId]);
            $request = $stmt->fetch();

            if ($request) {
                $insert = $pdo->prepare("INSERT INTO authors (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
                $insert->execute([
                    $request['first_name'],
                    $request['last_name'],
                    $request['username'],
                    $request['password']
                ]);

                $delete = $pdo->prepare("DELETE FROM access_requests WHERE id = ?");
                $delete->execute([$requestId]);

                echo "<p class='success-message'>Demande acceptée, auteur ajouté.</p>";
                exit;
            } else {
                echo "<p class='error-message'>Demande introuvable.</p>";
                exit;
            }
        } elseif ($action === '🛑 Rejeter') {
            $delete = $pdo->prepare("DELETE FROM access_requests WHERE id = ?");
            $delete->execute([$requestId]);

            echo "<p class='error-message'>Demande rejetée.</p>";
            exit;
        }
    } catch (Exception $e) {
        echo "<p class='error-message'>Une erreur est survenue : " . $e->getMessage() . "</p>";
        exit;
    }
} else {
    echo "<p class='error-message'>Requête invalide.</p>";
    exit;
}
?>

<a class="centrer_retour_index" href="index.php" aria-label="Retour vers la liste des articles">← Retour à
    l'Administration</a>

<?php include __DIR__ . '/../../includes/footer.php'; ?>