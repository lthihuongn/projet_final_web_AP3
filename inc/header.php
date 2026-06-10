<?php
// Démarrage de la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : S'assurer que $dir est défini même si oublié dans la page appelante
if (!isset($dir)) {
    $dir = '';
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plateforme CV - JUNIA</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&family=Open+Sans:wght@400;600&display=swap" rel="stylesheet">
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <style>
        :root {
            --junia-violet: #6B2C91;
            --junia-orange: #F39200;
            --junia-dark: #333333;
        }
        body {
            font-family: 'Open Sans', sans-serif;
            background-color: #f8f9fa;
            color: var(--junia-dark);
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        h1, h2, h3, h4, h5, h6, .navbar-brand {
            font-family: 'Montserrat', sans-serif;
            font-weight: 600;
        }
        .bg-junia-violet { background-color: var(--junia-violet) !important; }
        .text-junia-orange { color: var(--junia-orange) !important; }
        .btn-junia-orange {
            background-color: var(--junia-orange);
            color: white;
            border: none;
        }
        .btn-junia-orange:hover {
            background-color: #d88100;
            color: white;
        }
        .nav-link {
            color: rgba(255, 255, 255, 0.85) !important;
            transition: color 0.2s ease;
        }
        .nav-link:hover, .nav-link.active {
            color: var(--junia-orange) !important;
        }
    </style>
<!-- Remplacer l'ancienne ligne du CSS par celle-ci : -->
<link rel="stylesheet" href="<?php echo $dir; ?>css/style.css?v=<?php echo time(); ?>"></head>
<body>

<header>
    <nav class="navbar navbar-expand-lg bg-junia-violet navbar-dark shadow-sm">
        <div class="container">
            <a class="navbar-brand d-flex align-items-center" href="/index.php">
                <span class="text-junia-orange me-2">██</span> JUNIA <span class="fw-light ms-1">CV</span>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="/index.php">Accueil</a>
                    </li>
                    
                    <?php if (isset($_SESSION['user_role'])): ?>
                        <?php if ($_SESSION['user_role'] === 'student'): ?>
                            <li class="nav-item"><a class="nav-link" href="/pages/profil.php">Mon Profil / Mon CV</a></li>
                        <?php elseif ($_SESSION['user_role'] === 'company'): ?>
                            <li class="nav-item"><a class="nav-link" href="/pages/catalogue.php">Catalogue Étudiants</a></li>
                        <?php elseif ($_SESSION['user_role'] === 'admin'): ?>
                            <li class="nav-item"><a class="nav-link" href="/pages/admin/dashboard.php">Administration</a></li>
                        <?php endif; ?>
                        
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-outline-light btn-sm" href="/api/auth.php?action=logout">Déconnexion</a>
                        </li>
                    <?php else: ?>
                        <li class="nav-item">
                            <a class="nav-link" href="/pages/contact.php">Devenir Partenaire</a>
                        </li>
                        <li class="nav-item ms-lg-3">
                            <a class="btn btn-junia-orange btn-sm px-3" href="/pages/connexion.php">Connexion</a>
                        </li>
                        <li class="nav-item ms-2">
                            <a class="btn btn-outline-light btn-sm" href="/pages/inscription.php">Inscription Étudiant</a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>
</header>

<main class="container my-5 flex-grow-1">