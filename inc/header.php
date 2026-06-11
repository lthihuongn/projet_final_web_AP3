<?php
// Démarrage de la session si elle n'est pas déjà active
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Sécurité : S'assurer que la variable de répertoire est définie
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
    <link rel="stylesheet" href="<?php echo $dir; ?>css/style.css?v=<?php echo time(); ?>">
</head>
<body>

<header class="topbar">
    <div class="brand-block">
        <div class="brand-logo" aria-hidden="true">J</div>
        <div>
            <p class="brand-kicker">JUNIA CV</p>
        </div>
    </div>

    <nav class="topnav" aria-label="Navigation principale">
        <a href="<?php echo $dir; ?>index.php">Accueil</a>
        
        <?php if (isset($_SESSION['user'])): ?>
            <?php if ($_SESSION['user']['role'] === 'student'): ?>
                <a href="<?php echo $dir; ?>pages/profile.php">Mon profil</a>
                <a href="<?php echo $dir; ?>pages/catalog.php">Catalogue</a>
            <?php elseif ($_SESSION['user']['role'] === 'company'): ?>
                <a href="<?php echo $dir; ?>pages/catalog.php">Catalogue</a>
            <?php elseif ($_SESSION['user']['role'] === 'admin'): ?>
                <a href="<?php echo $dir; ?>pages/admin.php">Administration</a>
                <a href="<?php echo $dir; ?>pages/catalog.php">Catalogue</a>
            <?php endif; ?>
            
            <a href="<?php echo $dir; ?>api/auth.php?action=logout" style="color: var(--junia-orange);">Déconnexion</a>
        <?php else: ?>
            <a href="<?php echo $dir; ?>pages/login.php">Connexion</a>
        <?php endif; ?>
    </nav>
</header>

<main class="container my-5 flex-grow-1">