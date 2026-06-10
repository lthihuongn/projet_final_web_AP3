<?php
session_start();

// Vérification de l'état de connexion de l'utilisateur
$isLoggedIn = isset($_SESSION['user']);
$userName = '';
$userRole = '';

if ($isLoggedIn) {
    $user = $_SESSION['user'];
    $userName = htmlspecialchars((string) ($user['name'] ?? 'Utilisateur'), ENT_QUOTES, 'UTF-8');
    $userRole = htmlspecialchars((string) ($user['role'] ?? 'student'), ENT_QUOTES, 'UTF-8');
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil | Junia CV</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/style.css?v=<?php echo time(); ?>">
</head>
<body class="catalogue-page">

    <header class="topbar">
        <div class="brand-block">
            <div class="brand-logo" aria-hidden="true">J</div>
            <div>
                <p class="brand-kicker">JUNIA CV</p>
                <h1>Réseau Professionnel</h1>
            </div>
        </div>

        <nav class="topnav" aria-label="Navigation principale">
            <a class="is-active" href="index.php">Accueil</a>
            <a href="pages/profil.php">Mon profil</a>
            <a href="pages/catalogue.php">Catalogue</a>
        </nav>
    </header>

    <main class="catalogue-shell">
        
        <section class="catalogue-hero">
            <div>
                <p class="eyebrow">Junia CV & Talents</p>
                <h2>Propulsez votre avenir professionnel</h2>
                <p class="hero-text">
                    La plateforme d'interconnexion exclusive dédiée aux étudiants de JUNIA et aux recruteurs partenaires. Déposez votre CV, valorisez vos compétences techniques et trouvez votre prochain stage, alternance ou premier emploi en quelques clics.
                </p>
            </div>

            <div class="hero-stats">
                <div class="stat-card">
                    <strong>+1 200</strong>
                    <span>Profils actifs</span>
                </div>
                <div class="stat-card">
                    <strong>450</strong>
                    <span>Partenaires</span>
                </div>
                <div class="stat-card">
                    <strong>94%</strong>
                    <span>Insertion</span>
                </div>
            </div>
        </section>

        <section class="catalogue-layout">
            
            <aside class="filters-panel" aria-label="Espace utilisateur">
                <?php if ($isLoggedIn): ?>
                    <h3>Mon Compte</h3>
                    <div style="margin-top: 16px;">
                        <p style="margin: 0; color: #1b1330; font-weight: 700;">Ravi de vous revoir,</p>
                        <p style="margin: 4px 0 12px; font-size: 1.2rem; font-weight: 800; color: var(--junia-violet);">
                            <?php echo $userName; ?>
                        </p>
                        <span class="role-pill" style="margin-left: 0;"><?php echo $userRole; ?></span>
                    </div>
                    
                    <a href="pages/profil.php" class="button" style="text-decoration: none; margin-top: 24px;">
                        Mettre à jour mon profil
                    </a>
                    <a href="pages/catalogue.php" class="secondary-button" style="display: block; text-align: center; text-decoration: none;">
                        Explorer le catalogue
                    </a>
                <?php else: ?>
                    <h3>Rejoindre l'aventure</h3>
                    <p style="color: #665a7f; font-size: 0.95rem; margin-top: 8px; line-height: 1.5;">
                        Connectez-vous pour accéder à l'intégralité des profils étudiants ou pour administrer vos informations de recherche.
                    </p>
                    
                    <a href="pages/connexion.php" class="button" style="text-decoration: none; margin-top: 16px;">
                        Se connecter
                    </a>
                    <a href="pages/inscription.php" class="secondary-button" style="display: block; text-align: center; text-decoration: none;">
                        Créer un compte
                    </a>
                <?php endif; ?>
            </aside>

            <section class="results-panel">
                <div class="results-header">
                    <div>
                        <p class="eyebrow">Fonctionnalités</p>
                        <h3>Ce que propose la plateforme</h3>
                    </div>
                </div>

                <div class="profiles-grid">
                    
                    <article class="profile-card">
                        <div class="profile-card__header">
                            <h4 class="profile-name">Visibilité Augmentée</h4>
                        </div>
                        <p class="profile-bio">
                            Exposez votre profil et vos compétences (Python, Data Science, UX Design, Systèmes embarqués) auprès des recruteurs du réseau partenaire JUNIA de manière claire et structurée.
                        </p>
                        <div class="chip-list">
                            <span class="chip">CV en ligne</span>
                            <span class="chip">Compétences</span>
                        </div>
                    </article>

                    <article class="profile-card">
                        <div class="profile-card__header">
                            <h4 class="profile-name">Filtres Recruteurs</h4>
                        </div>
                        <p class="profile-bio">
                            Un outil de recherche multicritère performant permettant aux entreprises de cibler instantanément des promotions précises (A4, A5, Bachelor) et des types de contrats (Stage, Alternance, CDI).
                        </p>
                        <div class="chip-list">
                            <span class="chip">Recherche libre</span>
                            <span class="chip">Promotions</span>
                        </div>
                    </article>

                    <article class="profile-card">
                        <div class="profile-card__header">
                            <h4 class="profile-name">Convocations directes</h4>
                        </div>
                        <p class="profile-bio">
                            Les recruteurs professionnels ont le privilège de planifier des entretiens et d'envoyer des propositions directement depuis le catalogue de profils de façon centralisée.
                        </p>
                        <div class="chip-list">
                            <span class="chip">Entretiens</span>
                            <span class="chip">Partenariats</span>
                        </div>
                    </article>

                </div>
            </section>
        </section>
    </main>

</body>
</html>