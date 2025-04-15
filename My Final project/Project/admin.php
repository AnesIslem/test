<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: cnxadmin.php");
    exit();
}

include("connexion.php");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <style>
        /* Main Layout */
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .admin-container {
            display: flex;
            min-height: 100vh;
            padding-top: 60px; /* Navbar height */
        }
        
        .main-content {
            flex: 1;
            padding: 20px;
            margin-left: 250px; /* Sidebar width */
            transition: margin-left 0.3s;
        }
        
        /* When sidebar is collapsed */
        .admin-sidebar.collapsed {
            transform: translateX(-250px);
        }
        
        .admin-sidebar.collapsed + .main-content {
            margin-left: 0;
        }
        
        @media (max-width: 768px) {
            .admin-sidebar {
                transform: translateX(-250px);
            }
            
            .admin-sidebar.visible {
                transform: translateX(0);
            }
            
            .main-content {
                margin-left: 0;
            }
        }
    </style>
</head>
<body>
    <?php include("includes/navbar.php"); ?>
    
    <div class="admin-container">
        <?php include("includes/sidebare.php"); ?>
        
        <main class="main-content">
            <h1>Bienvenue sur le panneau d'administration</h1>
            <!-- Your page content here -->
        </main>
    </div>
    
    <?php include("includes/scripts.php"); ?>
</body>
</html>