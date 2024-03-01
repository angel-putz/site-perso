 <?php
 session_start();
 $_SESSION = array();
 session_destroy();
 ?>
 <!DOCTYPE html>
 <html>
 <head>
 <link rel="stylesheet" href="index.css">
 <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
 </head>
 <body>
 <div class="deconnexion">
 <?php
 include "header.php";
 ?>
 <p>Vous êtes déconnecté</p>
 <a href="index.php" class="btn btn-success" role="button">Retour à l'accueil</a>
 </div>
 </body>