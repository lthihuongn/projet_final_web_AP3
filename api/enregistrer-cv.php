<?php
// Démarrage de la session
session_start();

// Définition de l'en-tête pour le retour de données au format JSON
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

/**
 * Récupère le contenu de la requête (gère le JSON imbriqué ou le POST classique)
 */
function getRequestPayload(): array
{
    $rawInput = file_get_contents('php://input');
    if ($rawInput !== false && trim($rawInput) !== '') {
        $decodedJson = json_decode($rawInput, true);
        if (is_array($decodedJson)) {
            return $decodedJson;
        }
    }
    return $_POST;
}

// Vérification de l'authentification et du rôle de l'utilisateur
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'student') {
    sendJsonResponse(false, 'Accès non autorisé ou session expirée.');
}

// Vérification de la méthode de la requête
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Méthode non autorisée. Une requête POST est attendue.');
}

// Extraction et nettoyage des données reçues
$payload = getRequestPayload();

// Éléments de la table 'etudiants'
$name = trim((string) ($payload['name'] ?? ''));
$bio = trim((string) ($payload['bio'] ?? ''));
$domainsArray = $payload['domains'] ?? [];
$searchDomains = is_array($domainsArray) ? implode(', ', $domainsArray) : '';

// Éléments de la table 'formations'
$school = trim((string) ($payload['school'] ?? ''));
$degree = trim((string) ($payload['degree'] ?? ''));
$educationEndDate = trim((string) ($payload['education_end_date'] ?? ''));

// Éléments de la table 'experiences'
$company = trim((string) ($payload['company'] ?? ''));
$position = trim((string) ($payload['position'] ?? ''));
$experienceStartDate = trim((string) ($payload['experience_start_date'] ?? ''));
$experienceEndDate = trim((string) ($payload['experience_end_date'] ?? ''));
$experienceDescription = trim((string) ($payload['experience_description'] ?? ''));

// Éléments de la table 'competences'
$skillsString = trim((string) ($payload['skills'] ?? ''));
$skillsArray = $skillsString !== '' ? array_map('trim', explode(',', $skillsString)) : [];

try {
    // Récupération de l'ID de l'étudiant via l'email stocké en session
    $userEmail = $_SESSION['user']['email'];
    $findUserQuery = $pdo->prepare('SELECT id FROM etudiants WHERE email = :email');
    $findUserQuery->execute(['email' => $userEmail]);
    $student = $findUserQuery->fetch();

    if (!$student) {
        sendJsonResponse(false, 'Impossible de trouver votre profil étudiant.');
    }

    $studentId = (int) $student['id'];

    // Début de la transaction SQL
    $pdo->beginTransaction();

    // 1. Mise à jour de la table 'etudiants'
    $updateStudentQuery = $pdo->prepare('
        UPDATE etudiants 
        SET nom = :name, 
            biographie = :bio, 
            domaines_recherche = :domains 
        WHERE id = :id
    ');
    $updateStudentQuery->execute([
        'name' => $name !== '' ? $name : $_SESSION['user']['name'],
        'bio' => $bio,
        'domains' => $searchDomains,
        'id' => $studentId
    ]);

    // 2. Gestion de la table 'formations' (Dernière formation)
    $checkEducationQuery = $pdo->prepare('SELECT id FROM formations WHERE etudiant_id = :student_id');
    $checkEducationQuery->execute(['student_id' => $studentId]);
    
    if ($checkEducationQuery->fetch()) {
        $saveEducationQuery = $pdo->prepare('
            UPDATE formations 
            SET ecole = :school, diplome = :degree, date_fin = :end_date 
            WHERE etudiant_id = :student_id
        ');
    } else {
        $saveEducationQuery = $pdo->prepare('
            INSERT INTO formations (etudiant_id, ecole, diplome, date_fin) 
            VALUES (:student_id, :school, :degree, :end_date)
        ');
    }
    $saveEducationQuery->execute([
        'school' => $school !== '' ? $school : null,
        'degree' => $degree !== '' ? $degree : null,
        'end_date' => $educationEndDate !== '' ? $educationEndDate : null,
        'student_id' => $studentId
    ]);

    // 3. Gestion de la table 'experiences' (Dernière expérience)
    $checkExperienceQuery = $pdo->prepare('SELECT id FROM experiences WHERE etudiant_id = :student_id');
    $checkExperienceQuery->execute(['student_id' => $studentId]);

    if ($checkExperienceQuery->fetch()) {
        $saveExperienceQuery = $pdo->prepare('
            UPDATE experiences 
            SET entreprise = :company, poste = :position, date_debut = :start_date, date_fin = :end_date, description = :description 
            WHERE etudiant_id = :student_id
        ');
    } else {
        $saveExperienceQuery = $pdo->prepare('
            INSERT INTO experiences (etudiant_id, entreprise, poste, date_debut, date_fin, description) 
            VALUES (:student_id, :company, :position, :start_date, :end_date, :description)
        ');
    }
    $saveExperienceQuery->execute([
        'company' => $company !== '' ? $company : null,
        'position' => $position !== '' ? $position : null,
        'start_date' => $experienceStartDate !== '' ? $experienceStartDate : null,
        'end_date' => $experienceEndDate !== '' ? $experienceEndDate : null,
        'description' => $experienceDescription !== '' ? $experienceDescription : null,
        'student_id' => $studentId
    ]);

    // 4. Gestion de la table 'competences' (Nettoyage et réinsertion)
    $deleteSkillsQuery = $pdo->prepare('DELETE FROM competences WHERE etudiant_id = :student_id');
    $deleteSkillsQuery->execute(['student_id' => $studentId]);

    if (!empty($skillsArray)) {
        $insertSkillQuery = $pdo->prepare('INSERT INTO competences (etudiant_id, competence) VALUES (:student_id, :skill)');
        foreach ($skillsArray as $skill) {
            if ($skill !== '') {
                $insertSkillQuery->execute([
                    'student_id' => $studentId,
                    'skill' => $skill
                ]);
            }
        }
    }

    // Validation définitive de toutes les requêtes de la transaction
    $pdo->commit();

    // Actualisation du nom en session si celui-ci a changé
    if ($name !== '') {
        $_SESSION['user']['name'] = $name;
    }

    sendJsonResponse(true, 'Votre profil et votre CV ont été enregistrés avec succès !');

} catch (Exception $exception) {
    // En cas de problème, annulation complète des requêtes non validées
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    sendJsonResponse(false, 'Erreur système lors de la sauvegarde : ' . $exception->getMessage());
}