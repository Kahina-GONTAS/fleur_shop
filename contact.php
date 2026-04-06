<?php
/* Page de contact - FleurShop: Ici on gère l'envoi du formulaire et l'affichage
*partie: traitement de formulaire: 
    *1- verification si le formulaire a été envoyé =POST
    *2- je récupère les champs (isset()) en enlevant les espaces inutiles(trim())
*partie de la validation : vérifier si les champs obligatoires sont remplis et si l'email a un format valide(filter_var)
*si tout est bon, il ecrit un message de confirmation, sinon il stocke l'erreur dans $message_error
*/

/*
** à propos du formulaire:
* C'est un formulaire classique en POST qui envoie les données au même fichier PHP.
* J'ai bien fait attention à donner des attributs name à chaque champ pour pouvoir les 
* récupérer proprement en PHP, et j'ai ajouté une petite logique pour que les champs ne
 * se vident pas en cas d'erreur de saisie

*/

include 'includes/db.php';
include 'includes/panier.php';

$message_success = "";
$message_error = "";

// On vérifie si le formulaire a été posté (le client a cliqué sur le bouton Envoyer)
if (isset($_POST['nom'])) {
    
    // Récupération des données du formulaire + netoyage des espaces accidentels
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $telephone = trim($_POST['telephone']);
    $sujet = trim($_POST['sujet']);
    $message = trim($_POST['message']);

    // Validation des champs obligatoires
    if (empty($nom) || empty($email) || empty($message)) {
        $message_error = "Oups ! Il manque des informations obligatoires.";
    } 
    // Vérification du format de l'email s'il est valide 
    elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $message_error = "L'adresse email n'est pas valide.";
    }  // si aucune erreur n'a été trouvée, Succès
   


        else {
        // --- AJOUT : ENREGISTREMENT DANS LA BASE DE DONNÉES ---
        try {
            // On prépare la requête (sécurisée contre les injections SQL)
            $stmt = $pdo->prepare("INSERT INTO contacts (nom, email, sujet, message) VALUES (?, ?, ?, ?)");
            
            // On exécute avec les données du formulaire
            $stmt->execute([$nom, $email, $sujet, $message]);

            $message_success = "Super ! Votre message a bien été envoyé et enregistré en base de données. 🌸";
            
            // On vide les variables pour vider le formulaire
            $nom = $email = $sujet = $message = "";
            
        } catch (PDOException $e) {
            $message_error = "Désolé, une erreur technique est survenue : " . $e->getMessage();
        }
    }







        
        // On vide les variables pour vider le formulaire après succès 
        $nom = $email = $telephone = $sujet = $message = "";
    
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Contact - FleurShop</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>

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
                🛒 Panier (<?php echo compter_articles(); ?>)
            </a>
        </div>
    </header>

    <main style="padding: 20px; max-width: 800px; margin: auto;">
        <h2>Contactez-nous</h2>
        <p>Une question ? Remplissez le formulaire ci-dessous.</p>

        <!-- Affichage des messages d'erreur ou de succès -->
        <!--  affichage du rectagle rouge que s'il y'a un message d'erreur, sinon la page reste propre -->
        <?php if ($message_error != ""): ?>
            <p style="color: red; background: #efcaca; padding: 10px; border-radius: 5px;">
                <?php echo $message_error; ?>
            </p>
        <?php endif; ?>

        <?php if ($message_success != ""): ?>
            <p style="color: green; background: #d4fcd4; padding: 10px; border-radius: 5px;">
                <?php echo $message_success; ?>
            </p>
        <?php endif; ?>

        <form method="POST" action="contact.php" style="display: flex; flex-direction: column; gap: 15px;">
            <div>
                <label>Nom :</label>  

                <input type="text" name="nom" value="<?php echo isset($nom) ? $nom : ''; ?>" style="width: 100%; padding: 8px;">
            </div>

            <div>
                <label>Email :</label>  

                <input type="email" name="email" value="<?php echo isset($email) ? $email : ''; ?>" style="width: 100%; padding: 8px;">
            </div>

            <div>
                <label>Sujet :</label>  
             <!--  menu déroulant-->
                <select name="sujet" style="width: 100%; padding: 8px;">
                    <option value="infos">Demande d'informations</option>
                    <option value="sav">Service après-vente</option>
                    <option value="autre">Autre</option>
                </select>
            </div>

            <div>
                <label>Message :</label>  

                <textarea name="message" rows="5" style="width: 100%; padding: 8px;"><?php echo isset($message) ? $message : ''; ?></textarea>
            </div>

            <button type="submit" style="background: #ee4431; color: white; padding: 10px; border: none; cursor: pointer;">
                Envoyer le message
            </button>
        </form>
    </main>

    <footer style="margin-top: 50px; text-align: center; border-top: 1px solid #ccc; padding: 20px;">
        <p>&copy; 2026 FleurShop - UGA-Gontask</p>
    </footer>

</body>
</html>
