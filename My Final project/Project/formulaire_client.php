<?php
include("connexion.php");
session_start();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    // Retrieve form data
    $nin = trim($_POST['num']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date = $_POST['date'];
    $email = trim($_POST['email']);
    $tele = trim($_POST['telephone']);
    $adr = trim($_POST['adress']);

    
    require("verifier.php");
    // If no errors, insert into database
    if (empty($errors)) {
        $rqt = "INSERT INTO `client` (`NIN`, `Nom`, `Prenom`, `adresse`, `Date de naissance`, `email`, `numero de Telephone`) 
                VALUES ('$nin', '$nom', '$prenom', '$adr', '$date', '$email', '$tele')";

        if (mysqli_query($conn, $rqt)) {
            header("Location: admin.php"); // Redirect to admin page
            exit();
        } else {
            $errors['general'] = "Erreur lors de l'ajout du client: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formulaire de Client</title>
    <link rel="stylesheet" href="css/formulaire.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="javascript/js.js"></script> 
</head>
<body>
    <div style="margin-top: -15px;">
       <nav> <?php include("includes/navbar.php");?></nav>
        <?php include("includes/sidebare.php"); ?>
        
        <div class="formulaire">
            <h1>Formulaire d'ajout d'un client</h1>
            <div class="box">
                <?php if (!empty($errors['general'])) echo "<p style='color:red; text-align:center;'>{$errors['general']}</p>"; ?>

                <form action="formulaire_client.php" method="post" onsubmit="validateForm(event)">
                    <div style="text-align: center;">
                        <label><b>NIN :</b></label><h7>*</h7>
                        <input type="number" name="num" required placeholder="Entrez NIN">
                    </div><br>

                    <div class="general"> 
                        <div>
                            <label><b>Nom :</b></label><h7>*</h7>
                            <input type="text" name="nom" required placeholder="Entrez Nom" onblur="validateTextInput(this, 'nomError')">
                            <span id="nomError" style="color:red;"></span>
                        </div>
                        <div style="margin-left: 80px;">
                            <label><b>Prénom :</b></label><h7>*</h7>
                            <input type="text" name="prenom" required placeholder="Entrez Prénom" onblur="validateTextInput(this, 'prenomError')">
                            <span id="prenomError" style="color:red;"></span>
                        </div>
                    </div> 
                    <br><br>

                    <div class="general">
                        <div>
                            <label><b>Date de Naissance:</b></label><h7>*</h7>
                            <input type="date" name="date" required>
                        </div>
                        <div>
                            <label><b>Adresse:</b></label><h7>*</h7>
                            <input type="text" name="adress" required placeholder="Entrez Adresse">
                        </div>
                    </div>
                    <br><br>

                    <div class="general">
                        <div>
                            <label><b>Email:</b></label>
                            <input type="email" name="email" placeholder="Entrez Email">
                        </div>
                        <div style="margin-left: 90px;">
                            <label><b>Numéro de téléphone:</b></label><h7>*</h7>
                            <input type="tel" name="telephone" required placeholder="Entrez votre Numéro">
                        </div>
                    </div>
                    <br><br>

                    <div class="btn">
                        <input type="submit" name="ajouter" value="Ajouter" style="background-color: rgba(142, 255, 90, 0.25);">
                    </div>
                    <a href="admin.php">
                        <div class="btn">
                            <input type="button" name="Retour" value="Retour" style="background-color: rgba(255, 36, 36, 0.25);">
                        </div>
                    </a>
                </form>
            </div>
        </div>
    </div>
    <?php include("includes/scripts.php"); ?>

</body>
</html>
