<?php
// ce fichier a pour but de faire un calcul et de rediriger l'utilisateur immédiatement
// On inclut les fonctions du panier
include 'includes/panier.php'; // pour lui donner accès/utilisé les fonctions ecrites dans panier.php

/*On vérifie si on a bien reçu les données du formulaire :ID, nom, prix, afin de s'assurer qu'on a tous ce qu'il faut 
*isset() est une fonction html qui verifier si la variable existe et n'est pas vide
* si toutes les valeurs existe on va pouvoire ajouter le produit au panier avec quantite=1 par defaut et c'est à ce moment là le ^produit vest mémorisé dans la mémoire de l'utilisateur 
*/

if (isset($_POST['id']) && isset($_POST['nom']) && isset($_POST['prix'])) {
    
    // On récupère les infos envoiyé par l'action POST du formulaire 
    $id = $_POST['id'];
    $nom = $_POST['nom'];
    $prix = $_POST['prix'];
    
    // On appelle la fonction pour ajouter (quantité 1 par défaut) 
    ajouter_au_panier($id, $nom, $prix, 1);
    
    // On redirige vers la page du panier pour confirmer l'ajout
    header('Location: panier.php'); // après l'ajout on va directement à la page de panier
    exit(); // On arrête le script apres l'odre de redirection 
} else {
    // Si on arrive ici sans données, on repart à l'accueil
    header('Location: index.php');
}
?>
