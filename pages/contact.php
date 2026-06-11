<?php
session_start();

// Configuration de l'environnement global pour le layout commun
$dir = '../';
$simpleHeader = true; // Déclenche le bouton de retour épuré dans le header
require_once '../inc/header.php';
?>

<div class="catalogue-shell" style="max-width: 680px; margin: 0 auto;">
    <section class="auth-card" style="width: 100%; background: var(--card); padding: 40px; border-radius: 28px; box-shadow: 0 24px 60px rgba(2, 6, 23, 0.2); text-align: left;">
        <h2 style="color: var(--junia-violet); margin-bottom: 12px; font-family: 'Montserrat', sans-serif; font-weight: 700;">Devenir Partenaire</h2>
        <p style="color: #665a7f; margin-bottom: 28px; line-height: 1.6;">Vous souhaitez recruter nos talents ? Laissez-nous vos coordonnées, l'équipe JUNIA vous créera un accès très rapidement.</p>

        <form class="auth-form" method="post" action="#">
            <div class="form-grid form-grid--two" style="margin-bottom: 16px;">
                <label class="field">
                    <span>Nom de l'entreprise</span>
                    <input type="text" id="company_name" name="company_name" required>
                </label>
                <label class="field">
                    <span>Contact (Nom, Prénom)</span>
                    <input type="text" id="contact_person" name="contact_person" required>
                </label>
            </div>

            <div class="field" style="margin-bottom: 16px;">
                <span>Adresse e-mail professionnelle</span>
                <input type="email" id="email" name="email" required>
            </div>

            <div class="field" style="margin-bottom: 24px;">
                <span>Votre message / Besoins de recrutement</span>
                <textarea id="message" name="message" rows="4" style="width: 100%; border: 1px solid rgba(107, 44, 145, 0.15); border-radius: 16px; padding: 14px; font-family: inherit; resize: vertical;"></textarea>
            </div>

            <button type="submit" class="button">Envoyer la demande</button>
        </form>
    </section>
</div>

<?php
require_once '../inc/footer.php';
?>