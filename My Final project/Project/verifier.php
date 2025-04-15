<?php 
$errors = [];

// Check name (only letters and spaces)
if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $nom)) {
    $errors['nom'] = "Le nom ne doit contenir que des lettres.";
}

// Check surname (only letters and spaces)
if (!preg_match("/^[a-zA-ZÀ-ÿ\s]+$/", $prenom)) {
    $errors['prenom'] = "Le prénom ne doit contenir que des lettres.";
}

// Check required fields
if (empty($nin) || empty($nom) || empty($prenom) || empty($date) || empty($tele) || empty($adr)) {
    $errors['general'] = "Tous les champs (sauf email) sont requis.";
}
?>