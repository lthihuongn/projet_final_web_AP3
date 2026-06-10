<?php
session_start();

header('Content-Type: application/json; charset=UTF-8');

if (!isset($_SESSION['user'])) {
	echo json_encode([
		'success' => false,
		'message' => 'Accès non autorisé.',
	], JSON_UNESCAPED_UNICODE);
	exit;
}

$profils = [
	[
		'id' => 1,
		'name' => 'Léa Martin',
		'school' => 'JUNIA',
		'schoolCode' => 'junia-a5',
		'promo' => 'A5 - Informatique',
		'promoCode' => 'junia-a5',
		'city' => 'Lille',
		'availability' => 'Disponible',
		'availabilityClass' => 'open',
		'email' => 'lea.martin@junia.com',
		'bio' => 'Étudiante orientée data et développement web, à la recherche d’une alternance avec un fort volet technique.',
		'domains' => ['Alternance', 'CDI'],
		'contracts' => ['alternance', 'cdi'],
		'skills' => ['PHP', 'MySQL', 'JavaScript', 'Data'],
		'languages' => ['FR', 'EN'],
	],
	[
		'id' => 2,
		'name' => 'Nassim Benali',
		'school' => 'JUNIA',
		'schoolCode' => 'junia-a4',
		'promo' => 'A4 - Réseaux & cybersécurité',
		'promoCode' => 'junia-a4',
		'city' => 'Lille',
		'availability' => 'Disponible',
		'availabilityClass' => 'open',
		'email' => 'nassim.benali@junia.com',
		'bio' => 'Profil orienté cybersécurité, administration système et supervision réseau, avec une première expérience en entreprise.',
		'domains' => ['Stage', 'Alternance'],
		'contracts' => ['stage', 'alternance'],
		'skills' => ['Linux', 'Sécurité', 'Python', 'Réseau'],
		'languages' => ['FR', 'EN'],
	],
	[
		'id' => 3,
		'name' => 'Camille Durand',
		'school' => 'JUNIA',
		'schoolCode' => 'junia-bachelor',
		'promo' => 'Bachelor - Design numérique',
		'promoCode' => 'junia-bachelor',
		'city' => 'Bordeaux',
		'availability' => 'Mobilité internationale',
		'availabilityClass' => 'travel',
		'email' => 'camille.durand@junia.com',
		'bio' => 'Designer produit avec un goût prononcé pour l’UX, la recherche utilisateur et les interfaces mobiles.',
		'domains' => ['Mobilité', 'Stage'],
		'contracts' => ['stage', 'mobilite'],
		'skills' => ['UX', 'Figma', 'HTML', 'CSS'],
		'languages' => ['FR', 'EN', 'ES'],
	],
	[
		'id' => 4,
		'name' => 'Yanis Morel',
		'school' => 'JUNIA',
		'schoolCode' => 'junia-a5',
		'promo' => 'A5 - Génie industriel',
		'promoCode' => 'junia-a5',
		'city' => 'Paris',
		'availability' => 'Disponible',
		'availabilityClass' => 'open',
		'email' => 'yanis.morel@junia.com',
		'bio' => 'Étudiant intéressé par les missions d’amélioration continue, supply chain et management de production.',
		'domains' => ['CDI', 'Alternance'],
		'contracts' => ['alternance', 'cdi'],
		'skills' => ['Lean', 'Excel', 'Gestion de projet', 'Power BI'],
		'languages' => ['FR', 'EN'],
	],
];

echo json_encode([
	'success' => true,
	'profils' => $profils,
], JSON_UNESCAPED_UNICODE);
