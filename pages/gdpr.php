<?php
// Démarrage de la session
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mentions Légales & RGPD | Junia CV</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="catalogue-page">
    <header class="topbar">
        <div class="brand-block">
            <div class="brand-logo" aria-hidden="true">J</div>
            <div>
                <p class="brand-kicker">JUNIA CV</p>
                <h1>Conformité RGPD</h1>
            </div>
        </div>
        <nav class="topnav">
            <a href="../index.php">Retour à l'accueil</a>
        </nav>
    </header>

    <main class="catalogue-shell" style="max-width: 800px; margin: 0 auto; background: var(--card); padding: 40px; border-radius: 28px;">
        <h2 style="color: var(--junia-violet); margin-bottom: 24px;">Mentions Légales et Politique de Confidentialité</h2>
        
        <h3 style="color: #1b1330; margin-top: 24px;">1. Collecte des données</h3>
        <p style="color: var(--muted); line-height: 1.6;">Dans le cadre de l'utilisation de la plateforme "Junia CV", nous collectons les données personnelles des étudiants (Nom, Prénom, Email, Parcours, Compétences) afin de générer un CV standardisé et de faciliter la mise en relation avec des entreprises partenaires.</p>

        <h3 style="color: #1b1330; margin-top: 24px;">2. Stockage et Sécurité</h3>
        <p style="color: var(--muted); line-height: 1.6;">Les mots de passe sont rigoureusement hachés via des protocoles cryptographiques standards (Bcrypt). Aucune donnée sensible n'est stockée en clair dans notre base de données.</p>

        <h3 style="color: #1b1330; margin-top: 24px;">3. Droit d'accès et de suppression</h3>
        <p style="color: var(--muted); line-height: 1.6;">Conformément au Règlement Général sur la Protection des Données (RGPD), chaque utilisateur dispose d'un droit d'accès, de rectification et de suppression de ses données personnelles. Vous pouvez supprimer l'intégralité de votre compte depuis votre page profil ou en contactant l'administration JUNIA.</p>
    </main>
</body>
</html>