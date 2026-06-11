<?php
session_start();

// Redirection intelligente si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    $userRole = $_SESSION['user']['role'] ?? 'student';
    
    if ($userRole === 'company') {
        header('Location: catalog.php');
    } elseif ($userRole === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: profile.php');
    }
    exit;
}
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

            <?php if (isset($_GET['message'])): ?>
				<div class="alert <?php echo htmlspecialchars($_GET['type'] ?? 'info', ENT_QUOTES, 'UTF-8'); ?>" role="status">
					<?php echo htmlspecialchars($_GET['message'], ENT_QUOTES, 'UTF-8'); ?>
				</div>
			<?php endif; ?>

			<form class="auth-form" id="registerForm" data-endpoint="../api/register.php" novalidate>
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
					<a class="tiny" href="login.php">Déjà un compte ? Connexion</a>
				</div>

				<div class="field" style="margin-top: 8px;">
    				<label class="checkbox" style="align-items: flex-start;">
        				<input type="checkbox" name="gdpr_consent" required style="margin-top: 4px;">
        				<span style="font-weight: normal; font-size: 0.85rem; line-height: 1.4;">
           					 J'accepte que mes données personnelles soient collectées, stockées et affichées aux entreprises partenaires de JUNIA. 
            				<a href="gdpr.php" style="color: var(--accent); text-decoration: underline;">En savoir plus</a>.
        				</span>
    				</label>
				</div>

				<button type="submit" class="button">Créer mon compte</button>
			</form>
		</section>
	</main>
	<script src="../js/auth.js"></script>
</body>
</html>