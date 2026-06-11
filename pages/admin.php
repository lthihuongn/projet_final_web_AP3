<?php
// Démarrage de la session
session_start();

// Vérification stricte des droits administrateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'admin') {
    header('Location: login.php?message=Accès+réservé+à+l\'administration.&type=error');
    exit;
}

// Inclusion de la connexion à la base de données
require_once '../inc/db.php';

$systemMessage = '';
$messageType = 'success';

// Traitement de la création manuelle d'une entreprise par l'administrateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['form_action']) && $_POST['form_action'] === 'create_company') {
    $companyName = trim((string) $_POST['company_name']);
    $contactEmail = trim((string) $_POST['contact_email']);
    $industrySector = trim((string) $_POST['industry_sector']);
    
    // Génération d'un mot de passe provisoire sécurisé (8 caractères)
    $temporaryPassword = bin2hex(random_bytes(4));
    $hashedPassword = password_hash($temporaryPassword, PASSWORD_DEFAULT);

    try {
        $insertCompanyQuery = $pdo->prepare('
            INSERT INTO entreprises (nom, email_contact, password_hash, secteur) 
            VALUES (:name, :email, :password, :sector)
        ');
        $insertCompanyQuery->execute([
            'name' => $companyName,
            'email' => $contactEmail,
            'password' => $hashedPassword,
            'sector' => $industrySector
        ]);
        $systemMessage = "Entreprise ajoutée avec succès. Le mot de passe généré est : <strong>{$temporaryPassword}</strong> (à transmettre au partenaire).";
    } catch (PDOException $exception) {
        $messageType = 'error';
        $systemMessage = "Erreur lors de la création de l'entreprise (l'email est peut-être déjà utilisé).";
    }
}

// Récupération des données pour les tableaux de bord
$studentsQuery = $pdo->query('SELECT id, nom, email, date_creation FROM etudiants ORDER BY date_creation DESC');
$studentList = $studentsQuery->fetchAll();

$companiesQuery = $pdo->query('SELECT id, nom, email_contact, secteur FROM entreprises ORDER BY date_creation DESC');
$companyList = $companiesQuery->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration | Junia CV</title>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@600;700;800&family=Open+Sans:wght@400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="../css/style.css">
</head>
<body class="catalogue-page">
    <header class="topbar">
        <div class="brand-block">
            <div class="brand-logo" aria-hidden="true">J</div>
            <div>
                <p class="brand-kicker">JUNIA CV</p>
                <h1>Administration</h1>
            </div>
        </div>
        <nav class="topnav">
            <a href="../index.php">Accueil</a>
            <a href="api/auth.php?action=logout">Déconnexion</a>
        </nav>
    </header>

    <main class="catalogue-shell">
        <section class="catalogue-hero" style="grid-template-columns: 1fr;">
            <div>
                <p class="eyebrow">Tableau de bord</p>
                <h2>Gestion de la plateforme</h2>
                <p class="hero-text">Gérez les comptes étudiants, modérez les profils et invitez de nouvelles entreprises partenaires.</p>
            </div>
        </section>

        <?php if ($systemMessage !== ''): ?>
            <div class="notice" data-type="<?php echo $messageType; ?>" style="display: block;">
                <?php echo $systemMessage; ?>
            </div>
        <?php endif; ?>

        <section class="catalogue-layout">
            <aside class="filters-panel">
                <h3>Ajouter une entreprise</h3>
                <form method="post" action="admin.php" style="margin-top: 16px;">
                    <input type="hidden" name="form_action" value="create_company">
                    <label class="filter-field">
                        <span>Nom de l'entreprise</span>
                        <input type="text" name="company_name" required>
                    </label>
                    <label class="filter-field">
                        <span>Email de contact</span>
                        <input type="email" name="contact_email" required>
                    </label>
                    <label class="filter-field">
                        <span>Secteur d'activité</span>
                        <input type="text" name="industry_sector" placeholder="Ex: IT, Énergie, Cybersécurité...">
                    </label>
                    <button type="submit" class="button button--small" style="margin-top: 16px;">Créer le compte</button>
                </form>
            </aside>

            <section class="results-panel">
                <div class="results-header">
                    <h3>Comptes Étudiants (<?php echo count($studentList); ?>)</h3>
                </div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; text-align: left; border-collapse: collapse; margin-bottom: 32px;">
                        <tr style="border-bottom: 1px solid rgba(107, 44, 145, 0.2);">
                            <th style="padding: 12px;">ID</th>
                            <th style="padding: 12px;">Nom</th>
                            <th style="padding: 12px;">Email</th>
                            <th style="padding: 12px;">Action</th>
                        </tr>
                        <?php foreach ($studentList as $student): ?>
                        <tr style="border-bottom: 1px solid rgba(107, 44, 145, 0.1);">
                            <td style="padding: 12px;"><?php echo $student['id']; ?></td>
                            <td style="padding: 12px;"><strong><?php echo htmlspecialchars((string)$student['nom']); ?></strong></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars((string)$student['email']); ?></td>
                            <td style="padding: 12px;"><button class="secondary-button" style="padding: 6px 12px; margin: 0;">Supprimer</button></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>

                <div class="results-header">
                    <h3>Entreprises Partenaires (<?php echo count($companyList); ?>)</h3>
                </div>
                <div style="overflow-x: auto;">
                    <table style="width: 100%; text-align: left; border-collapse: collapse;">
                        <tr style="border-bottom: 1px solid rgba(107, 44, 145, 0.2);">
                            <th style="padding: 12px;">Entreprise</th>
                            <th style="padding: 12px;">Email</th>
                            <th style="padding: 12px;">Secteur</th>
                        </tr>
                        <?php foreach ($companyList as $company): ?>
                        <tr style="border-bottom: 1px solid rgba(107, 44, 145, 0.1);">
                            <td style="padding: 12px;"><strong><?php echo htmlspecialchars((string)$company['nom']); ?></strong></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars((string)$company['email_contact']); ?></td>
                            <td style="padding: 12px;"><?php echo htmlspecialchars((string)$company['secteur']); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </table>
                </div>
            </section>
        </section>
    </main>
</body>
</html>