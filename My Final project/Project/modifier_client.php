<?php 
require("connexion.php");
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['ajouter'])) {
    $id_c=$_POST['id'];
    $nin = trim($_POST['num']);
    $nom = trim($_POST['nom']);
    $prenom = trim($_POST['prenom']);
    $date = $_POST['date'];
    $email = trim($_POST['email']);
    $tele = trim($_POST['telephone']);
    $adr = trim($_POST['adress']);
    require("verifier.php");

if(empty($errors)){
    $rqt="UPDATE `client` SET`NIN`='$nin',`Nom`='$nom',`Prenom`='$prenom',`Date de naissance`='$date',`adresse`='$adr',`email`='$email',`numero de Telephone`='$tele' WHERE `ID_C`='$id_c'";
    if(mysqli_query($conn,$rqt)){
        header("location:list_clients.php");
    }else{
        die("error");
        echo "Erreur Erreur";
        header("location:list_clients.php");
    }
}else{?>
    <script>alert("Veuillez corriger les erreurs avant de soumettre.SVP return")</script>
    <?php

}
}
?>