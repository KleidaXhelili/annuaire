<?php 

// Connexion à la BDD
$pdo = new PDO(
    'mysql:host=localhost;dbname=repertoire;chartset=utf8',
    'root',
    '',
    array(
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    )
);
 
// Inclusion du fichier de fonctions
require_once('functions.php');