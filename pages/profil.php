<?php
session_start();

/*if (!isset($_SESSION['user'])) {
	header('Location: connexion.php?message=Merci+de+vous+connecter+pour+acc%C3%A9der+au+profil.&type=error');
	exit;
}*/

$user = $_SESSION['user'];
$userName = htmlspecialchars((string) ($user['name'] ?? 'Utilisateur'), ENT_QUOTES, 'UTF-8');
$userRole = htmlspecialchars((string) ($user['role'] ?? 'student'), ENT_QUOTES, 'UTF-8');
$userEmail = htmlspecialchars((string) ($user['email'] ?? 'prenom.nom@junia.fr'), ENT_QUOTES, 'UTF-8');
$initials = strtoupper(substr((string) ($user['name'] ?? 'U'), 0, 1));
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Mon profil | Junia CV</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/style.css?v=20260610">
	<script src="../js/form-cv.js" defer></script>
</head>
<body class="profil-page">
	<header class="topbar">
		<div class="brand-block">
			<div class="brand-logo" aria-hidden="true">J</div>
			<div>
				<p class="brand-kicker">JUNIA CV</p>
				<h1>Mon profil</h1>
			</div>
		</div>

		<nav class="topnav" aria-label="Navigation principale">
			<a href="../index.php">Accueil</a>
			<a class="is-active" href="profil.php">Mon profil</a>
			<a href="catalogue.php">Catalogue</a>
		</nav>
	</header>

	<main class="profil-shell">
		<section class="profil-hero">
			<div>
				<p class="eyebrow">Espace étudiant</p>
				<h2>Complète ton profil pour générer un CV propre et lisible</h2>
				<p class="hero-text">Ton espace centralise les informations utilisées pour ton CV JUNIA, la consultation par les entreprises et les futures candidatures.</p>
			</div>

			<div class="profil-status-card">
				<div class="avatar-badge" aria-hidden="true"><?php echo $initials; ?></div>
				<div>
					<p class="profile-name"><?php echo $userName; ?></p>
					<p class="profile-meta"><?php echo $userEmail; ?></p>
					<span class="role-pill"><?php echo $userRole; ?></span>
				</div>
			</div>
		</section>

		<section class="profil-layout">
			<aside class="profil-sidecard">
				<h3>Vue rapide</h3>
				<p class="profil-note">Les informations sont sauvegardées localement pour l’instant, en attendant la connexion au back-end.</p>
				<div class="mini-stats">
					<div class="stat-card">
						<strong>8</strong>
						<span>sections à compléter</span>
					</div>
					<div class="stat-card">
						<strong>PDF</strong>
						<span>prêt à générer</span>
					</div>
					<div class="stat-card">
						<strong>JUNIA</strong>
						<span>format standardisé</span>
					</div>
				</div>

				<div class="profil-checklist">
					<p class="eyebrow">À finaliser</p>
					<ul>
						<li>Photo de profil</li>
						<li>Biographie</li>
						<li>Parcours académique</li>
						<li>Expériences</li>
						<li>Compétences</li>
					</ul>
				</div>
			</aside>

			<section class="profil-content">
				<form class="profil-form" id="profilForm" action="../api/enregistrer-cv.php" method="post">
					
					<div class="profil-section">
						<div class="section-header">
							<div>
								<p class="eyebrow">Identité</p>
								<h3>Informations personnelles</h3>
							</div>
						</div>

						<div class="form-grid form-grid--two">
							<label class="field">
								<span>Nom complet</span>
								<input type="text" name="name" value="<?php echo $userName; ?>" placeholder="Prénom Nom" required>
							</label>
							<label class="field">
								<span>E-mail</span>
								<input type="email" name="email" value="<?php echo $userEmail; ?>" required readonly>
							</label>
						</div>
					</div>

					<div class="profil-section">
						<p class="eyebrow">Présentation</p>
						<h3>Biographie / lettre de motivation</h3>
						<label class="field field--full">
							<span>Texte de présentation</span>
							<textarea name="bio" rows="5" placeholder="Présente ton parcours, ton projet et ce que tu recherches."></textarea>
						</label>
					</div>

					<div class="profil-section">
						<p class="eyebrow">Parcours</p>
						<h3>Dernière formation</h3>
						<div class="form-grid form-grid--three">
							<label class="field">
								<span>École</span>
								<input type="text" name="school" placeholder="JUNIA - ISEN / HEI / ISA">
							</label>
							<label class="field">
								<span>Diplôme / Spécialisation</span>
								<input type="text" name="degree" placeholder="CIR, Adimaker, Smart Cities...">
							</label>
							<label class="field">
								<span>Date de fin de formation</span>
								<input type="date" name="education_end_date">
							</label>
						</div>
					</div>

					<div class="profil-section">
						<p class="eyebrow">Expérience</p>
						<h3>Dernière expérience professionnelle</h3>
						<div class="form-grid form-grid--two">
							<label class="field">
								<span>Entreprise</span>
								<input type="text" name="company" placeholder="Nom de l'entreprise">
							</label>
							<label class="field">
								<span>Poste occupé</span>
								<input type="text" name="position" placeholder="Ex: Stagiaire développeur">
							</label>
							<label class="field">
								<span>Date de début</span>
								<input type="date" name="experience_start_date">
							</label>
							<label class="field">
								<span>Date de fin</span>
								<input type="date" name="experience_end_date">
							</label>
						</div>
						<div class="form-grid style="margin-top: 14px;">
							<label class="field field--full">
								<span>Description des missions</span>
								<textarea name="experience_description" rows="3" placeholder="Détaille tes réalisations et tâches principales..."></textarea>
							</label>
						</div>
					</div>

					<div class="profil-section">
						<p class="eyebrow">Compétences</p>
						<h3>Mots-clés et Domaines de recherche</h3>
						<div class="form-grid">
							<label class="field field--full">
								<span>Compétences techniques (séparées par des virgules)</span>
								<input type="text" name="skills" placeholder="PHP, MySQL, JavaScript, Cybersécurité...">
							</label>
						</div>

						<div class="checklist-grid">
							<label class="check-item"><input type="checkbox" name="domains[]" value="Stage"> Stage</label>
							<label class="check-item"><input type="checkbox" name="domains[]" value="Alternance"> Alternance</label>
							<label class="check-item"><input type="checkbox" name="domains[]" value="CDI"> CDI</label>
							<label class="check-item"><input type="checkbox" name="domains[]" value="Mobilité"> Mobilité</label>
						</div>
					</div>

					<div class="profil-actions">
						<button type="submit" class="button">Enregistrer mon profil</button>
						<a class="secondary-button profil-link" href="catalogue.php">Voir le catalogue</a>
					</div>
				</form>
			</section>
		</section>
	</main>
</body>
</html>