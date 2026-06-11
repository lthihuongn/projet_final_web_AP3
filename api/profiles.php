<?php
// Démarrage de la session
session_start();

// Définition de l'en-tête JSON
header('Content-Type: application/json; charset=UTF-8');

// Inclusion de la base de données
require_once '../inc/db.php';

/**
 * Envoie une réponse JSON
 */
function sendJsonResponse(bool $isSuccess, string $message, array $extraData = []): void
{
    echo json_encode(array_merge([
        'success' => $isSuccess,
        'message' => $message,
    ], $extraData), JSON_UNESCAPED_UNICODE);
    exit;
}

if (!isset($_SESSION['user'])) {
    sendJsonResponse(false, 'Accès non autorisé. Veuillez vous connecter.');
}

try {
    // 1. Récupération des étudiants
    $profilesQuery = $pdo->query('
        SELECT 
            e.id, e.nom AS name, e.email, e.biographie AS bio, e.domaines_recherche AS domains_str,
            f.ecole AS school, f.diplome AS promo
        FROM etudiants e
        LEFT JOIN formations f ON e.id = f.etudiant_id
        ORDER BY e.id DESC
    ');
    $rawProfiles = $profilesQuery->fetchAll();

    // 2. Récupération des compétences pour optimisation
    $skillsQuery = $pdo->query('SELECT etudiant_id, competence FROM competences');
    $allSkills = $skillsQuery->fetchAll();

    $skillsMap = [];
    foreach ($allSkills as $skillRow) {
        $studentId = (int) $skillRow['etudiant_id'];
        if (!isset($skillsMap[$studentId])) {
            $skillsMap[$studentId] = [];
        }
        $skillsMap[$studentId][] = $skillRow['competence'];
    }

    // 3. Formatage pour le front-end
    $formattedProfiles = [];
    foreach ($rawProfiles as $profile) {
        $studentId = (int) $profile['id'];

        $domainsStr = trim((string) $profile['domains_str']);
        $domainsArray = $domainsStr !== '' ? array_map('trim', explode(',', $domainsStr)) : [];
        $contractsArray = array_map('strtolower', $domainsArray);

        $promoText = strtolower((string) $profile['promo']);
        $schoolCode = 'junia-a5'; 
        if (str_contains($promoText, 'a4')) {
            $schoolCode = 'junia-a4';
        } elseif (str_contains($promoText, 'bachelor')) {
            $schoolCode = 'junia-bachelor';
        }

        $formattedProfiles[] = [
            'id' => $studentId,
            'name' => !empty($profile['name']) ? $profile['name'] : 'Étudiant Anonyme',
            'school' => !empty($profile['school']) ? $profile['school'] : 'JUNIA',
            'schoolCode' => $schoolCode,
            'promo' => !empty($profile['promo']) ? $profile['promo'] : 'Cursus Général',
            'promoCode' => $schoolCode,
            'city' => 'Lille', 
            'availability' => 'Disponible',
            'availabilityClass' => 'open',
            'email' => $profile['email'],
            'bio' => !empty($profile['bio']) ? $profile['bio'] : 'Aucune biographie renseignée pour le moment.',
            'domains' => $domainsArray,
            'contracts' => $contractsArray,
            'skills' => $skillsMap[$studentId] ?? [],
            'languages' => ['FR', 'EN']
        ];
    }

    echo json_encode([
        'success' => true,
        'profils' => $formattedProfiles
    ], JSON_UNESCAPED_UNICODE);

} catch (Exception $exception) {
    sendJsonResponse(false, 'Erreur système lors du chargement des profils : ' . $exception->getMessage());
}