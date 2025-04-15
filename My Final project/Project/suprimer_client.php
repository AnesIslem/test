<?php
require('connexion.php');
$CODE=$_POST['NIN'];
mysqli_query($conn,"delete from client where NIN = '$CODE'");
header('location:list_clients.php');

?>