<?php
// Utilisation de __DIR__ pour les chemins relatifs
include __DIR__ . '/../includes/header.php';  // Chemin absolu vers header.php
include __DIR__ . '/../config/config.php';    // Chemin absolu vers config.php

// Vérifier que la requête est POST et que l'action est définie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['request_id'])) {
    $requestId = (int) $_POST['request_id'];
    $action = $_POST['action'];

    try {
        if ($action === '✔️ Accepter') {
            // Récupérer les données de la demande
            $stmt = $pdo->prepare("SELECT first_name, last_name, username, password FROM access_requests WHERE id = ?");
            $stmt->execute([$requestId]);
            $request = $stmt->fetch();

            if ($request) {
                // Insérer dans authors
                $insert = $pdo->prepare("INSERT INTO authors (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
                $insert->execute([
                    $request['first_name'],
                    $request['last_name'],
                    $request['username'],
                    $request['password']
                ]);

                // Supprimer la demande
                $delete = $pdo->prepare("DELETE FROM access_requests WHERE id = ?");
                $delete->execute([$requestId]);

                echo "<p class='success-message'>Demande acceptée, auteur ajouté.</p>";
                exit;  // Arrêter l'exécution après le message
            } else {
                echo "<p class='error-message'>Demande introuvable.</p>";
                exit;
            }
        } elseif ($action === '🛑 Rejeter') {
            // Supprimer uniquement la demande
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

<?php include("../includes/footer.php"); ?>