<?php
session_start();

// On réactive la sécurité : si non connecté, retour à la page de connexion (anglaise)
if (!isset($_SESSION['user'])) {
	header('Location: login.php?message=Merci+de+vous+connecter+pour+accéder+au+catalogue.&type=error');
	exit;
}

$user = $_SESSION['user'];
$userName = htmlspecialchars((string) ($user['name'] ?? 'Utilisateur'), ENT_QUOTES, 'UTF-8');
$userRole = htmlspecialchars((string) ($user['role'] ?? 'student'), ENT_QUOTES, 'UTF-8');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
	<meta charset="UTF-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Catalogue des profils | Junia CV</title>
	<link rel="preconnect" href="https://fonts.googleapis.com">
	<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
	<link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
	<link rel="stylesheet" href="../css/style.css?v=<?php echo time(); ?>">
</head>
<body class="catalogue-page">
	<header class="topbar">
		<div class="brand-block">
			<div class="brand-logo" aria-hidden="true">J</div>
			<div>
				<p class="brand-kicker">JUNIA CV</p>
				<h1>Catalogue des profils</h1>
			</div>
		</div>

		<nav class="topnav" aria-label="Navigation principale">
			<a href="../index.php">Accueil</a>
			<?php if ($userRole === 'student'): ?>
				<a href="profile.php">Mon profil</a>
			<?php endif; ?>
			<a class="is-active" href="catalog.php">Catalogue</a>
			<?php if ($userRole === 'admin'): ?>
				<a href="admin.php">Administration</a>
			<?php endif; ?>
			<a href="../api/auth.php?action=logout" style="color: var(--junia-orange);">Déconnexion</a>
		</nav>
	</header>

	<main class="catalogue-shell" data-profiles-endpoint="../api/profiles.php" data-convoquer-endpoint="../api/interview.php">
		<section class="catalogue-hero">
			<div>
				<p class="eyebrow">Accès entreprise</p>
				<h2>Rechercher, filtrer et convoquer des étudiants JUNIA</h2>
				<p class="hero-text">Connecté en tant que <strong><?php echo $userName; ?></strong> <span class="role-pill"><?php echo $userRole; ?></span>. Parcourez les profils, ciblez les bons domaines et planifiez un entretien en quelques secondes.</p>
			</div>

			<div class="hero-stats" id="catalogueStats">
				<div class="stat-card">
					<strong id="profilesCount">0</strong>
					<span>profils visibles</span>
				</div>
				<div class="stat-card">
					<strong id="convoquesCount">0</strong>
					<span>convocations</span>
				</div>
				<div class="stat-card">
					<strong>JUNIA</strong>
					<span>réseau partenaires</span>
				</div>
			</div>
		</section>

		<section class="catalogue-layout">
			<aside class="filters-panel" aria-label="Filtres de recherche">
				<h3>Filtres</h3>

				<label class="filter-field">
					<span>Recherche libre</span>
					<input type="search" id="searchInput" placeholder="Nom, compétence, école, domaine">
				</label>

				<label class="filter-field">
					<span>Domaine</span>
					<select id="domainFilter">
						<option value="">Tous les domaines</option>
						<option value="Stage">Stage</option>
						<option value="Alternance">Alternance</option>
						<option value="CDI">CDI</option>
						<option value="Mobilité">Mobilité</option>
					</select>
				</label>

				<label class="filter-field">
					<span>Type de contrat</span>
					<select id="contractFilter">
						<option value="">Tous les contrats</option>
						<option value="stage">Stage</option>
						<option value="alternance">Alternance</option>
						<option value="professionnalisation">Professionnalisation</option>
						<option value="cdi">CDI</option>
					</select>
				</label>

				<label class="filter-field">
					<span>Compétence</span>
					<input type="text" id="skillFilter" placeholder="Python, data, UX, réseau...">
				</label>

				<label class="filter-field">
					<span>École / promotion</span>
					<select id="schoolFilter">
						<option value="">Toutes les promotions</option>
						<option value="junia-a4">JUNIA A4</option>
						<option value="junia-a5">JUNIA A5</option>
						<option value="junia-bachelor">Bachelor</option>
					</select>
				</label>

				<button type="button" class="secondary-button" id="resetFilters">Réinitialiser</button>
			</aside>

			<section class="results-panel">
				<div class="results-header">
					<div>
						<p class="eyebrow">Profils étudiants</p>
						<h3>Catalogue consultable</h3>
					</div>
					<p class="results-meta" id="resultsMeta">Chargement des profils...</p>
				</div>

				<div class="notice" id="catalogueMessage" role="status" aria-live="polite"></div>
				<div class="profiles-grid" id="profilesGrid"></div>
			</section>
		</section>
	</main>

	<script src="../js/catalog.js"></script>
</body>
</html>