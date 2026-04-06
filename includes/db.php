<?php
// fichier de Connexion à la base de données SQLite
// Pour ce projet J'ai choisi PDO car c'est le standard actuel en PHP 
// Les requêtes préparées protègent contre les injections SQL, et l'interface est uniforme quelle que soit la base de données utilisée.



// récuperer le chemin du fichier de base de données, et la fonction "__DIR__" récupoère le dossier courant de fichier
$db_file = __DIR__ . '/../data/fleurs.db';

// Je pourrais rajouter une  veérification si le dossier data existe, sinon le créer mais dans ce cas je l'ai bien crée 

// Créer le dossier data s'il n'existe pas 
if (!is_dir(__DIR__ . '/../data')) {
    mkdir(__DIR__ . '/../data', 0755, true);
}


//connexion à la base de données:

// Vérifier si la base de données existe
if (!file_exists($db_file)) {
    die('Erreur : La base de données n\'existe pas'); // on arrête l'execution avec die si le fichier n'existe pas afin d'empêcher le programme de continuer

}
/*
1- Démarrer un bloc qui va essayer d'exécuter du code, s'il y'a une erreur on pourra la capturer
2-Etablir la connexion avec le fichier fleurs.db

*/

try {
    $pdo = new PDO('sqlite:' . $db_file);// creation de l'objet de connexio à la base de donnée 
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) { // le capteur d'erreur 
    die('Erreur de connexion à la base de données : ' . $e->getMessage()); // recuperer le message d'erreur avec
}

?>
