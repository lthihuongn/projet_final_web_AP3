<?php
// Configuration de la base de données (XAMPP par défaut)
$host     = 'localhost';
$dbname   = 'junia_cv';
$username = 'root';
$password = ''; // Laissez vide sous XAMPP Windows, 'root' sous MAMP (Mac)
$charset  = 'utf8mb4';

$dsn = "mysql:host=$host;dbname=$dbname;charset=$charset";

$options = [
    // Active la levée d'exceptions en cas d'erreur SQL
    PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
    // Récupère les résultats sous forme de tableau associatif par défaut
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    // Désactive la simulation des requêtes préparées (protection contre les injections SQL)
    PDO::ATTR_EMULATE_PREPARES   => false,
];

try {
    $pdo = new PDO($dsn, $username, $password, $options);
} catch (PDOException $e) {
    // En production, il faudra masquer le message d'erreur précis pour des raisons de sécurité
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}