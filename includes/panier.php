<?php
//on  démarre la session pour le panier
session_start(); // crée un lien entre le serveur et le navigateur 

// Si le panier n'existe pas encore, on crée un tableau vide
if (!isset($_SESSION['panier'])) {
    $_SESSION['panier'] = array();
}

// Si l'utilisateur a cliqué sur "Passer la commande"
if (isset($_POST['commander'])) {
    // 1. Ici on pourrait enregistrer la commande en base de données (étape suivante)
    $message_succes = "Merci pour votre commande ! Elle est en cours de préparation. 🌸";
    
    // 2. C'EST ICI QU'ON APPELLE LA FONCTION !
    vider_panier(); 
}
/* Ajouter un produit
*avant d'ajouter le produit on doit d'abord vérifier si le produit existe déjà
*cas 1: le produit existe:  
*on parcours le tableau avec un foreach, 
*si on trouve le produit(ID) on incrémente la quantité et $trouve=true et on arrete la boucle

*cas 2: le produit n'existe pas:
*après avoir parcourir tous le panier $trouve=false don le produit il existe pas 
*alors ce produit ets un nouvel article qu'on doit ajouter au panier 
*on crié un petit tableau avec ses infos et on le mets à la fin du panier avec []
*/
function ajouter_au_panier($id, $nom, $prix) {
    $trouve = false;

    // On va  chercher si le produit est déjà dans le panier 
    foreach ($_SESSION['panier'] as $cle => $produit) {
        if ($produit['id'] == $id) {
            $_SESSION['panier'][$cle]['quantite']++;
            $trouve = true;
            break;
        }
    }
    
    // Si on le trouve pas ($trouve=false), on l'ajoute à la fin
    if (!$trouve) {
        $nouvel_article = array(
            'id' => $id,
            'nom' => $nom,
            'prix' => $prix,
            'quantite' => 1
        );
        $_SESSION['panier'][] = $nouvel_article;
    }
}

/* Supprimer un produit 
* on va chercher le produit par son ID, quand on le trouve unset va le supprimer de la mémoire 
*une fois on a supprimer l'element i du tableau php va laisser un trou, pour éviter d'avoir un trou dans le tableau, j'ai utiliseé array_values()
*/
function supprimer_du_panier($id) {
    foreach ($_SESSION['panier'] as $cle => $produit) {
        if ($produit['id'] == $id) {
            unset($_SESSION['panier'][$cle]);
            break;
        }
    }
    // On réindexe le tableau pour eviter les trous 
    $_SESSION['panier'] = array_values($_SESSION['panier']);
}



/* Modifier la quantité d'un produit
* On parcourt le panier, on trouve l'ID du produit
* Si la nouvelle quantité est 0 ou moins, on supprime l'article
* Sinon, on met à jour la valeur dans la session
*/
function modifier_quantite($id, $quantite) {
    if (isset($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $cle => $produit) {
            if ($produit['id'] == $id) {
                if ($quantite <= 0) {
                    // On utilise la fonction de suppression qu'on a déjà écrite
                    supprimer_du_panier($id);
                } else {
                    $_SESSION['panier'][$cle]['quantite'] = $quantite;
                }
                break;
            }
        }
    }
}


/* Calcul du total
*on repart de 0 comme total
*pour chaque article dans le panier on multiple son prix par sa quantité et on l'ajoute au total
*/
function calculer_total() {
    $total = 0;
    foreach ($_SESSION['panier'] as $item) {
        $total = $total + ($item['prix'] * $item['quantite']);
    }
    return $total;
}



/* Compter le nombre total d'articles
* On part de 0 et on additionne la quantité de chaque ligne du panier
*/
function compter_articles() {
    $total_articles = 0;
    if (isset($_SESSION['panier'])) {
        foreach ($_SESSION['panier'] as $item) {
            $total_articles = $total_articles + $item['quantite'];
        }
    }
    return $total_articles;
}

/* Vider le panier d'un coup
* Utile après avoir validé une commande
*/
function vider_panier() {
    $_SESSION['panier'] = array();
}

?>
