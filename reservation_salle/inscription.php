<?php

include_once "database.php";

$pdo = Database::connect();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $login = htmlspecialchars($_POST["login"]); //on récupère le login de l'utilisateur 
    $password = htmlspecialchars($_POST["password"]); 
    $confirm_password = htmlspecialchars($_POST["confirm_password"]);

    if ($password != $confirm_password) { //si les deux mots de passe ne sont pas identiques 
        echo "Passwords do not match.";
    } else {

        $sql = "INSERT INTO utilisateurs (login, password) VALUES (?, ?)"; //la requete SQL pour insérer un nouvel utilisateur dans la BDD 
        $stmt = $pdo->prepare($sql); //on prépare la requête SQL 
        $stmt->execute([$login, $password]); //on exécute la requête SQL

        header("Location: connexion.php");
        exit();
    }
}

Database::disconnect();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="index.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
include "header.php";
?>
<div class="fond-inscription">
<div class="blanc">
<h1>Inscription</h1>
    <form method="post">
        Login:<input type="text" name="login" id="login" pattern="^[A-Za-z'-]+$" required >
        Password:<input type="password" name="password" id="password" required  >
        Confirm Password:<input type="password" name="confirm_password" id="confirm_password" required>
        <div class="col-sm-offset-2 col-sm-10">
            <button type="submit" class="btn btn-success">S'inscrire</button>
        </div>
</body>
</html>