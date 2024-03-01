<?php

include_once "database.php";

session_start();

if(isset($_POST['id']) AND $_POST['id'] > 0) { //si l'id est supérieur à 0 
    $getid = intval($_POST['id']); //on récupère l'id de l'utilisateur connecté 
    $pdo = Database::connect();
    $requser = $pdo->prepare('SELECT * FROM utilisateurs WHERE id = ?'); //la requete SQL pour récupérer les informations de l'utilisateur
    $requser->execute(array($getid));
    $userinfo = $requser->fetch();

}

    if($_SERVER["REQUEST_METHOD"] =='POST'){
        $login = htmlspecialchars($_POST["login"]);
        $password = htmlspecialchars($_POST["password"]);
        $pdo = Database::connect();
        $sql="SELECT * FROM utilisateurs WHERE login=:login AND password=:password"; //la requete SQL pour récupérer les informations de l'utilisateur
        $stmt = $pdo->prepare($sql); //on prépare la requête SQL
        $stmt->execute(['login' => $login , 'password' => $password]); //on exécute la requête SQL

        $sql2="SELECT password FROM utilisateurs"; //la requete SQL pour récupérer le mot de passe de l'utilisateur
        $stmt2 = $pdo->prepare($sql2);
        $stmt2->execute(); //on exécute la requête SQL
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        if ( $login == $user['login'] && $password == $user['password']){ //si le login et le mot de passe sont corrects 
            $_SESSION['login'] = $login;
            header("Location: profil.php"); //on est redirigé vers la page profil.php
            exit();  
        }else{
            echo "Mauvais login ou mot de passe !"; //sinon on affiche un message d'erreur
        }
    }
Database::disconnect();

?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="index.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
    </head>
<body>
<?php
include "header.php";
?>
<div class="fond-connexion">
        <div class="blanc">
        <h1>Connexion</h1>
        </div>
<form method="post" >
    Login: <input type="text" name="login" required pattern="^[A-Za-z'-]$"><br>
    Password: <input type="password" name="password" required><br>
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-success">Connexion</button>
    </div>
</form>
</div>
</body>
</html>