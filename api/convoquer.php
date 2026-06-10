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

/**
 * Lit les données reçues (format JSON ou POST classique)
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

// Vérification de l'authentification et du rôle : seul un profil "entreprise" peut convoquer
if (!isset($_SESSION['user']) || $_SESSION['user']['role'] !== 'company') {
    sendJsonResponse(false, 'Accès non autorisé. Vous devez être connecté en tant qu\'entreprise pour envoyer une convocation.');
}

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Méthode HTTP non autorisée. Une requête POST est attendue.');
}

// Extraction des données envoyées par l'interface (catalogue.js)
$payload = getRequestPayload();
$studentId = (int) ($payload['profileId'] ?? 0);
$interviewDate = trim((string) ($payload['date'] ?? ''));
$interviewMessage = trim((string) ($payload['message'] ?? ''));

// Vérification des champs obligatoires
if ($studentId <= 0 || $interviewDate === '') {
    sendJsonResponse(false, 'Merci de sélectionner un profil étudiant et de renseigner une date d\'entretien.');
}

try {
    // Récupération de l'ID de l'entreprise depuis la session active
    $companyId = (int) $_SESSION['user']['id'];

    // 1. Vérification de l'existence de l'étudiant dans la base de données
    $checkStudentQuery = $pdo->prepare('SELECT id FROM etudiants WHERE id = :student_id');
    $checkStudentQuery->execute(['student_id' => $studentId]);
    
    if (!$checkStudentQuery->fetch()) {
        sendJsonResponse(false, 'Le profil étudiant sélectionné n\'existe pas ou a été supprimé.');
    }

    // 2. Préparation de la requête d'insertion dans la table 'convocations'
    $insertInterviewQuery = $pdo->prepare('
        INSERT INTO convocations (etudiant_id, entreprise_id, message, date_convocation, statut) 
        VALUES (:student_id, :company_id, :message, :interview_date, :status)
    ');
    
    // 3. Exécution de la requête avec liaison sécurisée des paramètres
    $insertInterviewQuery->execute([
        'student_id' => $studentId,
        'company_id' => $companyId,
        'message' => $interviewMessage !== '' ? $interviewMessage : null,
        'interview_date' => $interviewDate,
        'status' => 'en attente'
    ]);

    // Envoi de la réponse de succès au front-end
    sendJsonResponse(true, 'La convocation a été envoyée et enregistrée avec succès dans la base de données.');

} catch (Exception $exception) {
    // Gestion des erreurs SQL
    sendJsonResponse(false, 'Erreur système lors de l\'enregistrement de la convocation : ' . $exception->getMessage());
}