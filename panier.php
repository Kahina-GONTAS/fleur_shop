<?php
// panier.php - Page du panier

include 'includes/db.php';
include 'includes/panier.php';

// Traiter les modifications du panier
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action'])) {
        if ($_POST['action'] === 'modifier' && isset($_POST['id']) && isset($_POST['quantite'])) {
            modifier_quantite($_POST['id'], (int)$_POST['quantite']);
        } elseif ($_POST['action'] === 'supprimer' && isset($_POST['id'])) {
            supprimer_du_panier($_POST['id']);
        } elseif ($_POST['action'] === 'commander') {
            // Simuler une commande
            vider_panier();
            $message_success = "Commande passée avec succès ! Merci de votre achat.";
        }
    }
}

$total = calculer_total();
$message_success = isset($message_success) ? $message_success : '';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier - FleurShop</title>
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

    <!-- Message de succès -->
    <?php if ($message_success): ?>
        <div class="message success">
            <?php echo htmlspecialchars($message_success); ?>
        </div>
    <?php endif; ?>

    <!-- Panier -->
    <div class="cart-container">
        <h2>Mon Panier</h2>

        <?php if (count($_SESSION['panier']) > 0): ?>
            <div class="cart-items-list">
                <?php foreach ($_SESSION['panier'] as $item): ?>
                    <div class="cart-item">
                        <div class="cart-item-info">
                            <div class="cart-item-name"><?php echo htmlspecialchars($item['nom']); ?></div>
                            <div class="cart-item-price"><?php echo number_format($item['prix'], 2, ',', ' '); ?> €</div>
                        </div>
                        <div class="cart-item-qty">
                            <form method="POST" style="display: flex; gap: 10px; align-items: center;">
                                <input type="hidden" name="action" value="modifier">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <input type="number" name="quantite" value="<?php echo $item['quantite']; ?>" min="1" class="qty-input">
                                <button type="submit" class="btn-primary" style="padding: 5px 10px; font-size: 0.9rem;">OK</button>
                            </form>
                            <form method="POST" style="display: inline;">
                                <input type="hidden" name="action" value="supprimer">
                                <input type="hidden" name="id" value="<?php echo $item['id']; ?>">
                                <button type="submit" class="btn-remove">Supprimer</button>
                            </form>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="cart-total">
                Total : <?php echo number_format($total, 2, ',', ' '); ?> €
            </div>

            <form method="POST">
                <input type="hidden" name="action" value="commander">
                <button type="submit" class="checkout-btn">Passer la commande</button>
            </form>

        <?php else: ?>
            <div class="cart-empty">
                Votre panier est vide. <a href="catalogue.php">Continuer vos achats</a>
            </div>
        <?php endif; ?>
    </div>

    <!-- Footer -->
    <footer>
        <p>&copy; 2024 FleurShop - Boutique de fleurs en ligne</p>
        <p>Contact : contact@fleurshop.fr | Tél : 01 23 45 67 89</p>
    </footer>

</body>
</html>
