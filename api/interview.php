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

/**
 * Lecture des données reçues
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

// Vérification de sécurité : accès entreprise uniquement
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'company') {
    sendJsonResponse(false, 'Accès non autorisé. Vous devez être connecté en tant qu\'entreprise pour envoyer une convocation.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Méthode HTTP non autorisée. Une requête POST est attendue.');
}

$payload = getRequestPayload();
$studentId = (int) ($payload['profileId'] ?? 0);
$interviewDate = trim((string) ($payload['date'] ?? ''));
$interviewMessage = trim((string) ($payload['message'] ?? ''));

if ($studentId <= 0 || $interviewDate === '') {
    sendJsonResponse(false, 'Merci de sélectionner un profil étudiant et de renseigner une date d\'entretien.');
}

try {
    $companyId = (int) $_SESSION['user']['id'];

    // 1. Vérification de l'étudiant
    $checkStudentQuery = $pdo->prepare('SELECT id FROM etudiants WHERE id = :student_id');
    $checkStudentQuery->execute(['student_id' => $studentId]);
    
    if (!$checkStudentQuery->fetch()) {
        sendJsonResponse(false, 'Le profil étudiant sélectionné n\'existe pas ou a été supprimé.');
    }

    // 2. Insertion de la convocation
    $insertInterviewQuery = $pdo->prepare('
        INSERT INTO convocations (etudiant_id, entreprise_id, message, date_convocation, statut) 
        VALUES (:student_id, :company_id, :message, :interview_date, :status)
    ');
    
    $insertInterviewQuery->execute([
        'student_id' => $studentId,
        'company_id' => $companyId,
        'message' => $interviewMessage !== '' ? $interviewMessage : null,
        'interview_date' => $interviewDate,
        'status' => 'en attente'
    ]);

    sendJsonResponse(true, 'La convocation a été envoyée et enregistrée avec succès dans la base de données.');

} catch (Exception $exception) {
    sendJsonResponse(false, 'Erreur système lors de l\'enregistrement de la convocation : ' . $exception->getMessage());
}