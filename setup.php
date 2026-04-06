<?php
// setup.php est: le Script d'installation de la base de données
// Chemin du fichier de base de données
$db_file = __DIR__ . '/data/fleurs.db';
// creation de dossier s'il n'existe pas 
if (!is_dir(__DIR__ . '/data')) {
    mkdir(__DIR__ . '/data', 0755, true);
}

// Vérifier si la base existe déjà
if (file_exists($db_file)) {
    $message = "La base de données existe déjà. Aucune action n'a été effectuée.";
    $status = 'warning';
} else {
    try {
        // Créer la connexion à la base de données
        $pdo = new PDO('sqlite:' . $db_file);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        // Créer la table des produits
        $pdo->exec("
        CREATE TABLE produits (
	                id INTEGER PRIMARY KEY AUTOINCREMENT,
	                nom TEXT NOT NULL,
	                description TEXT,
	                prix REAL NOT NULL,
	                categorie TEXT NOT NULL,
	                image TEXT,
	                stock INTEGER DEFAULT 10, 
	                date_creation DATETIME DEFAULT CURRENT_TIMESTAMP
          )

        ");

        // Insérer les produits
        $produits = [
            ['Bouquet de Roses Rouges', '12 roses rouges fraîches avec feuillage', 24.90, 'bouquet', 'rose_rouge.jpeg', 15],
            ['Composition Printanière', 'Tulipes, jonquilles et pivoines', 32.50, 'composition', 'printemps.jfif', 8],
            ['Bouquet Romantique', 'Roses roses et blanches avec gypsophile', 28.00, 'bouquet', 'romantique.jfif', 12],
            ['Orchidée Blanche', 'Orchidée Phalaenopsis en pot décoratif', 19.90, 'plante', 'orchidee.jfif', 6],
            ['Bouquet Champêtre', 'Mélange de fleurs sauvages et herbes', 18.50, 'bouquet', 'champetre.jfif', 10],
            ['Bouquet Tournesols', '10 tournesols lumineux avec feuillage vert', 21.00, 'bouquet', 'Tournesols.jfif', 9],
            ['Composition Mariage', 'Roses blanches, pivoines et eucalyptus', 55.00, 'composition', 'mariage.jfif', 4],
            ['Composition Anniversaire', 'Gerberas colorés et lys parfumés en vase', 38.00, 'composition', 'anniversaire.jfif', 7],
            ['Plante Succulente', 'Assortiment de succulentes en pot céramique', 14.50, 'plante', 'succulente.jfif', 11],
            ['Lavande en Pot', 'Lavande parfumée en pot terre cuite rustique', 12.00, 'plante', 'lavande.jfif', 8],
            ['Vase en Verre', 'Vase transparent élégant pour bouquets', 9.90, 'accessoire', 'vase_verre.jfif', 20],
            ['Carte Cadeau', 'Carte cadeau à offrir à vos proches', 20.00, 'accessoire', 'Carte_Cadeau.jfif', 30],
        ];

        $stmt = $pdo->prepare("
            INSERT INTO produits (nom, description, prix, categorie, image, stock) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");

        foreach ($produits as $produit) {
            $stmt->execute($produit);
        }

        $message = " Base de données créée avec succès ! 12 produits ont été insérés.";
        $status = 'success';

    } catch (PDOException $e) {
        $message = "Erreur lors de la création de la base : " . $e->getMessage();
        $status = 'error';
    }
}

$pdo->exec("CREATE TABLE IF NOT EXISTS contacts (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    nom TEXT,
    email TEXT,
    sujet TEXT,
    message TEXT,
    date_envoi DATETIME DEFAULT CURRENT_TIMESTAMP
)");

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - FleurShop</title>
     <link rel="stylesheet" href="css/style2.css">
   
</head>
<body>

    <div class="container">
        <h1>🌸 Installation FleurShop</h1>
        
        <div class="message <?php echo $status; ?>">
            <?php echo $message; ?>
        </div>

        <?php if ($status === 'success'): ?>
            <p style="color: #666; margin: 20px 0;">
                La base de données est prête. Vous pouvez maintenant accéder au site.
            </p>
            <a href="index.php" class="btn">Aller à l'accueil</a>
        <?php elseif ($status === 'warning'): ?>
            <p style="color: #666; margin: 20px 0;">
                Si vous souhaitez réinitialiser la base, supprimez le fichier <code>data/fleurs.db</code> et relancez ce script.
            </p>
            <a href="index.php" class="btn">Aller à l'accueil</a>
        <?php else: ?>
            <p style="color: #666; margin: 20px 0;">
                Veuillez vérifier votre configuration et réessayer.
            </p>
        <?php endif; ?>
    </div>

</body>
</html>
