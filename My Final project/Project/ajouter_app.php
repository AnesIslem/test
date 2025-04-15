<?php
require_once 'connexion.php';

// Function to get all wilayas
function getWilayas() {
    $conn = new mysqli('localhost', 'root', '', 'agency');
    $result = $conn->query("SELECT wid, nom FROM wilayas ORDER BY nom");
    $wilayas = [];
    while($row = $result->fetch_assoc()) {
        $wilayas[] = $row;
    }
    $conn->close();
    return $wilayas;
}

$successMessage = '';
$errorMessage = '';

// Check if form is submitted
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic validation
    $requiredFields = ['titre', 'type_propriete', 'transaction_type', 'prix', 'surface', 'wilaya_id', 'description'];
    foreach ($requiredFields as $field) {
        if (empty($_POST[$field])) {
            $errorMessage = "Le champ $field est obligatoire";
            break;
        }
    }

    if (!$errorMessage) {
        $conn = new mysqli('localhost', 'root', '', 'agency');
        
        // Get next property ID
        $result = $conn->query("SELECT MAX(pid) as max_id FROM proprietes");
        $propertyId = ($result->fetch_assoc())['max_id'] + 1;

        // Escape all inputs
        $titre = $conn->real_escape_string($_POST['titre']);
        $description = $conn->real_escape_string($_POST['description']);
        $type_propriete = $conn->real_escape_string($_POST['type_propriete']);
        $transaction_type = $conn->real_escape_string($_POST['transaction_type']);
        $prix = (float)$_POST['prix'];
        $surface = (int)$_POST['surface'];
        $wilaya_id = (int)$_POST['wilaya_id'];
        $commune = $conn->real_escape_string($_POST['commune'] ?? '');
        $adresse = $conn->real_escape_string($_POST['adresse'] ?? '');
        $chambres = !empty($_POST['chambres']) ? (int)$_POST['chambres'] : 'NULL';
        $salles_de_bain = !empty($_POST['salles_de_bain']) ? (int)$_POST['salles_de_bain'] : 'NULL';
        $etage = !empty($_POST['etage']) ? (int)$_POST['etage'] : 'NULL';
        $nombre_etages = !empty($_POST['nombre_etages']) ? (int)$_POST['nombre_etages'] : 'NULL';
        $has_garage = isset($_POST['has_garage']) ? 1 : 0;
        $has_jardin = isset($_POST['has_jardin']) ? 1 : 0;
        $annee_construction = !empty($_POST['annee_construction']) ? (int)$_POST['annee_construction'] : 'NULL';
        $proprietaire_id = !empty($_POST['proprietaire_id']) ? (int)$_POST['proprietaire_id'] : 'NULL';
        $statut = $conn->real_escape_string($_POST['statut'] ?? 'disponible');

        // Create characteristics JSON
        $caracteristiques = json_encode([
            'climatisation' => isset($_POST['climatisation']) ? 1 : 0,
            'chauffage' => isset($_POST['chauffage']) ? 1 : 0,
            'meuble' => isset($_POST['meuble']) ? 1 : 0,
            'securite' => isset($_POST['securite']) ? 1 : 0,
            'ascenseur' => isset($_POST['ascenseur']) ? 1 : 0,
            'parking' => isset($_POST['parking']) ? 1 : 0,
            'notes' => $_POST['notes'] ?? ''
        ]);

        // Build SQL query
        $sql = "INSERT INTO proprietes SET
            pid = $propertyId,
            titre = '$titre',
            description = '$description',
            type_propriete = '$type_propriete',
            transaction_type = '$transaction_type',
            prix = $prix,
            surface = $surface,
            wilaya_id = $wilaya_id,
            commune = " . ($commune ? "'$commune'" : "NULL") . ",
            adresse = " . ($adresse ? "'$adresse'" : "NULL") . ",
            chambres = $chambres,
            salles_de_bain = $salles_de_bain,
            etage = $etage,
            nombre_etages = $nombre_etages,
            has_garage = $has_garage,
            has_jardin = $has_jardin,
            annee_construction = $annee_construction,
            proprietaire_id = $proprietaire_id,
            statut = '$statut',
            caracteristiques = '" . $conn->real_escape_string($caracteristiques) . "',
            date_ajout = NOW()";

        // Execute query
        if ($conn->query($sql)) {
            $successMessage = "Propriété ajoutée avec succès!";
            
            // Handle photo uploads
            if (isset($_FILES['photos']) && !empty($_FILES['photos']['name'][0])) {
                $uploadDir = 'uploads/properties/';
                if (!file_exists($uploadDir)) {
                    mkdir($uploadDir, 0777, true);
                }
                
                foreach ($_FILES['photos']['name'] as $key => $name) {
                    if ($_FILES['photos']['error'][$key] === UPLOAD_ERR_OK) {
                        $fileName = uniqid() . '_' . basename($name);
                        $uploadPath = $uploadDir . $fileName;
                        
                        if (move_uploaded_file($_FILES['photos']['tmp_name'][$key], $uploadPath)) {
                            $isMain = (isset($_POST['main_photo']) && $_POST['main_photo'] == $key) ? 1 : 0;
                            $conn->query("INSERT INTO propriete_photos SET 
                                propriete_id = $propertyId, 
                                url_photo = '$uploadPath', 
                                est_principale = $isMain");
                        }
                    }
                }
            }
        } else {
            $errorMessage = "Erreur: " . $conn->error;
        }
        
        $conn->close();
    }
}

$wilayas = getWilayas();
?>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion Immobilière - Ajouter une propriété</title>
    <link rel="stylesheet" href="styles.css">
    
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">

    
</head>
<body>
    <!-- Top Navbar -->
    <nav class="admin-navbar">
        <div class="navbar-left">
            <button id="toggle-sidebar" class="toggle-btn">
                <i class="fas fa-bars"></i>
            </button>
            <h1>Gestion Immobilière</h1>
        </div>
        <div class="navbar-right">
            <div class="user-menu">
                <i class="fas fa-user"></i>
                <span>Admin</span>
            </div>
        </div>
    </nav>

    <div class="admin-container">
        <!-- Sidebar -->
        <div class="admin-container">
        <?php include("includes/sidebare.php"); ?>
            
        </div>
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="content-header">
                <h2>Ajouter une propriété</h2>
            </div>
            
            <div class="content-body">
                <?php if ($successMessage): ?>
                <div class="alert success">
                    <i class="fas fa-check-circle"></i> <?php echo $successMessage; ?>
                </div>
                <?php endif; ?>
                
                <?php if ($errorMessage): ?>
                <div class="alert error">
                    <i class="fas fa-exclamation-circle"></i> <?php echo $errorMessage; ?>
                </div>
                <?php endif; ?>
                
                <div class="card">
                    <div class="card-body">
                    <form method="POST" action="ajouter_app.php" enctype="multipart/form-data">
                            <div class="form-section">
                                <h3>Informations générales</h3>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="titre">Titre</label>
                                        <input type="text" id="titre" name="titre" required placeholder="Titre de l'annonce">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="type_propriete">Type de propriété</label>
                                        <select id="type_propriete" name="type_propriete" required>
                                            <option value="" disabled selected>Sélectionnez un type</option>
                                            <option value="maison">Maison</option>
                                            <option value="appartement">Appartement</option>
                                            <option value="villa">Villa</option>
                                            <option value="lot">Lot</option>
                                            <option value="terrain">Terrain</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="transaction_type">Type de transaction</label>
                                        <select id="transaction_type" name="transaction_type" required>
                                            <option value="" disabled selected>Sélectionnez un type</option>
                                            <option value="vente">Vente</option>
                                            <option value="location">Location</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="statut">Statut</label>
                                        <select id="statut" name="statut" required>
                                            <option value="disponible">Disponible</option>
                                            <option value="vendu">Vendu</option>
                                            <option value="loué">Loué</option>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="prix">Prix (DA)</label>
                                        <input type="number" id="prix" name="prix" required min="0" step="0.01" placeholder="0.00">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="surface">Surface (m²)</label>
                                        <input type="number" id="surface" name="surface" required min="0" placeholder="0">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Localisation</h3>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="wilaya_id">Wilaya</label>
                                        <select id="wilaya_id" name="wilaya_id" required>
                                            <option value="" disabled selected>Sélectionnez une wilaya</option>
                                            <?php foreach ($wilayas as $wilaya): ?>
                                                <option value="<?php echo $wilaya['wid']; ?>"><?php echo htmlspecialchars($wilaya['nom']); ?></option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="commune">Commune</label>
                                        <input type="text" id="commune" name="commune" placeholder="Commune">
                                    </div>
                                    
                                    <div class="form-group full-width">
                                        <label for="adresse">Adresse complète</label>
                                        <input type="text" id="adresse" name="adresse" placeholder="Adresse complète">
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Caractéristiques</h3>
                                <div class="form-grid">
                                    <div class="form-group">
                                        <label for="chambres">Chambres</label>
                                        <input type="number" id="chambres" name="chambres" min="0" placeholder="0">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="salles_de_bain">Salles de bain</label>
                                        <input type="number" id="salles_de_bain" name="salles_de_bain" min="0" placeholder="0">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="etage">Étage</label>
                                        <input type="number" id="etage" name="etage" min="0" placeholder="0">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="nombre_etages">Nombre d'étages</label>
                                        <input type="number" id="nombre_etages" name="nombre_etages" min="0" placeholder="0">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="annee_construction">Année de construction</label>
                                        <input type="number" id="annee_construction" name="annee_construction" min="1900" max="<?php echo date('Y'); ?>" placeholder="<?php echo date('Y'); ?>">
                                    </div>
                                    
                                    <div class="form-group">
                                        <label for="proprietaire_id">ID Propriétaire</label>
                                        <input type="number" id="proprietaire_id" name="proprietaire_id" min="1" placeholder="1">
                                    </div>
                                </div>
                                
                                <div class="checkbox-group">
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="has_garage" name="has_garage">
                                        <label for="has_garage">Garage</label>
                                    </div>
                                    
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="has_jardin" name="has_jardin">
                                        <label for="has_jardin">Jardin</label>
                                    </div>
                                    
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="climatisation" name="climatisation">
                                        <label for="climatisation">Climatisation</label>
                                    </div>
                                    
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="chauffage" name="chauffage">
                                        <label for="chauffage">Chauffage</label>
                                    </div>
                                    
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="meuble" name="meuble">
                                        <label for="meuble">Meublé</label>
                                    </div>
                                    
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="securite" name="securite">
                                        <label for="securite">Sécurité</label>
                                    </div>
                                    
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="ascenseur" name="ascenseur">
                                        <label for="ascenseur">Ascenseur</label>
                                    </div>
                                    
                                    <div class="checkbox-item">
                                        <input type="checkbox" id="parking" name="parking">
                                        <label for="parking">Parking</label>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Photos</h3>
                                <div class="photo-upload-container">
                                    <div class="photo-upload-area" id="photoUploadArea">
                                        <div class="photo-upload-placeholder">
                                            <i class="fas fa-cloud-upload-alt"></i>
                                            <p>Glissez vos photos ici ou cliquez pour sélectionner</p>
                                            <span>Formats acceptés: JPG, PNG, GIF (Max: 5MB par photo)</span>
                                        </div>
                                        <input type="file" id="photoInput" name="photos[]" multiple accept="image/*" class="photo-input">
                                    </div>
                                    
                                    <div class="photo-preview-container" id="photoPreviewContainer">
                                        <!-- Photo previews will be added here dynamically -->
                                    </div>
                                    
                                    <div class="photo-instructions">
                                        <p><i class="fas fa-info-circle"></i> Sélectionnez la photo principale en cliquant sur "Définir comme principale"</p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="form-section">
                                <h3>Description et notes</h3>
                                <div class="form-group full-width">
                                    <label for="description">Description détaillée</label>
                                    <textarea id="description" name="description" rows="4" required placeholder="Description détaillée de la propriété..."></textarea>
                                </div>
                                
                                <div class="form-group full-width">
                                    <label for="notes">Notes additionnelles</label>
                                    <textarea id="notes" name="notes" rows="3" placeholder="Notes additionnelles (visible uniquement pour l'administration)..."></textarea>
                                </div>
                            </div>
                            
                            <div class="form-actions">
                                <button type="reset" class="btn btn-secondary">
                                    <i class="fas fa-undo"></i> Réinitialiser
                                </button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save"></i> Enregistrer
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
    <script>
        // Toggle sidebar
        document.getElementById('toggle-sidebar').addEventListener('click', function() {
            document.getElementById('sidebar').classList.toggle('collapsed');
        });
        
        
        
        // Show/hide fields based on property type
        document.getElementById('type_propriete').addEventListener('change', function() {
            const propertyType = this.value;
            const etageField = document.getElementById('etage').parentNode;
            const nombreEtagesField = document.getElementById('nombre_etages').parentNode;
            const chambresField = document.getElementById('chambres').parentNode;
            const sallesDeBainField = document.getElementById('salles_de_bain').parentNode;
            
            // Reset visibility
            etageField.style.display = 'block';
            nombreEtagesField.style.display = 'block';
            chambresField.style.display = 'block';
            sallesDeBainField.style.display = 'block';
            
            // Adjust based on property type
            if (propertyType === 'terrain' || propertyType === 'lot') {
                etageField.style.display = 'none';
                nombreEtagesField.style.display = 'none';
                chambresField.style.display = 'none';
                sallesDeBainField.style.display = 'none';
            } else if (propertyType === 'appartement') {
                nombreEtagesField.style.display = 'none';
            } else if (propertyType === 'maison' || propertyType === 'villa') {
                etageField.style.display = 'none';
            }
        });
        
        // Photo upload and preview
        const photoInput = document.getElementById('photoInput');
        const photoUploadArea = document.getElementById('photoUploadArea');
        const photoPreviewContainer = document.getElementById('photoPreviewContainer');
        
        // Handle drag and drop
        ['dragenter', 'dragover', 'dragleave', 'drop'].forEach(eventName => {
            photoUploadArea.addEventListener(eventName, preventDefaults, false);
        });
        
        function preventDefaults(e) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        ['dragenter', 'dragover'].forEach(eventName => {
            photoUploadArea.addEventListener(eventName, highlight, false);
        });
        
        ['dragleave', 'drop'].forEach(eventName => {
            photoUploadArea.addEventListener(eventName, unhighlight, false);
        });
        
        function highlight() {
            photoUploadArea.classList.add('highlight');
        }
        
        function unhighlight() {
            photoUploadArea.classList.remove('highlight');
        }
        
        photoUploadArea.addEventListener('drop', handleDrop, false);
        
        function handleDrop(e) {
            const dt = e.dataTransfer;
            const files = dt.files;
            handleFiles(files);
        }
        
        photoUploadArea.addEventListener('click', function() {
            photoInput.click();
        });
        
        photoInput.addEventListener('change', function() {
            handleFiles(this.files);
        });
        
        function handleFiles(files) {
            for (let i = 0; i < files.length; i++) {
                if (files[i].type.startsWith('image/')) {
                    previewFile(files[i], i);
                }
            }
        }
        
        function previewFile(file, index) {
            const reader = new FileReader();
            reader.readAsDataURL(file);
            
            reader.onloadend = function() {
                const preview = document.createElement('div');
                preview.className = 'photo-preview';
                preview.innerHTML = `
                    <div class="photo-preview-image" style="background-image: url('${reader.result}')"></div>
                    <div class="photo-preview-info">
                        <span class="photo-preview-name">${file.name}</span>
                        <span class="photo-preview-size">${formatFileSize(file.size)}</span>
                    </div>
                    <div class="photo-preview-actions">
                        <label class="photo-main-label">
                            <input type="radio" name="main_photo" value="${index}">
                            <span>Photo principale</span>
                        </label>
                        <button type="button" class="photo-remove-btn">
                            <i class="fas fa-trash"></i>
                        </button>
                    </div>
                `;
                
                photoPreviewContainer.appendChild(preview);
                
                // Handle remove button
                const removeBtn = preview.querySelector('.photo-remove-btn');
                removeBtn.addEventListener('click', function() {
                    preview.remove();
                    
                    // Create a new FileList without the removed file
                    const dt = new DataTransfer();
                    const input = document.getElementById('photoInput');
                    const { files } = input;
                    
                    for (let i = 0; i < files.length; i++) {
                        const file = files[i];
                        if (i !== index) {
                            dt.items.add(file);
                        }
                    }
                    
                    input.files = dt.files;
                });
            }
        }
        
        function formatFileSize(bytes) {
            if (bytes < 1024) return bytes + ' bytes';
            else if (bytes < 1048576) return (bytes / 1024).toFixed(1) + ' KB';
            else return (bytes / 1048576).toFixed(1) + ' MB';
        }
        
        // Responsive behavior
        function checkWidth() {
            if (window.innerWidth <= 768) {
                document.getElementById('sidebar').classList.add('collapsed');
            } else {
                document.getElementById('sidebar').classList.remove('collapsed');
            }
        }
        
        // Initial check
        checkWidth();
        
        // Check on resize
        window.addEventListener('resize', checkWidth);
    </script>
    <?php include("includes/scripts.php"); ?>
</body>
</html>
