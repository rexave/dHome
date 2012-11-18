<?php

require_once("../connexion.php");

	//DEHORS
	//temperature instantanée
	$sql = mysql_query("SELECT valeur1 FROM  historique_donnees 
						INNER JOIN 
							(
							SELECT MAX(date_histo) maxi_date, id_objet
							FROM historique_donnees
							WHERE id_objet = 3
							) max_historique_donnees ON max_historique_donnees.id_objet = historique_donnees.id_objet AND max_historique_donnees.maxi_date = historique_donnees.date_histo
						");
	list($dehors["temperature_current"]) = mysql_fetch_array($sql);
	//tendance
	$sql = mysql_query("SELECT valeur1 FROM  historique_donnees 
						INNER JOIN 
							(
							SELECT MAX(date_histo) maxi_date, id_objet
							FROM historique_donnees
							WHERE id_objet = 3
							) max_historique_donnees 
							ON max_historique_donnees.id_objet = historique_donnees.id_objet 
							AND YEAR(max_historique_donnees.maxi_date) = YEAR(historique_donnees.date_histo)
							AND MONTH(max_historique_donnees.maxi_date) = MONTH(historique_donnees.date_histo)
							AND DAY(max_historique_donnees.maxi_date) = DAY(historique_donnees.date_histo)
							AND HOUR(max_historique_donnees.maxi_date)-1 = HOUR(historique_donnees.date_histo)
							AND MINUTE(max_historique_donnees.maxi_date) = MINUTE(historique_donnees.date_histo)
						");
	list($dehors["temperature_last_hour"]) = mysql_fetch_array($sql);
	if($dehors["temperature_current"] == $dehors["temperature_last_hour"]) $dehors["temperature_tendance"]="stagne";
	else if($dehors["temperature_current"] < $dehors["temperature_last_hour"]) $dehors["temperature_tendance"]="baisse";
	else $dehors["temperature_tendance"]="hausse";
	
	//CHAMBRE
	$sql = mysql_query("SELECT valeur1, valeur2 FROM  historique_donnees 
						INNER JOIN 
							(
							SELECT MAX(date_histo) maxi_date, id_objet
							FROM historique_donnees
							WHERE id_objet = 1
							) max_historique_donnees ON max_historique_donnees.id_objet = historique_donnees.id_objet AND max_historique_donnees.maxi_date = historique_donnees.date_histo
						");
	list($chambre["temperature_current"],$chambre["hygro_current"]) = mysql_fetch_array($sql);
	$sql = mysql_query("SELECT valeur1,valeur2 FROM  historique_donnees 
						INNER JOIN 
							(
							SELECT MAX(date_histo) maxi_date, id_objet
							FROM historique_donnees
							WHERE id_objet = 1
							) max_historique_donnees 
							ON max_historique_donnees.id_objet = historique_donnees.id_objet 
							AND YEAR(max_historique_donnees.maxi_date) = YEAR(historique_donnees.date_histo)
							AND MONTH(max_historique_donnees.maxi_date) = MONTH(historique_donnees.date_histo)
							AND DAY(max_historique_donnees.maxi_date) = DAY(historique_donnees.date_histo)
							AND HOUR(max_historique_donnees.maxi_date)-1 = HOUR(historique_donnees.date_histo)
							AND MINUTE(max_historique_donnees.maxi_date) = MINUTE(historique_donnees.date_histo)
						");
	list($chambre["temperature_last_hour"],$chambre["hygro_last_hour"]) = mysql_fetch_array($sql);
	if($chambre["temperature_current"] == $chambre["temperature_last_hour"]) $chambre["temperature_tendance"]="stagne";
	else if($chambre["temperature_current"] < $chambre["temperature_last_hour"]) $chambre["temperature_tendance"]="baisse";
	else $chambre["temperature_tendance"]="hausse";

	if($chambre["hygro_current"] == $chambre["hygro_last_hour"]) $chambre["hygro_tendance"]="stagne";
	else if($chambre["hygro_current"] < $chambre["hygro_last_hour"]) $chambre["hygro_tendance"]="baisse";
	else $chambre["hygro_tendance"]="hausse";


	
	$sql = mysql_query("SELECT valeur1, valeur2, valeur3, valeur4 FROM  historique_donnees 
						INNER JOIN 
							(
							SELECT MAX(date_histo) maxi_date, id_objet
							FROM historique_donnees
							WHERE id_objet = 4
							) max_historique_donnees ON max_historique_donnees.id_objet = historique_donnees.id_objet AND max_historique_donnees.maxi_date = historique_donnees.date_histo
						");
	list($sejour["temperature_current"],$sejour["hygro_current"],$sejour["pression"],$sejour["prevision"]) = mysql_fetch_array($sql);
	$sql = mysql_query("SELECT valeur1,valeur2 FROM  historique_donnees 
						INNER JOIN 
							(
							SELECT MAX(date_histo) maxi_date, id_objet
							FROM historique_donnees
							WHERE id_objet = 4
							) max_historique_donnees 
							ON max_historique_donnees.id_objet = historique_donnees.id_objet 
							AND YEAR(max_historique_donnees.maxi_date) = YEAR(historique_donnees.date_histo)
							AND MONTH(max_historique_donnees.maxi_date) = MONTH(historique_donnees.date_histo)
							AND DAY(max_historique_donnees.maxi_date) = DAY(historique_donnees.date_histo)
							AND HOUR(max_historique_donnees.maxi_date)-1 = HOUR(historique_donnees.date_histo)
							AND MINUTE(max_historique_donnees.maxi_date) = MINUTE(historique_donnees.date_histo)
						");
	list($sejour["temperature_last_hour"],$sejour["hygro_last_hour"]) = mysql_fetch_array($sql);
	if($sejour["temperature_current"] == $sejour["temperature_last_hour"]) $sejour["temperature_tendance"]="stagne";
	else if($sejour["temperature_current"] < $sejour["temperature_last_hour"]) $sejour["temperature_tendance"]="baisse";
	else $sejour["temperature_tendance"]="hausse";
	if($sejour["hygro_current"] == $sejour["hygro_last_hour"]) $sejour["hygro_tendance"]="stagne";
	else if($sejour["hygro_current"] < $sejour["hygro_last_hour"]) $sejour["hygro_tendance"]="baisse";
	else $sejour["hygro_tendance"]="hausse";

	
	$sql = mysql_query("SELECT valeur1 FROM  historique_donnees 
						INNER JOIN 
							(
							SELECT MAX(date_histo) maxi_date, id_objet
							FROM historique_donnees
							WHERE id_objet = 2
							) max_historique_donnees ON max_historique_donnees.id_objet = historique_donnees.id_objet AND max_historique_donnees.maxi_date = historique_donnees.date_histo
						");
	list($conso["instantanee"]) = mysql_fetch_array($sql);
	
?>
<p>Dehors :  <b><?php echo $dehors["temperature_current"];?></b> °C [<?php echo $dehors["temperature_tendance"];?>] </p>
<p>Chambre :  <b><?php echo $chambre["temperature_current"];?></b> °C [<?php echo $chambre["temperature_tendance"];?>] - <b><?php echo $chambre["hygro_current"];?></b> % [<?php echo $chambre["hygro_tendance"];?>]</p>
<p>Séjour :  <b><?php echo $sejour["temperature_current"];?></b> °C [<?php echo $sejour["temperature_tendance"];?>] - <b><?php echo $sejour["hygro_current"];?></b> % [<?php echo $sejour["hygro_tendance"];?>] - <b><?php echo $sejour["pression"];?></b> HPa- <?php echo $sejour["prevision"];?></p>
<p>Conso :  <b><?php echo $conso["instantanee"];?></b> W</p>
			
