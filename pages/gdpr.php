<?php
session_start();

// Configuration de l'environnement global pour le layout commun
$dir = '../';
$simpleHeader = true; // Déclenche le bouton de retour épuré dans le header
require_once '../inc/header.php';
?>

<div class="catalogue-shell" style="max-width: 800px; margin: 0 auto; background: var(--card); padding: 40px; border-radius: 28px; box-shadow: 0 24px 60px rgba(2, 6, 23, 0.2);">
    <h2 style="color: var(--junia-violet); margin-bottom: 24px; font-family: 'Montserrat', sans-serif; font-weight: 700;">Mentions Légales et Politique de Confidentialité</h2>
    
    <h3 style="color: #1b1330; margin-top: 24px; font-family: 'Montserrat', sans-serif; font-weight: 600;">1. Collecte des données</h3>
    <p style="color: #665a7f; line-height: 1.6;">Dans le cadre de l'utilisation de la plateforme "Junia CV", nous collectons les données personnelles des étudiants (Nom, Prénom, Email, Parcours, Compétences) afin de générer un CV standardisé et de faciliter la mise en relation avec des entreprises partenaires.</p>

    <h3 style="color: #1b1330; margin-top: 24px; font-family: 'Montserrat', sans-serif; font-weight: 600;">2. Stockage et Sécurité</h3>
    <p style="color: #665a7f; line-height: 1.6;">Les mots de passe sont rigoureusement hachés via des protocoles cryptographiques standards (Bcrypt). Aucune donnée sensible n'est stockée en clair dans notre base de données.</p>

    <h3 style="color: #1b1330; margin-top: 24px; font-family: 'Montserrat', sans-serif; font-weight: 600;">3. Droit d'accès et de suppression</h3>
    <p style="color: #665a7f; line-height: 1.6;">Conformément au Règlement Général sur la Protection des Données (RGPD), chaque utilisateur dispose d'un droit d'accès, de rectification et de suppression de ses données personnelles. Vous pouvez supprimer l'intégralité de votre compte depuis votre page profil ou en contactant l'administration JUNIA.</p>
</div>

<?php
require_once '../inc/footer.php';
?>