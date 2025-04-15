<?php 
require_once 'connexion.php';

// Fetch random properties with their principal photo
$stmt = $pdo->prepare("
    SELECT p.*, w.nom AS wilaya_name, pp.url_photo AS principal_photo
    FROM proprietes p
    JOIN wilayas w ON p.wilaya_id = w.wid
    LEFT JOIN propriete_photos pp ON p.pid = pp.propriete_id AND pp.est_principale = 1
    WHERE p.statut = 'disponible'
    ORDER BY RAND()
    LIMIT 3
");
$stmt->execute();
$properties = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>HomeX - Agence Immobilière Algérienne</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <nav class="barre-navigation">
        <div class="nav-left">
            <a href="acheter.php">Acheter</a>
            <a href="vendre.php">Vendre</a>
            <a href="louer.php">Louer</a>
        </div>
        <div class="nav-center">
            <a href="accueil.php" class="logo">HomeX</a>
        </div>
        <div class="nav-right">
            <a href="recherche.php" class="bouton-recherche">Rechercher</a>
        </div>
    </nav>

    <section class="hero">
        <h1>Trouvez Votre Propriété en Algérie</h1>
        
        <div class="formulaire-compact">
            <form id="searchForm" action="recherche.php" method="GET">
                <div class="ligne-form">
                    <!-- Transaction Type -->
                    <select name="transaction" required aria-placeholder="Transaction">
                       
                        <option value="achat">Acheter</option>
                        <option value="location">Louer</option>
                    </select>
                    
                    <!-- Property Type -->
                    <select name="type_propriete" id="type_propriete" required>
                        <option value="">Type de propriété</option>
                        <option value="maison">Maison</option>
                        <option value="appartement">Appartement</option>
                        <option value="villa">Villa</option>
                        <option value="lot">Lot</option>
                        <option value="terrain">Terrain</option>
                    </select>
                    
                    <!-- Wilaya -->
                    <select name="wilaya">
    <option value="">Toute l'Algérie</option>
    <option value="1">01 - Adrar</option>
    <option value="2">02 - Chlef</option>
    <option value="3">03 - Laghouat</option>
    <option value="4">04 - Oum El Bouaghi</option>
    <option value="5">05 - Batna</option>
    <option value="6">06 - Béjaïa</option>
    <option value="7">07 - Biskra</option>
    <option value="8">08 - Béchar</option>
    <option value="9">09 - Blida</option>
    <option value="10">10 - Bouira</option>
    <option value="11">11 - Tamanrasset</option>
    <option value="12">12 - Tébessa</option>
    <option value="13">13 - Tlemcen</option>
    <option value="14">14 - Tiaret</option>
    <option value="15">15 - Tizi Ouzou</option>
    <option value="16">16 - Alger</option>
    <option value="17">17 - Djelfa</option>
    <option value="18">18 - Jijel</option>
    <option value="19">19 - Sétif</option>
    <option value="20">20 - Saïda</option>
    <option value="21">21 - Skikda</option>
    <option value="22">22 - Sidi Bel Abbès</option>
    <option value="23">23 - Annaba</option>
    <option value="24">24 - Guelma</option>
    <option value="25">25 - Constantine</option>
    <option value="26">26 - Médéa</option>
    <option value="27">27 - Mostaganem</option>
    <option value="28">28 - M'Sila</option>
    <option value="29">29 - Mascara</option>
    <option value="30">30 - Ouargla</option>
    <option value="31">31 - Oran</option>
    <option value="32">32 - El Bayadh</option>
    <option value="33">33 - Illizi</option>
    <option value="34">34 - Bordj Bou Arreridj</option>
    <option value="35">35 - Boumerdès</option>
    <option value="36">36 - El Tarf</option>
    <option value="37">37 - Tindouf</option>
    <option value="38">38 - Tissemsilt</option>
    <option value="39">39 - El Oued</option>
    <option value="40">40 - Khenchela</option>
    <option value="41">41 - Souk Ahras</option>
    <option value="42">42 - Tipaza</option>
    <option value="43">43 - Mila</option>
    <option value="44">44 - Aïn Defla</option>
    <option value="45">45 - Naâma</option>
    <option value="46">46 - Aïn Témouchent</option>
    <option value="47">47 - Ghardaïa</option>
    <option value="48">48 - Relizane</option>
    <option value="49">49 - Timimoun</option>
    <option value="50">50 - Bordj Badji Mokhtar</option>
    <option value="51">51 - Ouled Djellal</option>
    <option value="52">52 - Béni Abbès</option>
    <option value="53">53 - In Salah</option>
    <option value="54">54 - In Guezzam</option>
    <option value="55">55 - Touggourt</option>
    <option value="56">56 - Djanet</option>
    <option value="57">57 - El M'Ghair</option>
    <option value="58">58 - El Menia</option>
</select>
                    
                    <!-- Price -->
                    <select name="prix_max">
                        <option value="">Budget max</option>
                        <option value="5000000">5M DZD</option>
                        <option value="10000000">10M DZD</option>
                        <option value="20000000">20M DZD</option>
                    </select>
                </div>
                
                <!-- Dynamic Fields Container -->
                <div id="extra_fields"></div>
                
                <!-- Submit Button -->
                <div class="ligne-form" style="margin-top: 15px;">
                    <button type="submit" class="bouton-recherche">Trouver Maintenant</button>
                </div>
            </form>
        </div>
    </section>
    <div class="properties-container">
        <?php if (!empty($properties)): ?>
            <?php foreach ($properties as $property): 
                $price = number_format($property['prix'], 0, ',', ' ');
                $isForRent = ($property['transaction_type'] === 'location');
                
                // Fetch the main photo for the property
                $photoStmt = $pdo->prepare("SELECT url_photo FROM propriete_photos WHERE propriete_id = ? LIMIT 1");
                $photoStmt->execute([$property['pid']]);
                $photo = $photoStmt->fetchColumn();
                
                // Use the fetched photo or fallback to a default image
                $photoPath = $photo ? "uploads/properties/$photo" : 'uploads/properties/default.jpg';
            ?>
            <div class="property-card">
                <div class="property-image">
                    <img src="<?= htmlspecialchars($photoPath) ?>" 
                         alt="<?= htmlspecialchars($property['titre']) ?>"
                         onerror="this.src='uploads/properties/default.jpg'">
                </div>
                <div class="property-details">
                    <div class="property-price"><?= $price ?> DZD</div>
                    <h3 class="property-title"><?= htmlspecialchars($property['titre']) ?></h3>
                    <div class="property-location">
                        <?= htmlspecialchars($property['commune'] ?? 'Commune non spécifiée') ?>, 
                        <?= htmlspecialchars($property['wilaya_name']) ?>
                    </div>
                    <div class="property-features">
                        <span class="feature"><?= $property['surface'] ?> m²</span>
                        <?php if ($property['chambres']): ?>
                            <span class="feature"><?= $property['chambres'] ?> chambres</span>
                        <?php endif; ?>
                        <?php if ($property['has_garage']): ?>
                            <span class="feature">Garage</span>
                        <?php endif; ?>
                    </div>
                    <div class="property-actions">
                        <?php if ($isForRent): ?>
                            <a href="louer.php?id=<?= $property['pid'] ?>" class="btn btn-rent">Louer</a>
                        <?php else: ?>
                            <a href="acheter.php?id=<?= $property['pid'] ?>" class="btn btn-buy">Acheter</a>
                        <?php endif; ?>
                        <a href="details.php?id=<?= $property['pid'] ?>" class="btn btn-details">Détails</a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        <?php else: ?>
            <p>Aucune propriété disponible pour le moment.</p>
        <?php endif; ?>
    </div>

    <?php include 'footer.php'; ?>
    <script src="script.js"></script>
</body>
</html>