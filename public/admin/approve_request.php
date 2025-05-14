<?php
session_start();
include __DIR__ . '/../../config/config.php'; // ← Corrigé ici

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
        // Ajoute l'utilisateur dans authors avec un mot de passe par défaut ou celui de la demande
        $password = password_hash('motdepassepardefaut', PASSWORD_BCRYPT); // Remplace par le mot de passe réel si nécessaire

        $stmt = $pdo->prepare("INSERT INTO authors (username, first_name, last_name, password) VALUES (?, ?, ?, ?)");
        $stmt->execute([
            $request['username'],
            $request['first_name'],
            $request['last_name'],
            $password
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
