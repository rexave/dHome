<?php

require_once ("../includes/global_ui.php");
require_once ("../connexion.php");

switch($_GET[a]){
    default: action_sur_objet(); break;
}


function action_sur_objet(){

    $sql = mysql_query("SELECT id_eventGhost
						FROM objets, actions_definies
						WHERE objets.id_objet_logique = actions_definies.id_objet
						AND objets.id_objet_logique = '".$_GET["id"]."'
						AND actions_definies.id_action = '".$_GET["action"]."'");
	list($id_eventGhost) = mysql_fetch_array($sql);
	
	appel_macro_eventghost($id_eventGhost);
	
	//on recupere l'etat cible de l'objet apres l'action
	$sql_etat_cible = mysql_query("	SELECT id_etat_cible, objets.id_type_objet_logique,objets.nom_objet
									FROM objets, actions_possibles
									WHERE objets.id_type_objet_logique = actions_possibles.id_type_objet
									AND objets.id_objet_logique = '".$_GET["id"]."'
									AND actions_possibles.id_action = '".$_GET["action"]."'")  or die (mysql_error());
	list($id_etat_cible,$id_type_objet,$nom_objet) = mysql_fetch_array($sql_etat_cible);

	//on met l'objet impacté dans son nouvel état
	$sql_update = mysql_query("UPDATE objets SET id_etat='".$id_etat_cible."' WHERE id_objet_logique = '".$_GET["id"]."'")  or die (mysql_error());

	//recupere la nouvelle icone
	$sql_icone = mysql_query("	SELECT nom_icone
								FROM  etats_possibles
								WHERE etats_possibles.id_etat = '".$id_etat_cible."'
								AND etats_possibles.id_type_objet='".$id_type_objet."'") or die (mysql_error());
	list($nom_icone)=mysql_fetch_array($sql_icone);
	echo '<img src="../images/'.$nom_icone.'" alt="'.$nom_icone.'"/>
			<h3>'.$nom_objet.'</h3>';
	
	//reconstruit la liste des actions possibles
	$sql_action_possible = mysql_query("SELECT actions.id_action,lib_action
										FROM actions_possibles, objets, actions
										WHERE objets.id_type_objet_logique = actions_possibles.id_type_objet
										AND actions_possibles.id_etat_cible <> objets.id_etat
										AND actions_possibles.id_action = actions.id_action
										AND objets.id_objet_logique = '".$_GET["id"]."'") or die (mysql_error());
	while(list($id_action, $lib_action)=mysql_fetch_array($sql_action_possible)){
		echo "<p><a href='#' onclick=\"lancer_action('".$_GET["id"]."','".$id_action."');\">$lib_action</a></p>";
	}
}

function appel_macro_eventghost($nom){
 
	$ch = curl_init();

	// URL d'appel
	curl_setopt($ch, CURLOPT_URL, "http://${EVENTGHOST_SERVER_URL}/?".$nom);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	// on veut que le retour soit transmis en tant que variable et affiché directement
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

	// execution
	$retour = curl_exec($ch);
	// on se fiche du retour, on détruit
	unset($retour);

	// close cURL resource, and free up system resources
	curl_close($ch);

}



?>