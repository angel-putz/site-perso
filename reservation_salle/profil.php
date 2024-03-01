<?php

include_once "database.php";

    session_start();

    if ($_SERVER["REQUEST_METHOD"] == "POST") { 
        $login = htmlspecialchars($_POST["login"]); //on récupère les données du formulaire et on les sécurise 
        $password = htmlspecialchars($_POST["password"]); //on récupère les données du formulaire et on les sécurise
        $pdo = Database::connect();
        $sql="UPDATE utilisateurs SET login =: login, password =:password WHERE login = '$_SESSION[login]'"; //la requete SQL pour modifier le login et le mot de passe de l'utilisateur connecté 
        $stmt = $pdo->prepare($sql); //on prépare la requête SQL
        $stmt->execute(['login' => $login , 'password' => $password]); //on exécute la requête SQL avec les paramètres login et password
        echo "Votre login et votre password ont bien été modifiés";
          
        ?>
        <?php
        exit();
        }

    Database::disconnect();

    ?>

    <!DOCTYPE html>
    <html>
    <head>
            <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
            <link rel="stylesheet" href="index.css">
    </head>
    <body>
    <?php
include "header.php";
?>
    <div class="fond-profil">
<div class="blanc">
        <h1> Vous pouvez changer vos login , password , reserver une salle ou se déconnecter</h1>
</div>
    <form method="post">
        Bonjour <?php echo $_SESSION['login']; ?><br>
        Nouveau login : <input type="text" name="login" required pattern="^[A-Za-z'-]+$"><br>
        Nouveau password : <input type="password" name="password" required><br>
        <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-success" name="envoye">Mettre a jour</button>
    </div><br>
        <a class="btn btn-primary" href="./planning.php" role="button">Planning</a>
        <a class="btn btn-danger" href="./deco.php" role="button" style="margin-left: 130px;">Deconnexion</a>
    </form>
    </div>
    </body>
    </html>