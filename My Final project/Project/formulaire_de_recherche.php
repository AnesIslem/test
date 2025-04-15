<?php
session_start();
include("connexion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/formulaire.css">
    <link rel="stylesheet" href="css/sidebare_style.css">
    <title>Recherche</title>
    <style>
       div > input{
    border-radius: 5px;
    height: 30px;
    margin-left:5px;
    width: 500px;
    margin-top: 10px;
    text-align: center;
}
        .btn{
    font-family: Georgia, 'Times New Roman', Times, serif;
    margin-left: 35%;
        }
        .btn >input {
            width: 100px;
            height: 30px;
        }
    </style>
</head>
<body>
<div style="margin-top: -15px;">
        <nav><?php include("includes/navbar.php");?></nav>
        <?php include("includes/sidebare.php"); ?>
        
        <div class="formulaire">
            <h1>Formulaire de Recherch d'un client</h1>
            <div class="box">
                <form action="list_recherch.php" method="post" >
                    <div style="text-align: center;">
                        <label><b>Search:</b></label>
                        <input type="text" name="search" required placeholder="Entrez Le NIN..">
                    </div><br>
                    <div class="btn">
                        <input type="submit" name="ajouter" value="Search" style="background-color: rgba(142, 255, 90, 0.25);">
                        <a href="type_de_recherch.php">
                            <input type="button" name="Retour" value="Retour" style="background-color: rgba(255, 36, 36, 0.25);">
                    </a>
                    </div>
                   
                </form>
            </div>
        </div>
    </div>
</body>
</html>