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

function respond(bool $success, string $message, array $extra = []): void
{
	echo json_encode(array_merge([
		'success' => $success,
		'message' => $message,
	], $extra), JSON_UNESCAPED_UNICODE);
	exit;
}

if (!isset($_SESSION['user'])) {
	respond(false, 'Accès non autorisé.');
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
	respond(false, 'Méthode non autorisée.');
}

$payload = read_input();
$profileId = (int) ($payload['profileId'] ?? 0);
$date = trim((string) ($payload['date'] ?? ''));
$message = trim((string) ($payload['message'] ?? ''));

if ($profileId <= 0 || $date === '') {
	respond(false, 'Merci de renseigner un profil et une date de convocation.');
}

$history = $_SESSION['conversations'] ?? [];
$history[] = [
	'profileId' => $profileId,
	'date' => $date,
	'message' => $message,
	'createdAt' => date('c'),
	'company' => $_SESSION['user']['name'] ?? 'Entreprise',
];

$_SESSION['conversations'] = $history;

respond(true, 'Convocation enregistrée. Un courriel pourra être déclenché côté serveur si PHPMailer est configuré.', [
	'historyCount' => count($history),
]);
