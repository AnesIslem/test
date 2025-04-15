<?php
require_once 'connexion.php';

if (!isset($_GET['id']) || !is_numeric($_GET['id'])) {
    header('Location: accueil.php');
    exit;
}

$propertyId = intval($_GET['id']);

// Fetch property details with main photo
$stmt = $pdo->prepare("
    SELECT p.*, w.nom AS wilaya_name,
           (SELECT url_photo FROM propriete_photos 
            WHERE propriete_id = p.pid 
            ORDER BY est_principale DESC LIMIT 1) AS main_photo
    FROM proprietes p
    JOIN wilayas w ON p.wilaya_id = w.wid
    WHERE p.pid = ? AND p.statut = 'disponible'
");
$stmt->execute([$propertyId]);
$property = $stmt->fetch();

if (!$property) {
    header('Location: accueil.php');
    exit;
}

// Fetch all photos for the gallery
$photoStmt = $pdo->prepare("
    SELECT url_photo 
    FROM propriete_photos 
    WHERE propriete_id = ?
    ORDER BY est_principale DESC, pid ASC
    LIMIT 3
");
$photoStmt->execute([$propertyId]);
$photos = $photoStmt->fetchAll();

// Format price and details
$price = number_format($property['prix'], 0, ',', ' ');
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($property['titre']) ?> - HomeX</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Additional styles for detail page */
        .property-detail-container {
            max-width: 1200px;
            margin: 40px auto;
            padding: 0 20px;
        }
        
        .property-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid #e2e8f0;
        }
        body.modal-open {
    overflow: hidden;
}
        
        .property-title {
            font-size: 1.8rem;
            color: var(--primary-blue);
            margin: 0;
        }
        
        .property-price {
            font-size: 2rem;
            font-weight: bold;
            color: var(--accent-blue);
        }
        
        .property-gallery {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 15px;
            margin-bottom: 40px;
        }
        
        .gallery-main {
            grid-column: span 2;
            grid-row: span 2;
            height: 400px;
        }
        
        .gallery-secondary {
            height: 192.5px;
        }
        
        .property-gallery img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }
        
        .property-content {
            display: grid;
            grid-template-columns: 2fr 1fr;
            gap: 30px;
        }
        
        .property-description {
            line-height: 1.7;
            color: #334155;
        }
        
        .property-features {
            background: white;
            padding: 25px;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }
        
        .feature-item {
            display: flex;
            justify-content: space-between;
            padding: 12px 0;
            border-bottom: 1px solid #f1f5f9;
        }
        
        .feature-item:last-child {
            border-bottom: none;
        }
        
        .feature-label {
            font-weight: 500;
            color: #64748b;
        }
        
        .feature-value {
            font-weight: 600;
            color: var(--text-dark);
        }
        
        .action-section {
            margin-top: 40px;
            text-align: center;
            padding: 30px;
            background: white;
            border-radius: 8px;
            box-shadow: var(--shadow);
        }
        
        .btn-buy-now {
            background: var(--accent-blue);
            color: white;
            padding: 15px 30px;
            font-size: 1.1rem;
            font-weight: 600;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            transition: background 0.2s;
            display: inline-block;
            margin-top: 15px;
            text-decoration: none;
        }
        
        .btn-buy-now:hover {
            background: #1e40af;
        }
        
        @media (max-width: 768px) {
            .property-header {
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
            
            .property-gallery {
                grid-template-columns: 1fr;
            }
            
            .gallery-main {
                grid-column: span 1;
                height: 250px;
            }
            
            .property-content {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'navbar.php'; ?>
    
        
       <div class="property-detail-container">
        <div class="property-header">
            <h1 class="property-title"><?= htmlspecialchars($property['titre']) ?></h1>
            <div class="property-price"><?= $price ?> DZD</div>
        </div>
        
        <div class="property-gallery">
            <!-- Main Image -->
            <div class="gallery-main">
                <img src="uploads/properties/<?= !empty($photos[0]['url_photo']) ? htmlspecialchars($photos[0]['url_photo']) : 'default.jpg' ?>" 
                     alt="<?= htmlspecialchars($property['titre']) ?>"
                     onerror="this.src='uploads/properties/default.jpg'">
            </div>
            
            <!-- Secondary Images -->
            <?php for ($i = 1; $i < 3; $i++): ?>
                <div class="gallery-secondary">
                    <img src="uploads/properties/<?= !empty($photos[$i]['url_photo']) ? htmlspecialchars($photos[$i]['url_photo']) : 'default.jpg' ?>" 
                         alt="<?= htmlspecialchars($property['titre']) ?> - Photo <?= $i+1 ?>"
                         onerror="this.src='uploads/properties/default.jpg'">
                </div>
            <?php endfor; ?>
            </div>
        
        <div class="property-content">
            <div class="property-description">
                <h2>Description</h2>
                <p><?= nl2br(htmlspecialchars($property['description'] ?? 'Aucune description disponible.')) ?></p>
                
                <h2>Détails du bien</h2>
                <p>
                    <strong>Type :</strong> <?= ucfirst(htmlspecialchars($property['type_propriete'])) ?><br>
                    <strong>Surface :</strong> <?= $property['surface'] ?> m²<br>
                    <?php if ($property['chambres']): ?>
                        <strong>Chambres :</strong> <?= $property['chambres'] ?><br>
                    <?php endif; ?>
                    <?php if ($property['salles_de_bain']): ?>
                        <strong>Salles de bain :</strong> <?= $property['salles_de_bain'] ?><br>
                    <?php endif; ?>
                </p>
            </div>
            
            <div class="property-features">
                <h2>Caractéristiques</h2>
                <div class="feature-item">
                    <span class="feature-label">Wilaya</span>
                    <span class="feature-value"><?= htmlspecialchars($property['wilaya_name']) ?></span>
                </div>
                <div class="feature-item">
                    <span class="feature-label">Commune</span>
                    <span class="feature-value"><?= htmlspecialchars($property['commune'] ?? 'Non spécifiée') ?></span>
                </div>
                <div class="feature-item">
                    <span class="feature-label">Garage</span>
                    <span class="feature-value"><?= $property['has_garage'] ? 'Oui' : 'Non' ?></span>
                </div>
                <div class="feature-item">
                    <span class="feature-label">Jardin</span>
                    <span class="feature-value"><?= $property['has_jardin'] ? 'Oui' : 'Non' ?></span>
                </div>
                <div class="feature-item">
                    <span class="feature-label">Date d'ajout</span>
                    <span class="feature-value"><?= date('d/m/Y', strtotime($property['date_ajout'])) ?></span>
                </div>
            </div>
        </div>
        
        <div class="action-section">
            <h2>Intéressé par cette propriété ?</h2>
            <p>Contactez-nous dès maintenant pour finaliser votre achat.</p>
           
    <button onclick="document.getElementById('purchase-form').style.display='block'" 
            class="btn-buy-now">
        Acheter Maintenant
    </button>
</div>
            <p class="contact-info">Ou appelez-nous au : <strong>XX XX XX XX XX</strong></p>
        </div>
    </div>
    
    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
    <script>
// Formatting functions
function formatNIN(input) {
    input.value = input.value.replace(/\D/g, '').slice(0, 12);
}

function formatPhone(input) {
    input.value = input.value.replace(/\D/g, '').slice(0, 10);
}

// Form validation
function validatePurchaseForm() {
    const form = document.getElementById('purchase-form-data');
    let isValid = true;

    // Clear previous errors
    document.querySelectorAll('.error-message').forEach(el => el.style.display = 'none');

    // Validate NIN
    if (!/^\d{12}$/.test(form.nin.value)) {
        showError(form.nin, "Le NIN doit contenir exactement 12 chiffres");
        isValid = false;
    }

    // Validate phone
    if (!/^\d{10}$/.test(form.telephone.value)) {
        showError(form.telephone, "Le téléphone doit contenir 10 chiffres");
        isValid = false;
    }

    // Validate birth date (minimum 18 years)
    const dob = new Date(form.date_naissance.value);
    const minAgeDate = new Date();
    minAgeDate.setFullYear(minAgeDate.getFullYear() - 18);
    
    if (dob >= minAgeDate) {
        showError(form.date_naissance, "Vous devez avoir au moins 18 ans");
        isValid = false;
    }

    if (isValid) {
        submitPurchaseForm();
    }

    return false; // Prevent default submission
}

function showError(input, message) {
    const error = document.createElement('div');
    error.className = 'error-message';
    error.textContent = message;
    error.style.display = 'block';
    
    // Insert after input
    input.parentNode.insertBefore(error, input.nextSibling);
    input.focus();
}

function submitPurchaseForm() {
    const form = document.getElementById('purchase-form-data');
    const formData = new FormData(form);

    fetch('process_purchase.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            window.location.href = 'merci.php?id=' + data.property_id;
        } else {
            alert("Erreur: " + data.message);
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert("Une erreur s'est produite");
    });
}

// Modal controls
document.querySelector('.purchase-modal .close').addEventListener('click', function() {
    document.getElementById('purchase-form').style.display = 'none';
});

// Open modal from button
document.querySelector('.btn-buy-now').addEventListener('click', function() {
    document.getElementById('purchase-form').style.display = 'flex';
});
function openModal() {
    document.getElementById('purchase-form').style.display = 'flex';
    document.body.classList.add('modal-open');
}

function closeModal() {
    document.getElementById('purchase-form').style.display = 'none';
    document.body.classList.remove('modal-open');
}
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape' && document.getElementById('purchase-form').style.display === 'flex') {
        closeModal();
    }
});
</script>
<div id="purchase-form" class="purchase-modal">
    <div class="modal-content">
        <span class="close">&times;</span>
        <h2>Formulaire d'Achat</h2>
        <form id="purchase-form-data" onsubmit="return validatePurchaseForm()">
            <input type="hidden" name="property_id" value="<?= $property['pid'] ?>">

            <!-- Personal Information -->
            <fieldset>
                <legend>Informations Personnelles</legend>
                <div class="form-grid">
                    <div class="form-group">
                        <label for="nin">NIN*</label>
                        <input type="text" id="nin" name="nin" required 
                               pattern="[0-9]{12}" title="12 chiffres requis"
                               oninput="formatNIN(this)">
                        <span class="hint">Ex: 123456789012</span>
                    </div>

                    <div class="form-group">
                        <label for="date_naissance">Date Naissance*</label>
                        <input type="date" id="date_naissance" name="date_naissance" required
                               max="<?= date('Y-m-d', strtotime('-18 years')) ?>">
                    </div>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="nom">Nom*</label>
                        <input type="text" id="nom" name="nom" required
                               pattern="[A-Za-zÀ-ÿ\s\-]{2,}">
                    </div>

                    <div class="form-group">
                        <label for="prenom">Prénom*</label>
                        <input type="text" id="prenom" name="prenom" required
                               pattern="[A-Za-zÀ-ÿ\s\-]{2,}">
                    </div>
                </div>
            </fieldset>

            <!-- Contact Information -->
            <fieldset>
                <legend>Coordonnées</legend>
                <div class="form-group">
                    <label for="adresse">Adresse Complète*</label>
                    <input type="text" id="adresse" name="adresse" required>
                </div>

                <div class="form-grid">
                    <div class="form-group">
                        <label for="email">Email*</label>
                        <input type="email" id="email" name="email" required>
                    </div>

                    <div class="form-group">
                        <label for="telephone">Téléphone*</label>
                        <input type="tel" id="telephone" name="telephone" required
                               pattern="[0-9]{10}" title="10 chiffres requis"
                               oninput="formatPhone(this)">
                        <span class="hint">Ex: 0550123456</span>
                    </div>
                </div>
            </fieldset>

            <!-- Payment Method -->
            <fieldset>
                <legend>Paiement</legend>
                <div class="form-group">
                    <label>Méthode de Paiement*</label>
                    <div class="radio-group">
                        <label>
                            <input type="radio" name="payment_method" value="virement" required> Virement Bancaire
                        </label>
                        <label>
                            <input type="radio" name="payment_method" value="ccp"> CCP
                        </label>
                        <label>
                            <input type="radio" name="payment_method" value="cheque"> Chèque
                        </label>
                    </div>
                </div>
            </fieldset>

            <div class="form-actions">
                <button type="submit" class="btn-submit">
                    <i class="fas fa-check-circle"></i> Confirmer l'Achat
                </button>
            </div>
        </form>
    </div>
</div>
</body>
<style>
    /* Purchase Form Modal */
.purchase-modal {
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.7);
    display: flex;
    justify-content: center;
    align-items: center;
}

.modal-content {
    background: white;
    padding: 30px;
    border-radius: 8px;
    width: 100%;
    max-width: 500px;
    box-shadow: 0 4px 20px rgba(0,0,0,0.2);
}

.close {
    float: right;
    font-size: 28px;
    cursor: pointer;
}

.form-group {
    margin-bottom: 20px;
}

.form-group label {
    display: block;
    margin-bottom: 8px;
    font-weight: 500;
}

.form-group input, 
.form-group textarea {
    width: 100%;
    padding: 12px;
    border: 1px solid #ddd;
    border-radius: 4px;
    font-size: 16px;
}

.btn-submit {
    background: var(--accent-blue);
    color: white;
    padding: 12px 24px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    font-size: 16px;
    width: 100%;
}

.btn-submit:hover {
    background: #1e40af;
}
/* Purchase Form Styles */
.purchase-modal {
    position: fixed;
    /* ... existing styles ... */
    display: none; /* Hidden by default */
}

.modal-content {
    max-height: 90vh;
    overflow-y: auto;
}

fieldset {
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 20px;
    margin-bottom: 20px;
}

legend {
    padding: 0 10px;
    font-weight: 600;
    color: var(--primary-blue);
}

.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.hint {
    display: block;
    font-size: 0.8rem;
    color: #64748b;
    margin-top: 4px;
}

.radio-group {
    display: flex;
    gap: 20px;
    margin-top: 8px;
}

.radio-group label {
    display: flex;
    align-items: center;
    gap: 5px;
    cursor: pointer;
}

/* Validation Styles */
input:invalid {
    border-color: #e53e3e;
}

input:valid {
    border-color: #38a169;
}

.error-message {
    color: #e53e3e;
    font-size: 0.8rem;
    margin-top: 4px;
    display: none;
}
</style>
</html>