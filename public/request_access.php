<?php
include '../includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['first_name'], $_POST['last_name'], $_POST['username'], $_POST['password'], $_POST['motivation'])) {
        include '../config/config.php';

        // Vérifie si le nom d'utilisateur existe déjà
        $check = $pdo->prepare(query: "SELECT COUNT(*) FROM (
            SELECT username FROM authors
            UNION ALL
            SELECT username FROM access_requests
        ) AS all_usernames WHERE username = ?");
        $check->execute(params: [$_POST['username']]);
        $exists = $check->fetchColumn();

        if ($exists > 0) {
            echo "<p>Ce nom d'utilisateur est déjà utilisé.</p>";
        } else {
            // Insère la demande
            $stmt = $pdo->prepare(query: "INSERT INTO access_requests (first_name, last_name, username, password, motivation) VALUES (?, ?, ?, ?, ?)");
            $stmt->execute(params: [
                $_POST['first_name'],
                $_POST['last_name'],
                $_POST['username'],
                password_hash(password: $_POST['password'], algo: PASSWORD_BCRYPT),
                $_POST['motivation']
            ]);
            echo "<p>Votre demande a bien été envoyée. Un administrateur l'examinera bientôt.</p>";
        }
    } else {
        echo "<p>Tous les champs doivent être remplis.</p>";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Demande d'adhésion</title>
</head>

<body>
    <h1>Faire une demande d'adhésion</h1>

    <form class="request-container" action="request_access.php" method="POST">
        <label for="first_name">Prénom:</label>
        <input type="text" id="first_name" name="first_name" required>

        <label for="last_name">Nom:</label>
        <input type="text" id="last_name" name="last_name" required>

        <label for="username">Nom d'utilisateur:</label>
        <input type="text" id="username" name="username" required>

        <label for="password">Mot de passe:</label>
        <input type="password" id="password" name="password" required>

        <label for="motivation">Motivation pour rejoindre les auteurs:</label>
        <textarea id="motivation" name="motivation" rows="4" required></textarea>

        <input type="submit" value="Envoyer la demande">
    </form>

    <a class="centrer_retour_index" href="../public/index.php" aria-label="Retour vers la liste des articles">← Retour à
        l'accueil</a>
</body>

</html>