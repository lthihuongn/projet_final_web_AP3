<?php
// Démarrage de la session
session_start();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contact | Junia CV</title>
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="login-page">
    <main class="login-shell">
        <section class="auth-card" style="width: min(100%, 600px);">
            <h1>Devenir Partenaire</h1>
            <p>Vous souhaitez recruter nos talents ? Laissez-nous vos coordonnées, l'équipe JUNIA vous créera un accès très rapidement.</p>

            <form class="auth-form" method="post" action="#">
                <div class="form-row form-row--compact">
                    <div class="field" style="flex: 1;">
                        <label for="company_name">Nom de l'entreprise</label>
                        <input type="text" id="company_name" name="company_name" required>
                    </div>
                    <div class="field" style="flex: 1;">
                        <label for="contact_person">Contact (Nom, Prénom)</label>
                        <input type="text" id="contact_person" name="contact_person" required>
                    </div>
                </div>

                <div class="field">
                    <label for="email">Adresse e-mail professionnelle</label>
                    <input type="email" id="email" name="email" required>
                </div>

                <div class="field">
                    <label for="message">Votre message / Besoins de recrutement</label>
                    <textarea id="message" name="message" rows="4" style="width: 100%; border: 1px solid rgba(148, 163, 184, 0.2); border-radius: 16px; padding: 14px; font-family: inherit;"></textarea>
                </div>

                <button type="submit" class="button" style="margin-top: 16px;">Envoyer la demande</button>
                <div style="text-align: center; margin-top: 16px;">
                    <a class="tiny" href="../index.php">Retour à l'accueil</a>
                </div>
            </form>
        </section>
    </main>
</body>
</html>