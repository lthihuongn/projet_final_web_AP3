<?php
// Démarrage de la session
session_start();

// Définition de l'en-tête pour le retour au format JSON
header('Content-Type: application/json; charset=UTF-8');

// Inclusion de la connexion à la base de données
require_once '../inc/db.php';

/**
 * Envoie une réponse JSON et arrête l'exécution du script
 */
function sendJsonResponse(bool $isSuccess, string $message, array $extraData = []): void
{
    echo json_encode(array_merge([
        'success' => $isSuccess,
        'message' => $message,
    ], $extraData), JSON_UNESCAPED_UNICODE);
    exit;
}

// Vérification de l'authentification de l'utilisateur
if (!isset($_SESSION['user'])) {
    sendJsonResponse(false, 'Accès non autorisé. Veuillez vous connecter.');
}

try {
    // 1. Récupération de la liste principale des étudiants avec leur dernière formation
    $profilesQuery = $pdo->query('
        SELECT 
            e.id, 
            e.nom AS name, 
            e.email, 
            e.biographie AS bio, 
            e.domaines_recherche AS domains_str,
            f.ecole AS school, 
            f.diplome AS promo
        FROM etudiants e
        LEFT JOIN formations f ON e.id = f.etudiant_id
        ORDER BY e.id DESC
    ');
    $rawProfiles = $profilesQuery->fetchAll();

    // 2. Récupération de toutes les compétences pour optimiser les performances (évite les requêtes SQL imbriquées en boucle)
    $skillsQuery = $pdo->query('SELECT etudiant_id, competence FROM competences');
    $allSkills = $skillsQuery->fetchAll();

    // Cartographie des compétences indexée par ID d'étudiant
    $skillsMap = [];
    foreach ($allSkills as $skillRow) {
        $studentId = (int) $skillRow['etudiant_id'];
        if (!isset($skillsMap[$studentId])) {
            $skillsMap[$studentId] = [];
        }
        $skillsMap[$studentId][] = $skillRow['competence'];
    }

    // 3. Formatage de chaque profil pour correspondre précisément aux structures de catalogue.js
    $formattedProfiles = [];
    foreach ($rawProfiles as $profile) {
        $studentId = (int) $profile['id'];

        // Extraction et nettoyage des domaines (ex: "Stage, Alternance" devient ["Stage", "Alternance"])
        $domainsStr = trim((string) $profile['domains_str']);
        $domainsArray = $domainsStr !== '' ? array_map('trim', explode(',', $domainsStr)) : [];
        
        // Génération du tableau minuscule requis pour les filtres de types de contrats
        $contractsArray = array_map('strtolower', $domainsArray);

        // Analyse textuelle de la promotion pour faire correspondre le code de filtre attendu (junia-a4, junia-a5, etc.)
        $promoText = strtolower((string) $profile['promo']);
        $schoolCode = 'junia-a5'; // Valeur par défaut
        if (str_contains($promoText, 'a4')) {
            $schoolCode = 'junia-a4';
        } elseif (str_contains($promoText, 'bachelor')) {
            $schoolCode = 'junia-bachelor';
        } elseif (str_contains($promoText, 'a5')) {
            $schoolCode = 'junia-a5';
        }

        // Alignement des données de la base avec la maquette dynamique
        $formattedProfiles[] = [
            'id' => $studentId,
            'name' => !empty($profile['name']) ? $profile['name'] : 'Étudiant Anonyme',
            'school' => !empty($profile['school']) ? $profile['school'] : 'JUNIA',
            'schoolCode' => $schoolCode,
            'promo' => !empty($profile['promo']) ? $profile['promo'] : 'Cursus Général',
            'promoCode' => $schoolCode,
            'city' => 'Lille', // Donnée fixe par défaut (non présente dans le schéma SQL actuel)
            'availability' => 'Disponible',
            'availabilityClass' => 'open',
            'email' => $profile['email'],
            'bio' => !empty($profile['bio']) ? $profile['bio'] : 'Aucune biographie renseignée pour le moment.',
            'domains' => $domainsArray,
            'contracts' => $contractsArray,
            'skills' => $skillsMap[$studentId] ?? [],
            'languages' => ['FR', 'EN'] // Donnée fixe par défaut
        ];
    }

    // Transmission des données structurées au catalogue front-end
    echo json_encode([
        'success' => true,
        'profils' => $formattedProfiles
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $exception) {
    sendJsonResponse(false, 'Erreur système lors du chargement des profils : ' . $exception->getMessage());
}