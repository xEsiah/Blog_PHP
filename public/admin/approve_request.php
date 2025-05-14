<?php
include("../includes/header.php");
include '../config/config.php';

// Vérifier que la requête est POST et que l'action est définie
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['action'], $_POST['request_id'])) {
    $requestId = (int) $_POST['request_id'];
    $action = $_POST['action'];

    if ($action === '✔️ Accepter') {
        // Récupérer les données de la demande
        $stmt = $pdo->prepare(query: "SELECT first_name, last_name, username, password FROM access_requests WHERE id = ?");
        $stmt->execute(params: [$requestId]);
        $request = $stmt->fetch();

        if ($request) {
            // Insérer dans authors
            $insert = $pdo->prepare(query: "INSERT INTO authors (first_name, last_name, username, password) VALUES (?, ?, ?, ?)");
            $insert->execute(params: [
                $request['first_name'],
                $request['last_name'],
                $request['username'],
                $request['password']
            ]);

            // Supprimer la demande
            $delete = $pdo->prepare(query: "DELETE FROM access_requests WHERE id = ?");
            $delete->execute(params: [$requestId]);

            echo "<p style=text-align:center;>" . "Demande acceptée, auteur ajouté.";
        } else {
            echo "<p style=text-align:center;>" . "Demande introuvable.";
        }
    } elseif ($action === '🛑 Rejeter') {
        // Supprimer uniquement la demande
        $delete = $pdo->prepare(query: "DELETE FROM access_requests WHERE id = ?");
        $delete->execute(params: [$requestId]);

        echo "<p style=text-align:center;>" . "Demande rejetée" . "</p>";
    }
} else {
    echo "Requête invalide.";
}
?>
<a class="centrer_retour_index" href=" index.php" aria-label="Retour vers la liste des articles">← Retour à
    l'Administration</a>
<? include("../includes/footer.php"); ?>