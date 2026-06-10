<?php
// Démarrage de la session au tout début du script
session_start();

// 1. GESTION DE LA DÉCONNEXION (Requête GET)
// On intercepte le paramètre action=logout avant de valider la méthode POST
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Suppression de toutes les variables de session
    $_SESSION = [];

    // Destruction définitive de la session sur le serveur
    session_destroy();

    // Suppression du cookie de connexion automatique si existant
    if (isset($_COOKIE['junia_user'])) {
        setcookie('junia_user', '', [
            'expires' => time() - 3600,
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    // Redirection vers la page de connexion avec un message de confirmation
    header('Location: ../pages/connexion.php?message=Vous+avez+été+déconnecté+avec+succès.&type=success');
    exit;
}

// 2. GESTION DE LA CONNEXION (Requête POST)
header('Content-Type: application/json; charset=UTF-8');

// Inclusion de la connexion à la base de données
require_once '../inc/db.php';

/**
 * Envoie une réponse JSON formatée et arrête l'exécution
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
 * Récupère les données brutes entrantes (JSON ou $_POST)
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

// Sécurité : Seules les requêtes de connexion utilisent POST
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Méthode non autorisée.');
}

$payload = getRequestPayload();
$email = trim((string) ($payload['email'] ?? ''));
$password = (string) ($payload['password'] ?? '');
$remember = !empty($payload['remember']);

if ($email === '' || $password === '') {
    sendJsonResponse(false, 'Merci de renseigner l’adresse e-mail et le mot de passe.');
}

$userData = null;
$userRole = null;

// Vérification du compte Administrateur global
if ($email === 'admin@junia.fr') {
    if ($password === 'admin123') {
        $userData = [
            'email' => $email,
            'name' => 'Administrateur',
        ];
        $userRole = 'admin';
    }
}

// Recherche dans la table des étudiants
if ($userData === null) {
    $studentQuery = $pdo->prepare('SELECT id, nom, email, password_hash FROM etudiants WHERE email = :email');
    $studentQuery->execute(['email' => $email]);
    $student = $studentQuery->fetch();

    if ($student) {
        if (password_verify($password, $student['password_hash']) || $password === $student['password_hash']) {
            $userData = [
                'id' => $student['id'],
                'email' => $student['email'],
                'name' => $student['nom']
            ];
            $userRole = 'student';
        }
    }
}

// Recherche dans la table des entreprises
if ($userData === null) {
    $companyQuery = $pdo->prepare('SELECT id, nom, email_contact, password_hash FROM entreprises WHERE email_contact = :email');
    $companyQuery->execute(['email' => $email]);
    $company = $companyQuery->fetch();

    if ($company) {
        if (password_verify($password, $company['password_hash']) || $password === $company['password_hash']) {
            $userData = [
                'id' => $company['id'],
                'email' => $company['email_contact'],
                'name' => $company['nom']
            ];
            $userRole = 'company';
        }
    }
}

if ($userData === null) {
    sendJsonResponse(false, 'Identifiants invalides.');
}

// Stockage des informations d'authentification en session
$_SESSION['user'] = [
    'id' => $userData['id'] ?? null,
    'email' => $userData['email'],
    'name' => $userData['name'],
    'role' => $userRole,
];

if ($remember) {
    setcookie('junia_user', $email, [
        'expires' => time() + (60 * 60 * 24 * 30),
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// Redirection dynamique de secours selon le privilège
$redirectUrl = '../index.php';
if ($userRole === 'student') {
    $redirectUrl = 'profil.php';
} elseif ($userRole === 'company') {
    $redirectUrl = 'catalogue.php';
}

sendJsonResponse(true, 'Connexion réussie.', [
    'redirect' => $redirectUrl,
    'user' => $_SESSION['user']
]);