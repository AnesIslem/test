<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: cnxadmin.php");
    exit();
}
include("connexion.php");

// Fetch clients with error handling
$clients = [];
$rqt = "SELECT * FROM `client`";
$result = mysqli_query($conn, $rqt);
if ($result) {
    $clients = mysqli_fetch_all($result, MYSQLI_ASSOC);
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Clients</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <!-- Your existing styles -->
    
    <style>
        .main-content {
            padding: 20px;
            margin-left: 250px;
            transition: margin-left 0.3s;
        }
        
        .data-container {
            background-color: rgba(255, 255, 255, 0.9);
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            padding: 20px;
            margin-top: 20px;
        }
        
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .data-table th, 
        .data-table td {
            padding: 12px 15px;
            text-align: center;
            border: 1px solid #ddd;
        }
        
        .data-table th {
            background-color: #2c3e50;
            color: white;
            font-weight: 600;
        }
        
        .data-table tr:nth-child(even) {
            background-color: #f3e0e0;
        }
        
        .data-table tr:hover {
            background-color: #e1c7c7;
        }
        
        .action-btn {
            padding: 6px 12px;
            margin: 2px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-delete {
            background-color: #e74c3c;
            color: white;
        }
        
        .btn-edit {
            background-color: #3498db;
            color: white;
        }
        
        .btn-return {
            display: inline-block;
            margin-top: 20px;
            padding: 8px 16px;
            background-color: #95a5a6;
            color: white;
            text-decoration: none;
            border-radius: 4px;
        }
        
        @media (max-width: 768px) {
            .main-content {
                margin-left: 0;
                padding: 15px;
            }
            
            .data-table {
                display: block;
                overflow-x: auto;
            }
        }
    </style>
</head>
<body>
    <?php include("includes/navbar.php"); ?>
    
    <div class="admin-container">
        <?php include("includes/sidebare.php"); ?>
        
        <main class="main-content">
            <h1><i class="fas fa-users"></i> Liste des Clients</h1>
            
            <div class="data-container">
                <?php if (empty($clients)): ?>
                    <p>Aucun client trouvé.</p>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>NIN</th>
                                <th>Nom</th>
                                <th>Prénom</th>
                                <th>Date de naissance</th>
                                <th>Adresse</th>
                                <th>Email</th>
                                <th>Téléphone</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($clients as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client['ID_C']) ?></td>
                                <td><?= htmlspecialchars($client['NIN']) ?></td>
                                <td><?= htmlspecialchars($client['Nom']) ?></td>
                                <td><?= htmlspecialchars($client['Prenom']) ?></td>
                                <td><?= htmlspecialchars($client['Date de naissance']) ?></td>
                                <td><?= htmlspecialchars($client['adresse']) ?></td>
                                <td><?= htmlspecialchars($client['email']) ?></td>
                                <td><?= htmlspecialchars($client['numero de Telephone']) ?></td>
                                <td>
                                    <form method="post" action="formulaire_de_modifier.php" style="display: inline;">
                                        <input type="hidden" name="NIN" value="<?= htmlspecialchars($client['NIN']) ?>">
                                        <button type="submit" class="action-btn btn-edit">
                                            <i class="fas fa-edit"></i> Modifier
                                        </button>
                                    </form>
                                    <form method="post" action="suprimer_client.php" style="display: inline;" 
                                          onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce client?');">
                                        <input type="hidden" name="NIN" value="<?= htmlspecialchars($client['NIN']) ?>">
                                        <button type="submit" class="action-btn btn-delete">
                                            <i class="fas fa-trash"></i> Supprimer
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                <?php endif; ?>
                
                <a href="admin.php" class="btn-return">
                    <i class="fas fa-arrow-left"></i> Retour
                </a>
            </div>
        </main>
    </div>
<?php include("includes/scripts.php"); ?>
    
</body>
</html>