<?php
session_start();
if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true) {
    header("Location: cnxadmin.php");
    exit();
}
include("connexion.php"); // Include your database connection file
$rch=$_POST['search'];
if(ctype_digit($rch)){
    $rqt="SELECT * FROM `client` WHERE NIN LIKE '%$rch%'";
}elseif (!ctype_digit($rch)) {
$rqt="SELECT * FROM `client` WHERE CONCAT(`Nom`,`Prenom`) LIKE '%$rch%'";
}
$result=mysqli_query($conn,$rqt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
  
    <title>List of clients</title>
    <style>
        body{
            
            background-color:white;
        }
        table{
            position: static;
            margin-top: 40px;
            margin-left: 20px;
            margin-right: 20px;
            font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
            border-style: double;
            margin-bottom: 20px;
           
        }
        td{
            height: 35px;
            background-color: #f3e0e0;
            border-style: ridge;
            width: max-content;
            text-align: center;
            
        }
        th{ 
            height: 30px;
            font-weight: bold;
            border-style:solid;
        }
        .tableaux{
            border-radius: 7px;
            text-align: center;
            border-style: double;
            height: max-content;
            width: max-content;
            margin-top: 100px;
            margin-left: 370px;
            position:relative ;
            box-shadow: 2px -1px 20px 12px hwb(0deg 0% 100% / 27.1%);            
        }
    </style>
</head>
<body>
    <nav><?php include("includes/navbar.php")?></nav>
    <div class="container">
        <?php include("includes/sidebare.php"); ?>
    <div class="tableaux">
<h1>Clients List</h1>

    <table>
        <tr>
        <th>ID</th>
        <th>NIN</th>
        <th>Nom</th>
        <th>Prenom</th>
        <th>Date de naissance</th>
        <th>adresse</th>
        <th>email</th>
        <th>num de telephone</th>
        <th>Action 1</th>
        <th>Action 2</th>
        </tr>
        <?php 
        if(mysqli_num_rows($result)==0){
            ?> <tr>
                <td colspan="10"><?php  echo "no results found " ;?></td>
            </tr>
            <?php
        }
        else{
        foreach($result as $row){ ?>
       <tr>
       <td><?php echo $row['ID_C'];?></td>
        <td><?php echo $row['NIN'];?></td>
        <td><?php echo $row['Nom'];?></td>
        <td><?php echo $row['Prenom'];?></td>
        <td><?php echo $row['Date de naissance'];?></td>
        <td><?php echo $row['adresse'];?></td>
        <td><?php echo $row['email'];?></td>
        <td><?php echo $row['numero de Telephone'];?></td>
        <td><form method="post" action="suprimer_client.php">
    <input type="hidden" name="NIN" value="<?php echo $row['NIN'];?>"></input>   
    <input type="submit" value="suprimer"></input></form></td>
    <td> <form method="post" action="formulaire_de_modifier.php">
    <input type="hidden" name="NIN" value="<?php echo $row['NIN'];?>"></input>
    <input type="submit" value="modifier"></input></form></td>
</td> 
        </tr>
        <?php }}?>
    </table>
    <a href="type_de_recherch.php"><input type="button" name="Routour" value="Routour" style="margin-bottom: 7px;width:90px ;height: 30px;"></a>
</div>
</div>
<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-beta1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>