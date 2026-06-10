<?php
session_start();

if (isset($_SESSION['user'])) {
	header('Location: profil.php');
	exit;
}

$message = isset($_GET['message']) ? trim((string) $_GET['message']) : '';
$messageType = isset($_GET['type']) && $_GET['type'] === 'success' ? 'success' : 'error';
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Inscription | Junia CV</title>
	<link rel="stylesheet" href="../css/style.css">
</head>
<body class="login-page">
	<main class="login-shell">
		<section class="auth-card">
			<h1>Inscription</h1>

			<?php if ($message !== ''): ?>
				<div class="alert <?php echo htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8'); ?>" role="status">
					<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
				</div>
			<?php endif; ?>

			<form class="auth-form" method="post" action="#" novalidate>
				<div class="field">
					<label for="firstname">Prénom</label>
					<input type="text" id="firstname" name="firstname" placeholder="Ton prénom" autocomplete="given-name" required>
				</div>

				<div class="field">
					<label for="lastname">Nom</label>
					<input type="text" id="lastname" name="lastname" placeholder="Ton nom" autocomplete="family-name" required>
				</div>

				<div class="field">
					<label for="email">Adresse e-mail</label>
					<input type="email" id="email" name="email" placeholder="prenom.nom@junia.fr" autocomplete="email" required>
				</div>

				<div class="field">
					<label for="password">Mot de passe</label>
					<input type="password" id="password" name="password" placeholder="Choisis un mot de passe" autocomplete="new-password" required>
				</div>

				<div class="field">
					<label for="confirm_password">Confirmer le mot de passe</label>
					<input type="password" id="confirm_password" name="confirm_password" placeholder="Répète le mot de passe" autocomplete="new-password" required>
				</div>

				<div class="form-row form-row--compact">
					<a class="tiny" href="connexion.php">Déjà un compte ? Connexion</a>
				</div>

				<button type="submit" class="button">Créer mon compte</button>
			</form>
		</section>
	</main>
</body>
</html>
