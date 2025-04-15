<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

include("connexion.php");

// Initialize variables
$searchResults = [];
$searchPerformed = false;
$lastName = '';
$firstName = '';

// Process search if form submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST' && (isset($_POST['last_name']) || isset($_POST['first_name']))) {
    $lastName = trim($_POST['last_name'] ?? '');
    $firstName = trim($_POST['first_name'] ?? '');
    
    // Prepare search conditions
    $conditions = [];
    $params = [];
    $types = '';
    
    if (!empty($lastName)) {
        $cleanLastName = mysqli_real_escape_string($conn, $lastName);
        $conditions[] = "`Nom` LIKE ?";
        $params[] = "%$cleanLastName%";
        $types .= 's';
    }
    
    if (!empty($firstName)) {
        $cleanFirstName = mysqli_real_escape_string($conn, $firstName);
        $conditions[] = "`Prenom` LIKE ?";
        $params[] = "%$cleanFirstName%";
        $types .= 's';
    }
    
    if (!empty($conditions)) {
        $whereClause = implode(' AND ', $conditions);
        $query = "SELECT * FROM `client` WHERE $whereClause";
        
        $stmt = mysqli_prepare($conn, $query);
        if ($stmt) {
            mysqli_stmt_bind_param($stmt, $types, ...$params);
            mysqli_stmt_execute($stmt);
            $result = mysqli_stmt_get_result($stmt);
            
            if ($result) {
                $searchResults = mysqli_fetch_all($result, MYSQLI_ASSOC);
            }
            $searchPerformed = true;
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Recherche par Nom et Prénom</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        .search-container {
            max-width: 800px;
            margin: 30px auto;
            padding: 30px;
            background-color: #fff;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        }
        
        .search-header {
            text-align: center;
            margin-bottom: 30px;
        }
        
        .search-form {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .search-group {
            display: flex;
            flex-direction: column;
        }
        
        .search-label {
            margin-bottom: 8px;
            font-weight: 600;
            color: #333;
        }
        
        .search-input {
            padding: 12px 15px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        
        .search-button {
            grid-column: span 2;
            padding: 12px;
            background-color: #3498db;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: background-color 0.3s;
            font-size: 16px;
        }
        
        .search-button:hover {
            background-color: #2980b9;
        }
        
        .results-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        
        .results-table th, 
        .results-table td {
            padding: 12px 15px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        
        .results-table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }
        
        .results-table tr:hover {
            background-color: #f1f1f1;
        }
        
        .no-results {
            text-align: center;
            padding: 20px;
            color: #666;
            font-style: italic;
            grid-column: span 2;
        }
        
        .action-button {
            padding: 6px 12px;
            margin-right: 5px;
            border: none;
            border-radius: 3px;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            font-size: 14px;
        }
        
        .edit-button {
            background-color: #2ecc71;
            color: white;
        }
        
        .delete-button {
            background-color: #e74c3c;
            color: white;
        }
        
        @media (max-width: 768px) {
            .search-form {
                grid-template-columns: 1fr;
            }
            
            .search-button {
                grid-column: span 1;
            }
        }
    </style>
</head>
<body>
    <?php include("includes/navbar.php"); ?>
    <?php include("includes/sidebare.php"); ?>
    
    <div class="search-container">
        <div class="search-header">
            <h2>Recherche Avancée</h2>
            <p>Vous pouvez rechercher par nom, prénom, ou les deux</p>
        </div>
        
        <form method="POST" class="search-form">
            <div class="search-group">
                <label for="last_name" class="search-label">Nom de famille</label>
                <input type="text" id="last_name" name="last_name" class="search-input" 
                       placeholder="Entrez le nom (ex: Soudani)"
                       value="<?= htmlspecialchars($lastName) ?>">
            </div>
            
            <div class="search-group">
                <label for="first_name" class="search-label">Prénom</label>
                <input type="text" id="first_name" name="first_name" class="search-input" 
                       placeholder="Entrez le prénom (ex: Anes)"
                       value="<?= htmlspecialchars($firstName) ?>">
            </div>
            
            <button type="submit" class="search-button">Rechercher</button>
        </form>
        
        <?php if ($searchPerformed): ?>
            <?php if (!empty($searchResults)): ?>
                <table class="results-table">
                    <thead>
                        <tr>
                            <th>NIN</th>
                            <th>Nom</th>
                            <th>Prénom</th>
                            <th>Téléphone</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($searchResults as $client): ?>
                            <tr>
                                <td><?= htmlspecialchars($client['NIN']) ?></td>
                                <td><?= htmlspecialchars($client['Nom']) ?></td>
                                <td><?= htmlspecialchars($client['Prenom']) ?></td>
                                <td><?= htmlspecialchars($client['numero de Telephone']) ?></td>
                                <td>
                                    <a href="formulaire_de_modifier.php?NIN=<?= urlencode($client['NIN']) ?>" 
                                       class="action-button edit-button">Modifier</a>
                                    <a href="suprimer_client.php?NIN=<?= urlencode($client['NIN']) ?>" 
                                       class="action-button delete-button"
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce client?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-results">
                    Aucun résultat trouvé <?php 
                        echo !empty($lastName) ? "pour le nom \"".htmlspecialchars($lastName)."\"" : "";
                        echo !empty($firstName) ? (empty($lastName) ? "pour" : " et")." le prénom \"".htmlspecialchars($firstName)."\"" : "";
                    ?>
                </div>
            <?php endif; ?>
        <?php endif; ?>
    </div>
</body>
</html>