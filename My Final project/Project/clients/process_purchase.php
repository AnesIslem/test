<?php
header('Content-Type: application/json');
require 'connexion.php';

$response = ['success' => false, 'message' => ''];

try {
    // Validate required fields
    $required = [
        'nin', 'nom', 'prenom', 'date_naissance',
        'adresse', 'email', 'telephone', 'property_id', 'payment_method'
    ];
    
    foreach ($required as $field) {
        if (empty($_POST[$field])) {
            throw new Exception("Le champ $field est requis");
        }
    }

    // Validate input formats
    if (!preg_match('/^\d{12}$/', $_POST['nin'])) {
        throw new Exception("NIN doit contenir 12 chiffres");
    }

    if (!preg_match('/^\d{10}$/', $_POST['telephone'])) {
        throw new Exception("Téléphone doit contenir 10 chiffres");
    }

    // Validate payment method
    $allowedMethods = ['virement', 'ccp', 'cheque'];
    if (!in_array($_POST['payment_method'], $allowedMethods)) {
        throw new Exception("Méthode de paiement invalide");
    }

    // Verify property availability
    $propertyId = intval($_POST['property_id']);
    $stmt = $pdo->prepare("SELECT pid, prix FROM proprietes WHERE pid = ? AND statut = 'disponible'");
    $stmt->execute([$propertyId]);
    $property = $stmt->fetch();

    if (!$property) {
        throw new Exception("La propriété n'est plus disponible");
    }

    // Start transaction
    $pdo->beginTransaction();

    // 1. Handle client (insert or update)
    $clientStmt = $pdo->prepare("
        INSERT INTO client 
        (NIN, Nom, Prenom, date_naissance, adresse, email, telephone)
        VALUES (?, ?, ?, ?, ?, ?, ?)
        ON DUPLICATE KEY UPDATE
        Nom = VALUES(Nom),
        Prenom = VALUES(Prenom),
        adresse = VALUES(adresse),
        email = VALUES(email),
        telephone = VALUES(telephone)
    ");
    
    $clientStmt->execute([
        $_POST['nin'],
        $_POST['nom'],
        $_POST['prenom'],
        $_POST['date_naissance'],
        $_POST['adresse'],
        $_POST['email'],
        $_POST['telephone']
    ]);

    // 2. Get client ID
    $clientId = $pdo->lastInsertId();
    if ($clientId == 0) {
        $clientId = $pdo->query("SELECT ID_C FROM client WHERE NIN = '{$_POST['nin']}'")->fetchColumn();
    }

    // 3. Create transaction record (with payment method)
    $transactionStmt = $pdo->prepare("
        INSERT INTO transactions 
        (property_id, client_id, montant, payment_method, date_transaction)
        VALUES (?, ?, ?, ?, NOW())
    ");
    
    $transactionStmt->execute([
        $propertyId,
        $clientId,
        $property['prix'],
        $_POST['payment_method']
    ]);

    // 4. Update property status
    $pdo->prepare("UPDATE proprietes SET statut = 'reserve' WHERE pid = ?")
       ->execute([$propertyId]);

    $pdo->commit();

    // Send confirmation
    sendConfirmation($_POST['email'], $propertyId, $clientId);
    
    $response = [
        'success' => true,
        'property_id' => $propertyId,
        'client_id' => $clientId
    ];

} catch (PDOException $e) {
    $pdo->rollBack();
    $response['message'] = "Erreur base de données: " . $e->getMessage();
    $response['error_info'] = $e->errorInfo ?? null;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);

function sendConfirmation($email, $propertyId, $clientId) {
    // Implement email sending logic
    $to = htmlspecialchars($email);
    $subject = "Confirmation de votre demande d'achat";
    $message = "
        <h2>Merci pour votre demande d'achat</h2>
        <p>Référence propriété: #$propertyId</p>
        <p>Votre référence client: #$clientId</p>
        <p>Un conseiller vous contactera sous 24h.</p>
    ";
    
    $headers = "From: no-reply@votre-agence.com\r\n";
    $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
    
    mail($to, $subject, $message, $headers);
}
?>