<?php
session_start();
if (!isset($_SESSION['loggedin'])) {
    header("Location: login.php");
    exit();
}

require("connexion.php");

// Secure way to get NIN from either POST or GET
$nin = $_POST['NIN'] ?? $_GET['NIN'] ?? null;

if (!$nin) {
    die("<p style='color: red; text-align: center;'>Aucun NIN spécifié.</p>");
}

// Use prepared statement to prevent SQL injection
$stmt = mysqli_prepare($conn, "SELECT * FROM client WHERE NIN = ?");
mysqli_stmt_bind_param($stmt, "s", $nin);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$row = mysqli_fetch_assoc($result);

if (!$row) {
    die("<p style='color: red; text-align: center;'>Aucune donnée trouvée pour ce NIN.</p>");
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Client</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    <style>
        .form-container {
            max-width: 900px;
            margin: 20px auto;
            padding: 30px;
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        
        .form-title {
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }
        
        .form-col {
            flex: 1;
        }
        
        .form-label {
            display: block;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .form-control {
            width: 100%;
            padding: 10px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 16px;
        }
        
        .required-marker {
            color: red;
            margin-left: 4px;
        }
        
        .btn-group {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin-top: 30px;
        }
        
        .btn {
            padding: 10px 25px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: #3498db;
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #2980b9;
        }
        
        .btn-secondary {
            background-color: #95a5a6;
            color: white;
        }
        
        .btn-secondary:hover {
            background-color: #7f8c8d;
        }
        
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 0;
            }
        }
    </style>
</head>
<body>
    <?php include("includes/navbar.php"); ?>
    <?php include("includes/sidebare.php"); ?>
    
    <div class="form-container">
        <h1 class="form-title">Modification du Client</h1>
        
        <form action="modifier_client.php" method="post">
            <input type="hidden" name="id" value="<?= htmlspecialchars($row['ID_C']) ?>">
            
            <div class="form-group">
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">NIN <span class="required-marker">*</span></label>
                        <input type="text" class="form-control" name="num" 
                               value="<?= htmlspecialchars($row['NIN']) ?>" required readonly>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Nom <span class="required-marker">*</span></label>
                        <input type="text" class="form-control" name="nom" 
                               value="<?= htmlspecialchars($row['Nom']) ?>" required>
                    </div>
                    <div class="form-col">
                        <label class="form-label">Prénom <span class="required-marker">*</span></label>
                        <input type="text" class="form-control" name="prenom" 
                               value="<?= htmlspecialchars($row['Prenom']) ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Date de Naissance <span class="required-marker">*</span></label>
                        <input type="date" class="form-control" name="date" 
                               value="<?= htmlspecialchars($row['Date de naissance']) ?>" required
                               min="1920-01-01" max="2006-12-31">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Adresse <span class="required-marker">*</span></label>
                        <input type="text" class="form-control" name="adress" 
                               value="<?= htmlspecialchars($row['adresse']) ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="form-group">
                <div class="form-row">
                    <div class="form-col">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control" name="email" 
                               value="<?= htmlspecialchars($row['email']) ?>">
                    </div>
                    <div class="form-col">
                        <label class="form-label">Téléphone <span class="required-marker">*</span></label>
                        <input type="tel" class="form-control" name="telephone" 
                               value="<?= htmlspecialchars($row['numero de Telephone']) ?>" required>
                    </div>
                </div>
            </div>
            
            <div class="btn-group">
                <button type="submit" class="btn btn-primary" name="ajouter">Enregistrer</button>
                <a href="list_clients.php" class="btn btn-secondary">Retour</a>
            </div>
        </form>
    </div>

    <script>
    document.addEventListener("DOMContentLoaded", function() {
        // Phone number validation
        const phoneInput = document.querySelector('input[name="telephone"]');
        phoneInput.addEventListener('input', function(e) {
            this.value = this.value.replace(/[^0-9+]/g, '');
        });
        
        // Date validation
        const dateInput = document.querySelector('input[name="date"]');
        dateInput.setAttribute("min", "1920-01-01");
        dateInput.setAttribute("max", "2006-12-31");
    });
    </script>
    <?php include("includes/scripts.php"); ?>
</body>
</html>