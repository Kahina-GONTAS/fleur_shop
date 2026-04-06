<?php
// index.php - Page d'accueil


include 'includes/db.php'; //connexion à la base de donnée $pdo
include 'includes/panier.php';

// Récupérer les produits vedettes (j'ai choisit les 4 premiers pour ne pas surcharger la page d'accueil)  
$stmt = $pdo->query("SELECT * FROM produits LIMIT 4");
// et transformer le resultat en un tableau PHP
$produits_vedettes = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FleurShop - Boutique de Fleurs</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

    <!-- Header -->
    <header>
        <h1>🌸 FleurShop</h1>
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="catalogue.php">Catalogue</a></li>
                <li><a href="contact.php">Contact</a></li>
            </ul>
        </nav>
        <div class="header-right">
            <a href="panier.php" class="cart-link">
                🛒 Panier <span class="cart-count"><?php echo compter_articles(); ?></span>
            </a>
        </div>
    </header>
    <section class="hero">
        <h2>Bienvenue chez FleurShop</h2>
        <p>Des fleurs fraîches livrées chez vous pour toutes vos occasions</p>
        <a href="catalogue.php" class="btn-primary">Voir le catalogue</a>
    </section>

    <!-- Produits vedettes -->
    <h2 class="section-title">Nos produits populaires</h2>
    <p class="section-subtitle">Découvrez nos meilleures ventes</p>

    <div class="products-grid">
        <?php foreach ($produits_vedettes as $produit): ?>
            <div class="product-card">
               <img src="images/<?php echo $produit['image']; ?>" alt="<?php echo $produit['nom']; ?>" style="width: 100%; height: 180px; object-fit: cover; border-radius: 8px;">

                <div class="product-info">
                    <h3><?php echo htmlspecialchars($produit['nom']); ?></h3>
                    <p><?php echo htmlspecialchars($produit['description']); ?></p>
                    <!-- affichage de prix -->
                    <div class="product-price"><?php echo number_format($produit['prix'], 2, ',', ' '); ?> €</div>
                    <form method="POST" action="ajouter_panier.php" style="margin-top: auto;">
                        <input type="hidden" name="id" value="<?php echo $produit['id']; ?>">
                        <input type="hidden" name="nom" value="<?php echo htmlspecialchars($produit['nom']); ?>">
                        <input type="hidden" name="prix" value="<?php echo $produit['prix']; ?>">
                        <button type="submit" class="add-to-cart">Ajouter au panier</button>
                    </form>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2026 FleurShop - Boutique de fleurs en ligne</p>
        <p>Contact : contact@fleurshop.fr | Tél : 07 75 85 85 68</p>
    </footer>

</body>
</html>
