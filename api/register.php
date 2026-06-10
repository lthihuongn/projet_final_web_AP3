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

// Vérification de la méthode HTTP
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Méthode HTTP non autorisée. Une requête POST est attendue.');
}

// Extraction et nettoyage des données du formulaire d'inscription
$payload = getRequestPayload();
$firstName = trim((string) ($payload['firstname'] ?? ''));
$lastName = trim((string) ($payload['lastname'] ?? ''));
$email = trim((string) ($payload['email'] ?? ''));
$password = (string) ($payload['password'] ?? '');
$confirmPassword = (string) ($payload['confirm_password'] ?? '');

// Vérification que tous les champs obligatoires sont remplis
if ($firstName === '' || $lastName === '' || $email === '' || $password === '') {
    sendJsonResponse(false, 'Tous les champs obligatoires doivent être remplis.');
}

// Vérification de la correspondance des mots de passe
if ($password !== $confirmPassword) {
    sendJsonResponse(false, 'Les mots de passe ne correspondent pas.');
}

try {
    // 1. Vérification si l'adresse e-mail existe déjà dans la base
    $checkEmailQuery = $pdo->prepare('SELECT id FROM etudiants WHERE email = :email');
    $checkEmailQuery->execute(['email' => $email]);
    
    if ($checkEmailQuery->fetch()) {
        sendJsonResponse(false, 'Cette adresse e-mail est déjà utilisée par un autre compte.');
    }

    // 2. Sécurisation : Hachage du mot de passe (Obligation RGPD / Sécurité)
    $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
    $fullName = $firstName . ' ' . $lastName;

    // 3. Insertion du nouvel étudiant dans la table
    $insertStudentQuery = $pdo->prepare('
        INSERT INTO etudiants (nom, email, password_hash) 
        VALUES (:name, :email, :password)
    ');
    
    $insertStudentQuery->execute([
        'name' => $fullName,
        'email' => $email,
        'password' => $hashedPassword
    ]);

    // Succès : on renvoie l'utilisateur vers la page de connexion
    sendJsonResponse(true, 'Compte créé avec succès ! Tu peux maintenant te connecter.', [
        'redirect' => 'connexion.php?message=Compte+créé+avec+succès.+Connecte-toi!&type=success'
    ]);

} catch (Exception $exception) {
    // Gestion des erreurs système ou SQL
    sendJsonResponse(false, 'Erreur système lors de la création du compte : ' . $exception->getMessage());
}