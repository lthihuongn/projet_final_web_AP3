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
    $companyName = trim((string) ($_POST['company_name'] ?? ''));
    $contactEmail = trim((string) ($_POST['contact_email'] ?? ''));
    $industrySector = trim((string) ($_POST['industry_sector'] ?? ''));
    
    // Génération d'un mot de passe provisoire sécurisé de 8 caractères
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
$students = $studentsQuery->fetchAll();

$companiesQuery = $pdo->query('SELECT id, nom, email_contact, secteur FROM entreprises ORDER BY date_creation DESC');
$companies = $companiesQuery->fetchAll();

// Configuration de la racine relative et inclusion du layout commun
$dir = '../';
require_once '../inc/header.php';
?>

<div class="catalogue-shell">
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
                <h3>Comptes Étudiants (<?php echo count($students); ?>)</h3>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; text-align: left; border-collapse: collapse; margin-bottom: 32px;">
                    <tr style="border-bottom: 1px solid rgba(107, 44, 145, 0.2); background: rgba(107, 44, 145, 0.05);">
                        <th style="padding: 12px; color: var(--junia-violet);">ID</th>
                        <th style="padding: 12px; color: var(--junia-violet);">Nom</th>
                        <th style="padding: 12px; color: var(--junia-violet);">Email</th>
                        <th style="padding: 12px; color: var(--junia-violet);">Action</th>
                    </tr>
                    <?php foreach ($students as $student): ?>
                    <tr style="border-bottom: 1px solid rgba(107, 44, 145, 0.1);">
                        <td style="padding: 12px; color: #1b1330;"><?php echo $student['id']; ?></td>
                        <td style="padding: 12px; color: #1b1330;"><strong><?php echo htmlspecialchars((string)$student['nom'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                        <td style="padding: 12px; color: #665a7f;"><?php echo htmlspecialchars((string)$student['email'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="padding: 12px;"><button class="secondary-button" style="padding: 6px 12px; margin: 0; font-size: 0.85rem;">Supprimer</button></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div class="results-header">
                <h3>Entreprises Partenaires (<?php echo count($companies); ?>)</h3>
            </div>
            <div style="overflow-x: auto;">
                <table style="width: 100%; text-align: left; border-collapse: collapse;">
                    <tr style="border-bottom: 1px solid rgba(107, 44, 145, 0.2); background: rgba(107, 44, 145, 0.05);">
                        <th style="padding: 12px; color: var(--junia-violet);">Entreprise</th>
                        <th style="padding: 12px; color: var(--junia-violet);">Email</th>
                        <th style="padding: 12px; color: var(--junia-violet);">Secteur</th>
                    </tr>
                    <?php foreach ($companies as $company): ?>
                    <tr style="border-bottom: 1px solid rgba(107, 44, 145, 0.1);">
                        <td style="padding: 12px; color: #1b1330;"><strong><?php echo htmlspecialchars((string)$company['nom'], ENT_QUOTES, 'UTF-8'); ?></strong></td>
                        <td style="padding: 12px; color: #665a7f;"><?php echo htmlspecialchars((string)$company['email_contact'], ENT_QUOTES, 'UTF-8'); ?></td>
                        <td style="padding: 12px; color: #665a7f;"><?php echo htmlspecialchars((string)$company['secteur'], ENT_QUOTES, 'UTF-8'); ?></td>
                    </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </section>
    </section>
</div>

<?php
require_once '../inc/footer.php';
?>