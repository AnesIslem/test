<?php
session_start();
include("connexion.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>connexion</title>
    <style>
        h1{
            margin-top: 0100px;
        }
        body{
            background-color:#ffe6e6;
            text-align: center;
            align-content: center;
        }
        form{
            background-color: #f3cfcf;
            width: 350px;
            text-align: start;
            border-radius: 5px;
            padding-top: 20px;
            margin-top: 40px;
            box-shadow: 0px -1px 20px 10px hwb(0 18% 75% / 0.271);
            height: 170px;
            
        }
        .box{
            margin-left: 500px;
        }
        div{
            height: 50px;
        }
       div >label{
       font-weight: bold;
       font-size: larger;
      /*text-align:right;*/
       margin-left: 20px;
       }
       div>input{
       margin-left:60px;
       width: fit-content;
       height: 30px;
       border-radius: 5px;
       border-style: double;
       font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;
       font-size: 1.01rem;
       font-weight: 500;
       }
    </style>
</head>
<body>
   <h1>Connexion de Adminstrateur</h1><br>
   <div class="box">
    <div class="formulaire" style="width: 300px;">
        <form action="cnxadmin.php" method="post">
            <div><label for="pesudo">Pesudo:</label>
            <input type="text" name="pesudo" require placeholder="Entrez votre Pesudo Svp"id="pesudo" style="margin-left: 80px;"><br>
           
    </div>
    <div><label for="mot de pass">Password:</label>
    <input type="password" name="mot de pass" require placeholder="Entrez votre mot de pass SVP" id="pass" >

    </div>
    <div class="btn"> <input type="submit" value="Connecter" name="submit" style="margin-top:20px ;margin-left: 40%;border-radius: 4px;border-style: double;font-size: large;font-family: 'Gill Sans', 'Gill Sans MT', Calibri, 'Trebuchet MS', sans-serif;font-weight: 400;background-color:#ffbddf;">
    </div>
        </form>
    </div>
   </div> 
</body>
</html>
<?php 
if (isset($_POST['submit'])) {
    // Retrieve form data
    $pass = $_POST['mot_de_pass'];
    $username = $_POST['pesudo'];

    // Validate input (basic example)
    if (isset($_POST['submit'])&&(empty($username) || empty($pass))) {
        die("Tous les champs sont requis.");
    }

    // Fix SQL syntax: Use backticks for column names with spaces
    $rqt = "SELECT * FROM `admin` WHERE `name` = '$username' AND `password` = '$pass'";

    // Execute the query
    $result = mysqli_query($conn, $rqt) or die(mysqli_error($conn));

    // Check if the query returned any rows
    $num_row_connect = mysqli_num_rows($result);
    if ($num_row_connect == 1) {
        $_SESSION['loggedin'] = true;
        $_SESSION['username'] = $username;
        header("Location: admin.php");
        exit(); 
    } else {
        echo "Pseudo ou mot de passe incorrect.";
    }
}
?>