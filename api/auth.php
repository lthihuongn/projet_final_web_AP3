<?php
// Démarrage de la session au tout début
session_start();

// 1. GESTION DE LA DÉCONNEXION (Requête GET)
// On intercepte le lien api/auth.php?action=logout avant toute autre vérification
if ($_SERVER['REQUEST_METHOD'] === 'GET' && isset($_GET['action']) && $_GET['action'] === 'logout') {
    // Nettoyage complet du tableau de session
    $_SESSION = [];

    // Destruction de la session sur le serveur
    session_destroy();

    // Suppression du cookie "Se souvenir de moi" si le navigateur en possède un
    if (isset($_COOKIE['junia_user'])) {
        setcookie('junia_user', '', [
            'expires' => time() - 3600, // Date d'expiration dans le passé pour forcer sa suppression
            'path' => '/',
            'secure' => !empty($_SERVER['HTTPS']),
            'httponly' => true,
            'samesite' => 'Lax',
        ]);
    }

    // Redirection automatique vers la page de connexion avec un message de confirmation
    header('Location: ../pages/connexion.php?message=Vous+avez+été+déconnecté+avec+succès.&type=success');
    exit;
}

// 2. GESTION DE LA CONNEXION (Requête POST)
// Si on arrive ici, c'est qu'il ne s'agit pas d'une déconnexion, on configure le retour JSON
header('Content-Type: application/json; charset=UTF-8');

// Inclusion de la connexion à la base de données
require_once '../inc/db.php';

/**
 * Envoie une réponse JSON et arrête le script
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
 * Lit les données reçues, que ce soit du JSON ou du POST classique
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

// Validation de la méthode HTTP pour la connexion
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    sendJsonResponse(false, 'Méthode non autorisée.');
}

// Récupération et nettoyage des données de connexion
$payload = getRequestPayload();
$email = trim((string) ($payload['email'] ?? ''));
$password = (string) ($payload['password'] ?? '');
$remember = !empty($payload['remember']);

if ($email === '' || $password === '') {
    sendJsonResponse(false, 'Merci de renseigner l’adresse e-mail et le mot de passe.');
}

$userData = null;
$userRole = null;

// Vérification du compte Administrateur (hors BDD)
if ($email === 'admin@junia.fr') {
    if ($password === 'admin123') {
        $userData = [
            'email' => $email,
            'name' => 'Administrateur',
        ];
        $userRole = 'admin';
    }
}

// BDD : Recherche dans la table des étudiants
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

// BDD : Recherche dans la table des entreprises
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

// Enregistrement des données de l'utilisateur dans la session globale
$_SESSION['user'] = [
    'id' => $userData['id'] ?? null,
    'email' => $userData['email'],
    'name' => $userData['name'],
    'role' => $userRole,
];

// Gestion du cookie "Se souvenir de moi"
if ($remember) {
    setcookie('junia_user', $email, [
        'expires' => time() + (60 * 60 * 24 * 30),
        'path' => '/',
        'secure' => !empty($_SERVER['HTTPS']),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

// Définition de la page de redirection selon le rôle
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