<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}
include("connexion.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Type de Recherche</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        .search-type-container {
            margin: 30px auto;
            max-width: 600px;
            padding: 30px;
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .search-title {
            text-align: center;
            margin-bottom: 25px;
            color: #2c3e50;
            font-size: 1.5rem;
        }
        
        .search-options {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
            gap: 20px;
        }
        
        .search-option {
            text-align: center;
            flex: 1;
            min-width: 200px;
        }
        
        .search-btn {
            padding: 12px 25px;
            width: 100%;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: all 0.3s ease;
            box-shadow: 0 2px 5px rgba(0,0,0,0.1);
        }
        
        .search-btn:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0,0,0,0.15);
        }
        
        .search-btn.nin {
            background-color: #2ecc71;
        }
        
        .search-btn.nin:hover {
            background-color: #27ae60;
        }
        
        .search-btn.name {
            background-color: #e74c3c;
        }
        
        .search-btn.name:hover {
            background-color: #c0392b;
        }
        
        @media (max-width: 768px) {
            .search-options {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <?php include("includes/navbar.php"); ?>
    <?php include("includes/sidebare.php"); ?>
    
    <div class="search-type-container">
        <h2 class="search-title">Recherche par :</h2>
        <div class="search-options">
            <div class="search-option">
                <a href="formulaire_de_recherche.php?type=nin">
                    <button type="button" class="search-btn nin">NIN (Numéro d'Identification National)</button>
                </a>
                <p style="margin-top: 10px;">Recherche par numéro d'identification unique</p>
            </div>
            <div class="search-option">
                <a href="nom.php">
                    <button type="button" class="search-btn name">Nom et Prénom</button>
                </a>
                <p style="margin-top: 10px;">Recherche par nom complet</p>
            </div>
        </div>
    </div>
    <?php include("includes/scripts.php"); ?>
</body>
</html>