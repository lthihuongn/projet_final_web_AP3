<?php
session_start();

if (!isset($_SESSION['user'])) {
	header('Location: login.php?message=Merci+de+vous+connecter+pour+accéder+au+catalogue.&type=error');
	exit;
}

$user = $_SESSION['user'];
$userName = htmlspecialchars((string) ($user['name'] ?? 'Utilisateur'), ENT_QUOTES, 'UTF-8');
$userRole = htmlspecialchars((string) ($user['role'] ?? 'student'), ENT_QUOTES, 'UTF-8');

// Définition de la racine relative et inclusion du layout commun
$dir = '../';
require_once '../inc/header.php';
?>

<div class="catalogue-shell" data-profiles-endpoint="../api/profiles.php" data-convoquer-endpoint="../api/interview.php">
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
</div>

<script src="../js/catalog.js"></script>

<?php
require_once '../inc/footer.php';
?>