<?php

$serveur = "localhost";
$utilisateur = "dhome";
$motdepasse = "";
$nom_bdd = "dhome";

$acces_serveur = mysql_connect($serveur,$utilisateur,$motdepasse)
    or die("Impossible de se connecter : " . mysql_error());
$select_bdd = mysql_select_db($nom_bdd)
   or die ('Impossible de sélectionner la base de données : ' . mysql_error());
   
mysql_set_charset('utf8',$acces_serveur);

?>