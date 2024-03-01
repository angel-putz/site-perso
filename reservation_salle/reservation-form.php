<?php

include_once "database.php";

session_start();

$pdo = Database::connect();
if(isset($_SESSION['login'])) {
    if($_SERVER['REQUEST_METHOD'] == 'POST') {
        $pdo = Database::connect();
        $titre = $_POST['titre'];
        $debut = $_POST['debut'];
        $fin = $_POST['fin'];
        $description = $_POST['description'];
        $id_utilisateur = $pdo->prepare("SELECT id FROM utilisateurs WHERE login = '".$_SESSION['login']."'"); //on prépare la requête SQL
        $id_utilisateur->execute(); //on exécute la requête SQL
        $id_utilisateur = $id_utilisateur->fetch()['id']; //on récupère l'id de l'utilisateur

        
        $stmt = $pdo->prepare("INSERT INTO reservations (id_utilisateur, titre , debut , description , fin) VALUES ('$id_utilisateur' , '$titre' , '$debut' , '$description' , '$fin')");
        $stmt->execute();
        header("Location: planning.php");
        Database::disconnect();
    }

}

// pour savoir le le temps total de la réservation il faut faire une requête SQL qui va chercher la date de début et la date de fin et qui va faire la différence entre les deux


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
<form method="post">
    <h1>Réserver une salle</h1>
    <p>Utilisateur : <?php echo $_SESSION['login']; ?></p>
    Titre: <input type="text" name="titre" required><br>
    <p>Veuillez choisir des heures rondes (ex: 08:00, 09:00, 10:00, etc...) , pas le samedi et dimanche et reserver des créneaux d'une heure</p>
    Heure de début: <input type="datetime-local" name="debut" min="08:00" max="18:00" required><br>
    Heure de fin: <input type="datetime-local" name="fin" min="08:00" max="18:00" required><br>
    Description: <textarea name="description" required rows="4" cols="44"></textarea><br>
    <div class="col-sm-offset-2 col-sm-10">
      <button type="submit" class="btn btn-success">Reserver</button>
    </div>
    <a class="btn btn-primary" href="./planning.php" role="button">Retour</a>
</body>
</html>