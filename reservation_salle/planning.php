
<?php

include_once "database.php";

session_start();

$pdo = Database::connect();

$date1 = date('D-m-Y');
$date2 = date('D-m-Y', strtotime('+1 day'));
$date3 = date('D-m-Y', strtotime('+2 day'));
$date4 = date('D-m-Y', strtotime('+3 day'));
$date5 = date('D-m-Y', strtotime('+4 day'));

$DateSQL1 = date('Y-m-d');
$DateSQL2 = date('Y-m-d', strtotime('+1 day'));
$DateSQL3 = date('Y-m-d', strtotime('+2 day'));
$DateSQL4 = date('Y-m-d', strtotime('+3 day'));
$DateSQL5 = date('Y-m-d', strtotime('+4 day'));



$result ='';

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


<div class="droite">

<ul class="menu">
    <li>
      <a href="#">Profil</a>
      <ul class="sousmenu">
        <li><a href="./profil.php">Modifier son profil</a></li>
        <li><a href="./deco.php">Deconnexion</a></li>
      </ul>
    </li>
  </ul>
</div>
</body>
</html>

<?php

// Jours en abscisse
$jours = [$date1, $date2, $date3, $date4, $date5];
$weekend = ['Sat', 'Sun'];


// Jours pour la base de données SQL
$joursSQL = [$DateSQL1, $DateSQL2, $DateSQL3, $DateSQL4, $DateSQL5];

// Heures de 8h à 19h en ordonnées
$heures = ['08h', '09h', '10h', '11h', '12h', '13h', '14h', '15h','16h', '17h', '18h', '19h'];

$tableau = [];

foreach ($jours as $jour) {
    foreach ($heures as $heure) {
        $heure = str_replace('h', ':', $heure); //on remplace le h par : pour que la requete SQL fonctionne
        $joursSQL = date('Y-m-d', strtotime($jour)); //on remplace les jours par des dates SQL pour que la requete SQL fonctionne
        $stmt = $pdo->prepare("SELECT titre FROM reservations WHERE debut = '$joursSQL $heure:00:00'");
        
        $stmt->execute();
        $result = $stmt->fetch();
        if ($result != false){
            $result = array_shift($result); //on récupère les informations de la BDD
            $tableau[$jour][$heure] = $result; //on affiche les informations de la BDD dans le tableau  
        }

        else if (in_array(substr($jour, 0, 3), $weekend)) //si le jour est un weekend on affiche salle fermée
            
            $tableau[$jour][$heure] = 'Salle fermée';

        else 
            $tableau[$jour][$heure] = 'Salle libre'; //si la salle est libre on affiche salle libre
    }
}

?>

<!-- Le code HTML -->

<!-- Creation du tableau -->
<table border='1'>
    <tr>
        <th></th>
        <?php foreach ($jours as $jour) { ?>  <!--on affiche les jours en haut du tableau --> 
            <th><?php echo $jour; ?></th> 
        <?php } ?>
    </tr>
    <?php foreach ($heures as $heure) { ?> <!-- on affiche les heures sur la gauche du tableau -->
        <tr>
            <th><?php echo $heure; ?></th>
            <?php foreach ($jours as $jour) { ?> 
                <td><?php 
                    $heure = str_replace('h', ':', $heure); //on remplace le h par : pour que la requete SQL fonctionne
                    if ($tableau[$jour][$heure] == 'Salle fermée'){ //si la salle est fermée on affiche salle fermée
                        ?> <div class="rouge"><?php
                        echo $tableau[$jour][$heure]; 
                        ?></div><?php
                    }
                    else if ($tableau[$jour][$heure] == 'Salle libre') //si la salle est libre on affiche salle libre
                        echo $tableau[$jour][$heure];
                    else
                    echo $tableau[$jour][$heure]; //on affiche les informations de la BDD dans le tableau 
                    ?></td>
            <?php } ?>
        </tr>
    <?php } ?>
</table>
<div class="milieu">
<a class="btn btn-primary" href="./reservation-form.php" role="button">Reserver</a>