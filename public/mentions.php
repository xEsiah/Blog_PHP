<?php
require_once __DIR__ . '/../config/config.php';
require_once __DIR__ . '/../includes/header.php'; ?>
<style>
    body {
        background-image: url("<?= BASE_URL ?>/images/background2.jpg");
        text-shadow: none;
    }

    .background {

        background-color: rgba(0, 0, 0, 0.48);
        padding: 20px;
        border-radius: 10px;
        margin: 20px;

    }

    a {
        color: #00ff00;
        text-decoration: none;
    }

    .gromp {
        display: flex;
        justify-content: center;
        align-items: center;
        margin-top: 20px;
    }
</style>
<div class="background">
    <h2>Mentions Légales</h2>
    <p>Ce site est un projet fictif et n'a pas vocation à être utilisé à des fins commerciales.</p>
    <p>Les informations fournies sur ce site sont à titre informatif uniquement et ne sauraient engager la
        responsabilité de
        l'auteur.</p>
    <p>Les contenus publiés sur ce site sont la propriété de leurs auteurs respectifs.</p>
    <p>Fond d'écran du site par <a target=_blank href="https://lukas_truzzi.artstation.com/resume">Lukas Truzzi</a>
    </p>
    <p>Fond d'écran de la page Mentions par <a href="https://x.com/wndr0001">wndr0001</a></p>
    <div class="gromp">
        <img src="images/xd.png" alt="Gromp" style="width: 50%; height: auto; border-radius: 10px;">
    </div>
</div>
<a class="centrer_retour_index" href="<?= BASE_URL ?>/index.php" aria-label="Retour vers la liste des articles">←
    Retour à
    l'accueil</a>
<?php include '../includes/footer.php'; ?>