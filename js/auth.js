document.addEventListener('DOMContentLoaded', () => {
    // 1. Détection et configuration du formulaire de connexion
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        setupLoginForm(loginForm);
    }

    // 2. Détection et configuration du formulaire d'inscription
    const registerForm = document.getElementById('registerForm');
    if (registerForm) {
        setupRegisterForm(registerForm);
    }
});

/**
 * Supprime une alerte de message existante au-dessus du formulaire spécifié
 */
function removeFormAlert(formElement) {
    const existingAlert = formElement.parentElement.querySelector('.alert[data-js-message="1"]');
    if (existingAlert) {
        existingAlert.remove();
    }
}

/**
 * Insère un bandeau d'alerte (succès ou erreur) juste au-dessus du formulaire concerné
 */
function showFormMessage(formElement, message, type) {
    removeFormAlert(formElement);

    const alertElement = document.createElement('div');
    alertElement.className = `alert ${type}`;
    alertElement.setAttribute('role', 'status');
    alertElement.dataset.jsMessage = '1';
    alertElement.textContent = message;
    formElement.parentElement.insertBefore(alertElement, formElement);
}

/**
 * Gère la validation et la soumission asynchrone du formulaire de connexion
 */
function setupLoginForm(form) {
    const endpoint = form.dataset.endpoint || '../api/auth.php';
    const successUrl = form.dataset.success || 'profile.php'; // Mis à jour
    const submitButton = document.getElementById('loginButton');
    const originalButtonLabel = submitButton ? submitButton.textContent : 'Se connecter';

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        const email = form.email.value.trim();
        const password = form.password.value;

        if (!email || !password) {
            showFormMessage(form, 'Merci de remplir l’adresse e-mail et le mot de passe.', 'error');
            return;
        }

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Connexion...';
        }

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    email,
                    password,
                    remember: form.remember ? form.remember.checked : false
                })
            });

            const data = await response.json().catch(() => null);

            if (!response.ok || !data || !data.success) {
                showFormMessage(form, (data && data.message) ? data.message : 'Connexion impossible.', 'error');
                return;
            }

            window.location.href = data.redirect || successUrl;
        } catch (error) {
            showFormMessage(form, 'Impossible de contacter le serveur. Réessaie.', 'error');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalButtonLabel;
            }
        }
    });
}

/**
 * Gère la validation et la soumission asynchrone du formulaire d'inscription
 */
function setupRegisterForm(form) {
    const endpoint = form.dataset.endpoint || '../api/register.php';
    const submitButton = form.querySelector('button[type="submit"]');
    const originalButtonLabel = submitButton ? submitButton.textContent : 'Créer mon compte';

    form.addEventListener('submit', async (event) => {
        event.preventDefault();

        // Récupération des données du formulaire
        const firstname = form.firstname.value.trim();
        const lastname = form.lastname.value.trim();
        const email = form.email.value.trim();
        const password = form.password.value;
        const confirmPassword = form.confirm_password.value;
        const gdprConsent = form.gdpr_consent ? form.gdpr_consent.checked : false;

        // Validation des champs obligatoires
        if (!firstname || !lastname || !email || !password || !confirmPassword) {
            showFormMessage(form, 'Merci de remplir tous les champs obligatoires.', 'error');
            return;
        }

        if (password !== confirmPassword) {
            showFormMessage(form, 'Les mots de passe ne correspondent pas.', 'error');
            return;
        }

        if (!gdprConsent) {
            showFormMessage(form, 'Vous devez accepter la politique de confidentialité pour vous inscrire.', 'error');
            return;
        }

        if (submitButton) {
            submitButton.disabled = true;
            submitButton.textContent = 'Inscription...';
        }

        try {
            const response = await fetch(endpoint, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    firstname,
                    lastname,
                    email,
                    password,
                    confirm_password: confirmPassword
                })
            });

            const data = await response.json().catch(() => null);

            if (!response.ok || !data || !data.success) {
                showFormMessage(form, (data && data.message) ? data.message : 'Inscription impossible.', 'error');
                return;
            }

            // Redirection vers la page de connexion
            window.location.href = data.redirect || 'login.php'; // Mis à jour
        } catch (error) {
            showFormMessage(form, 'Impossible de contacter le serveur. Réessaie.', 'error');
        } finally {
            if (submitButton) {
                submitButton.disabled = false;
                submitButton.textContent = originalButtonLabel;
            }
        }
    });
}