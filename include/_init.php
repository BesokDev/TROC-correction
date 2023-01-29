<?php

// Connexion à la BDD avecPDO
try {
    $bdd = new PDO('mysql:host=localhost:3306;dbname=troc', 'root','', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
} catch (PDOException $exception) {
    echo $exception->getMessage(); die("Veuillez créer la BDD nommée 'troc' pour continuer.");
}
// Démarrage d'une session php, nécessaire pour la connexion
session_start();

// Définition de constantes
define('BASE_URL', "http://troc/");
define('UPLOAD_URL', BASE_URL . 'uploads/');
define('UPLOAD_FOLDER', $_SERVER['DOCUMENT_ROOT'] . '/uploads/');

require_once ('_fonctions.php');