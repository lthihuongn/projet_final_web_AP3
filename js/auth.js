document.addEventListener('DOMContentLoaded', () => {
	const form = document.getElementById('loginForm');

	if (!form) {
		return;
	}

	const endpoint = form.dataset.endpoint || '../api/auth.php';
	const successUrl = form.dataset.success || 'profil.php';
	const button = document.getElementById('loginButton');
	const originalButtonLabel = button ? button.textContent : 'Se connecter';

	const removeAlert = () => {
		const existingAlert = form.parentElement.querySelector('.alert[data-js-message="1"]');
		if (existingAlert) {
			existingAlert.remove();
		}
	};

	const showMessage = (message, type) => {
		removeAlert();

		const alert = document.createElement('div');
		alert.className = `alert ${type}`;
		alert.setAttribute('role', 'status');
		alert.dataset.jsMessage = '1';
		alert.textContent = message;
		form.parentElement.insertBefore(alert, form);
	};

	form.addEventListener('submit', async (event) => {
		event.preventDefault();

		const email = form.email.value.trim();
		const password = form.password.value;

		if (!email || !password) {
			showMessage('Merci de remplir l’adresse e-mail et le mot de passe.', 'error');
			return;
		}

		if (button) {
			button.disabled = true;
			button.textContent = 'Connexion...';
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
					remember: form.remember.checked
				})
			});

			const data = await response.json().catch(() => null);

			if (!response.ok || !data || !data.success) {
				showMessage((data && data.message) ? data.message : 'Connexion impossible.', 'error');
				return;
			}

			window.location.href = data.redirect || successUrl;
		} catch (error) {
			showMessage('Impossible de contacter le serveur. Réessaie.', 'error');
		} finally {
			if (button) {
				button.disabled = false;
				button.textContent = originalButtonLabel;
			}
		}
	});
});
