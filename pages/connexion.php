<?php
session_start();

// Redirection intelligente si l'utilisateur est déjà connecté
if (isset($_SESSION['user'])) {
    $userRole = $_SESSION['user']['role'] ?? 'student';
    
    if ($userRole === 'company') {
        header('Location: catalogue.php');
    } elseif ($userRole === 'admin') {
        header('Location: admin.php');
    } else {
        header('Location: profil.php');
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Connexion | Junia CV</title>
	<link rel="stylesheet" href="../css/style.css">
</head>
<body class="login-page">
	<main class="login-shell">
		<section class="auth-card">
			<h1>Connexion</h1>

			<?php if ($message !== ''): ?>
				<div class="alert <?php echo htmlspecialchars($messageType, ENT_QUOTES, 'UTF-8'); ?>" role="status">
					<?php echo htmlspecialchars($message, ENT_QUOTES, 'UTF-8'); ?>
				</div>
			<?php endif; ?>

			<form class="auth-form" id="loginForm" data-endpoint="../api/auth.php" data-success="profil.php" novalidate>
				<div class="field">
					<label for="email">Adresse e-mail</label>
					<input type="email" id="email" name="email" placeholder="prenom.nom@junia.fr" autocomplete="email" required>
				</div>

				<div class="field">
					<label for="password">Mot de passe</label>
					<input type="password" id="password" name="password" placeholder="Ton mot de passe" autocomplete="current-password" required>
				</div>

				<div class="form-row form-row--compact">
					</label>
					<a class="tiny" href="inscription.php">Créer un compte</a>
				</div>

				<button type="submit" class="button" id="loginButton">Se connecter</button>
			</form>
		</section>
	</main>

	<script src="../js/auth.js"></script>
</body>
</html>
