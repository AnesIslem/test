<?php


// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "agency";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle property deletion
if (isset($_GET['delete'])) {
    $pid = intval($_GET['delete']);
    
    // First delete photos
    $deletePhotos = $conn->prepare("DELETE FROM propriete_photos WHERE propriete_id = ?");
    $deletePhotos->bind_param("i", $pid);
    $deletePhotos->execute();
    
    // Then delete property
    $deleteProperty = $conn->prepare("DELETE FROM proprietes WHERE pid = ?");
    $deleteProperty->bind_param("i", $pid);
    
    if ($deleteProperty->execute()) {
        $success_message = "Property deleted successfully!";
    } else {
        $error_message = "Error deleting property!";
    }
}

// Fetch all properties with photos count
$sql = "SELECT 
            p.*, 
            w.nom AS wilaya_name,
            (SELECT COUNT(*) FROM propriete_photos WHERE propriete_id = p.pid) AS photos_count
        FROM proprietes p
        JOIN wilayas w ON p.wilaya_id = w.wid
        ORDER BY p.date_ajout DESC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - HomeX</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" >
    <style>
        :root {
            --primary: #2c3e50;
            --secondary: #3498db;
            --danger: #e74c3c;
            --light: #ecf0f1;
            --dark: #34495e;
        }
        
        body {
            background-color: #f8f9fa;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
        }
        
        .sidebar {
            background-color: var(--primary);
            color: white;
            height: 100vh;
            position: fixed;
            padding-top: 20px;
        }
        
        .sidebar a {
            color: white;
            text-decoration: none;
            display: block;
            padding: 10px 15px;
            margin: 5px 0;
            border-radius: 5px;
            transition: all 0.3s;
        }
        
        .sidebar a:hover {
            background-color: var(--secondary);
        }
        
        .sidebar a.active {
            background-color: var(--secondary);
            font-weight: bold;
        }
        
        .sidebar i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }
        
        .main-content {
            margin-left: 250px;
            padding: 20px;
        }
        
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            margin-bottom: 20px;
            transition: transform 0.3s;
        }
        
        .card:hover {
            transform: translateY(-5px);
        }
        
        .badge-rent {
            background-color: #3498db;
        }
        
        .badge-sale {
            background-color: #e74c3c;
        }
        
        .property-img {
            height: 120px;
            object-fit: cover;
            border-radius: 5px;
        }
        
        .action-btn {
            padding: 5px 10px;
            font-size: 0.8rem;
            margin-right: 5px;
        }
        
        .table-responsive {
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
            padding: 20px;
        }
        
        .table th {
            background-color: var(--primary);
            color: white;
        }
        
        .status-available {
            color: #27ae60;
        }
        
        .status-sold {
            color: #e74c3c;
        }
        
        .status-rented {
            color: #f39c12;
        }
    </style>
</head>
<body>
    <div class="container-fluid" style="margin-left: -20px;">
    <?php include("includes/navbar.php"); ?>

        <div class="row">
            <!-- Sidebar -->
            <div class="admin-container">
        <?php include("includes/sidebare.php"); ?>
        
            </div>
            
            <!-- Main Content -->
            <div class="col-md-10 main-content">
                <h2 class="mb-4"><i class="fas fa-home"></i> All Properties</h2>
                
                <?php if (isset($success_message)): ?>
                    <div class="alert alert-success"><?= $success_message ?></div>
                <?php endif; ?>
                
                <?php if (isset($error_message)): ?>
                    <div class="alert alert-danger"><?= $error_message ?></div>
                <?php endif; ?>
                
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Type</th>
                                <th>Transaction</th>
                                <th>Price</th>
                                <th>Location</th>
                                <th>Status</th>
                                <th>Photos</th>
                                <th>Date Added</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if ($result->num_rows > 0): ?>
                                <?php while ($property = $result->fetch_assoc()): 
                                    $price = number_format($property['prix'], 0, ',', ' ');
                                    $isForRent = ($property['transaction_type'] === 'location');
                                    $photoStmt = $conn->prepare("SELECT url_photo FROM propriete_photos WHERE propriete_id = ? LIMIT 1");
                                    $photoStmt->bind_param("i", $property['pid']);
                                    $photoStmt->execute();
                                    $photo = $photoStmt->get_result()->fetch_assoc();
                                    $photoPath = $photo ? "uploads/properties/{$photo['url_photo']}" : 'uploads/properties/default.jpg';
                                ?>
                                <tr>
                                    <td><?= $property['pid'] ?></td>
                                    <td>
                                        <img src="<?= htmlspecialchars($photoPath) ?>" 
                                             alt="Property Image" 
                                             class="property-img"
                                             onerror="this.src='images/default-property.jpg'">
                                    </td>
                                    <td><?= htmlspecialchars($property['titre']) ?></td>
                                    <td><?= ucfirst(htmlspecialchars($property['type_propriete'])) ?></td>
                                    <td>
                                        <span class="badge <?= $isForRent ? 'badge-rent' : 'badge-sale' ?>">
                                            <?= $isForRent ? 'Location' : 'Vente' ?>
                                        </span>
                                    </td>
                                    <td><?= $price ?> DZD</td>
                                    <td>
                                        <?= htmlspecialchars($property['commune'] ?? 'N/A') ?>, 
                                        <?= htmlspecialchars($property['wilaya_name']) ?>
                                    </td>
                                    <td>
                                        <?php if ($property['statut'] === 'disponible'): ?>
                                            <span class="status-available"><i class="fas fa-check-circle"></i> Available</span>
                                        <?php elseif ($property['statut'] === 'vendu'): ?>
                                            <span class="status-sold"><i class="fas fa-times-circle"></i> Sold</span>
                                        <?php else: ?>
                                            <span class="status-rented"><i class="fas fa-check-circle"></i> Rented</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?= $property['photos_count'] ?> <i class="fas fa-camera"></i></td>
                                    <td><?= date('d/m/Y', strtotime($property['date_ajout'])) ?></td>
                                    <td>
                                        <a href="admin_edit_property.php?id=<?= $property['pid'] ?>" 
                                           class="btn btn-primary btn-sm action-btn" 
                                           title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <a href="?delete=<?= $property['pid'] ?>" 
                                           class="btn btn-danger btn-sm action-btn" 
                                           title="Delete"
                                           onclick="return confirm('Are you sure you want to delete this property?')">
                                            <i class="fas fa-trash"></i>
                                        </a>
                                        <a href="details.php?id=<?= $property['pid'] ?>" 
                                           class="btn btn-info btn-sm action-btn" 
                                           title="View">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            <?php else: ?>
                                <tr>
                                    <td colspan="11" class="text-center">No properties found in database</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-between mt-4">
                    <a href="admin_add_property.php" class="btn btn-success">
                        <i class="fas fa-plus"></i> Add New Property
                    </a>
                    <div>
                        <span class="badge bg-secondary">Total Properties: <?= $result->num_rows ?></span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include("includes/scripts.php"); ?>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Confirm before deleting
        document.querySelectorAll('.delete-btn').forEach(btn => {
            btn.addEventListener('click', function(e) {
                if (!confirm('Are you sure you want to delete this property?')) {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>

<?php
$conn->close();
?>