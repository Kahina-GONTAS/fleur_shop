<?php
// catalogue.php - Page de la boutique

include 'includes/db.php';
include 'includes/panier.php';

// 1- On regarde quelle catégorie l'utilisateur a choisie dans l'URL
if (isset($_GET['categorie'])) {
    $categorie = $_GET['categorie'];
} else {
    // Par défaut, on affiche tout
    $categorie = 'tous';
}

// 2- On prépare la requête pour récupérer les produits
if ($categorie == 'tous') {
    // On récupère tous  les produits par select *
    $requete = $pdo->query("SELECT * FROM produits ORDER BY nom");
} else { 
    // On filtre par la catégorie choisie
    $requete = $pdo->prepare("SELECT * FROM produits WHERE categorie = ?");
    $requete->execute(array($categorie));
}

// On transforme le résultat de la base en un tableau PHP utilisable
// FETCH_ASSOC permet d'utiliser les noms des colonnes 
$produits = $requete->fetchAll(PDO::FETCH_ASSOC);

// 3- Puis on récupère la liste des catégories existantes pour le menu de filtrage
// pdo est l'objet de connexion à la base de donnée 
$req_cat = $pdo->query("SELECT DISTINCT categorie FROM produits");
// on recupère toutes les lignes de resultat par un tablau associatif
$categories = $req_cat->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Catalogue - FleurShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <header>
        <h1>🌸 FleurShop</h1>
        <nav>
            <a href="index.php">Accueil</a> | 
            <a href="catalogue.php">Catalogue</a> | 
            <a href="contact.php">Contact</a>
        </nav>
        <div style="float: right;">
            <a href="panier.php">🛒 Panier (<?php echo compter_articles(); ?>)</a>
        </div>
    </header>

    <main style="padding: 20px;">
        <h2>Nos Fleurs</h2>

        <!-- Menu pour filtrer par catégorie -->
        <div style="margin-bottom: 20px; background: #f9f9f9; padding: 10px;">
            <strong>Filtrer par :</strong>
            <a href="catalogue.php?categorie=tous">Tous les produits</a>
            <!-- on parcourt le tableau $categories, on stocke chaque element dans la variable $c -->
            <?php foreach ($categories as $c): ?>
                | <a href="catalogue.php?categorie=<?php echo $c['categorie']; ?>">
            <!-- on recupère le nom de la catégorie en mettant sa 1 ere  lettre en Majuscule grace à ucfirst()  -->
                    <?php echo ucfirst($c['categorie']); ?>
                </a>
            <?php endforeach; ?>
        </div>


    <!-- Affichage des produits sous forme de grille -->
    <div style="display: flex; flex-wrap: wrap; gap: 20px;">
       <!-- On parcourt tous les produits en mettant chaque produit dans la variable $p -->
        <?php foreach ($produits as $p): ?>
           <div style="border: 1px solid #ddd; padding: 15px; width: 220px; border-radius: 10px; background: white; text-align: center; display: flex; flex-direction: column; justify-content: space-between;">
    
    <!-- L'image avec une taille fixe -->
    <img src="images/<?php echo $p['image']; ?>" alt="<?php echo $p['nom']; ?>" style="width: 100%; height: 150px; object-fit: cover; border-radius: 5px; margin-bottom: 10px;">
    
    <!-- Le titre avec une hauteur fixe (au cas où il ferait 2 lignes) -->
    <h3 style="color: #ee4431; margin: 10px 0; height: 50px; overflow: hidden; display: flex; align-items: center; justify-content: center;">
        <?php echo $p['nom']; ?>
    </h3>
    
    <!-- LA DESCRIPTION : On lui donne une hauteur fixe de 60px -->
    <p style="font-size: 0.9em; color: #5c5c5c; height: 60px; overflow: hidden; margin-bottom: 10px;">
        <?php echo $p['description']; ?>
    </p>
    
    <!-- Le prix -->
    <p style="margin-bottom: 15px;"><strong>Prix : <?php echo $p['prix']; ?> €</strong></p>
    
    <!-- Le bouton qui sera toujours tout en bas grâce au flex-direction: column -->
    <form method="POST" action="ajouter_panier.php" style="margin-top: auto;">
        <input type="hidden" name="id" value="<?php echo $p['id']; ?>">
        <input type="hidden" name="nom" value="<?php echo $p['nom']; ?>">
        <input type="hidden" name="prix" value="<?php echo $p['prix']; ?>">
        <button type="submit" style="background: #ee4431; color: white; border: none; padding: 10px; cursor: pointer; width: 100%; border-radius: 5px; font-weight: bold;">
            Ajouter au panier
        </button>
    </form>
</div>

            <?php endforeach; ?>
        </div>

    </main>

    <footer style="margin-top: 50px; text-align: center; font-size: 0.8em; color: #888;">
        <p>&copy; 2026 FleurShop - Boutique de fleurs - gontask-UGA</p>
    </footer>

</body>
</html>
