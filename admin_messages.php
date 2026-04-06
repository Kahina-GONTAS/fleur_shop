<?php
include 'includes/db.php'; // On se connecte à la base
include 'includes/panier.php';

// On récupère tous les messages du plus récent au plus ancien
$requete = $pdo->query("SELECT * FROM contacts ORDER BY date_envoi DESC");
$messages = $requete->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Messages Clients</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <header>
        <h1>Administration des Messages</h1>
        <nav><a href="index.php">Retour au site</a></nav>
    </header>

    <main style="padding: 20px;">
        <h2>Messages reçus (<?php echo count($messages); ?>)</h2>
        
        <?php if (count($messages) == 0): ?>
            <p>Aucun message pour le moment. 🌸</p>
        <?php else: ?>
            <table border="1" style="width: 100%; border-collapse: collapse; margin-top: 20px;">
                <tr style="background: #ee4431; color: white;">
                    <th style="padding: 10px;">Date</th>
                    <th>Nom</th>
                    <th>Email</th>
                    <th>Sujet</th>
                    <th>Message</th>
                </tr>
                <?php foreach ($messages as $m): ?>
                    <tr>
                        <td style="padding: 10px;"><?php echo $m['date_envoi']; ?></td>
                        <td><?php echo htmlspecialchars($m['nom']); ?></td>
                        <td><?php echo htmlspecialchars($m['email']); ?></td>
                        <td><?php echo htmlspecialchars($m['sujet']); ?></td>
                        <td style="font-style: italic;"><?php echo nl2br(htmlspecialchars($m['message'])); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        <?php endif; ?>
    </main>
</body>
</html>
