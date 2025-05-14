<?php
session_start();
include __DIR__ . '/../../../config/config.php';

// Vérifie si utilisateur connecté
if (empty($_SESSION['user']['id'])) {
    exit('Accès refusé.');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $requestId = (int) ($_POST['request_id'] ?? 0);
    $action = $_POST['action'] ?? '';

    if (!$requestId || !in_array($action, ['✔️ Accepter', '🛑 Rejeter'])) {
        exit('Requête invalide.');
    }

    // Récupère la demande
    $stmt = $pdo->prepare("SELECT * FROM access_requests WHERE id = ?");
    $stmt->execute([$requestId]);
    $request = $stmt->fetch();

    if (!$request) {
        exit('Demande non trouvée.');
    }

    if ($action === '✔️ Accepter') {
        // Ajoute l'utilisateur dans authors
        $stmt = $pdo->prepare("INSERT INTO authors (username, first_name, last_name) VALUES (?, ?, ?)");
        $stmt->execute([
            $request['username'],
            $request['first_name'],
            $request['last_name']
        ]);
    }

    // Supprime toujours la demande après traitement
    $stmt = $pdo->prepare("DELETE FROM access_requests WHERE id = ?");
    $stmt->execute([$requestId]);

    header('Location: index.php');
    exit;
} else {
    echo "Méthode non autorisée.";
}
