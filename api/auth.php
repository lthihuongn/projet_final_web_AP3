<?php
session_start();

header('Content-Type: application/json; charset=UTF-8');

function read_input(): array
{
	$raw = file_get_contents('php://input');
	if ($raw !== false && trim($raw) !== '') {
		$decoded = json_decode($raw, true);
		if (is_array($decoded)) {
			return $decoded;
		}
	}

	return $_POST;
}

function send_response(bool $success, string $message, array $extra = []): void
{
	echo json_encode(array_merge([
		'success' => $success,
		'message' => $message,
	], $extra), JSON_UNESCAPED_UNICODE);
	exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	send_response(false, 'Méthode non autorisée.');
}

$payload = read_input();
$email = trim((string) ($payload['email'] ?? ''));
$password = (string) ($payload['password'] ?? '');
$remember = !empty($payload['remember']);

if ($email === '' || $password === '') {
	send_response(false, 'Merci de renseigner l’adresse e-mail et le mot de passe.');
}

$accounts = [
	'admin@junia.fr' => [
		'password' => 'admin123',
		'name' => 'Administrateur',
		'role' => 'admin',
	],
	'etudiant@junia.fr' => [
		'password' => 'etudiant123',
		'name' => 'Étudiant',
		'role' => 'student',
	],
];

if (!isset($accounts[$email]) || $accounts[$email]['password'] !== $password) {
	send_response(false, 'Identifiants invalides.');
}

$_SESSION['user'] = [
	'email' => $email,
	'name' => $accounts[$email]['name'],
	'role' => $accounts[$email]['role'],
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

send_response(true, 'Connexion réussie.', [
	'redirect' => '../pages/profil.php',
	'user' => [
		'email' => $email,
		'name' => $accounts[$email]['name'],
		'role' => $accounts[$email]['role'],
	],
]);
