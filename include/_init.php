<?php

// Connexion à la BDD avecPDO
$bdd = new PDO('mysql:host=localhost:3306;dbname=troc', 'root','', [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);

// Démarrage d'une session php, nécessaire pour la connexion
session_start();

// Définition de constantes
define('BASE_URL', $_SERVER['DOCUMENT_ROOT']);
define('UPLOAD_FOLDER', 'uploads');

require_once ('_fonctions.php');