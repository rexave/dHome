 <?php

require_once("../connexion.php");

if(isset($_GET["id"])) $id_objet_physique=$_GET["id"]; else $id_objet_physique="";
if(isset($_GET["valeur1"])) $valeur1=$_GET["valeur1"]; else $valeur1="";
if(isset($_GET["valeur2"])) $valeur2=$_GET["valeur2"]; else $valeur2="";
if(isset($_GET["valeur3"])) $valeur3=$_GET["valeur3"]; else $valeur3="";
if(isset($_GET["valeur4"])) $valeur4=$_GET["valeur4"]; else $valeur4="";
 
 echo " <p>id_objet_physique $id_objet_physique</p>";
 echo " <p>valeur1 $valeur1</p>";
 echo " <p>valeur2 $valeur2</p>";
 echo " <p>valeur3 $valeur3</p>";
 echo " <p>valeur4 $valeur4</p>";
 
 if($id_objet_physique==""){
     echo "Pas d'id physique renvoyé";
     
 }else{
	//liste des scenarios associés à cette action
	$sql = mysql_query("SELECT id_scenario
                        FROM objets, scenario, etats_possibles
						WHERE objets.id_objet_physique = '".$id_objet_physique."'
						AND objets.id_objet_logique = scenario.id_objet_source
						AND objets.id_type_objet_logique = etats_possibles.id_type_objet
						AND scenario.id_etat_source = etats_possibles.id_etat
						AND etats_possibles.valeur1='".$valeur1."'
						AND etats_possibles.valeur2='".$valeur2."'
						AND etats_possibles.valeur3='".$valeur3."'
						AND etats_possibles.valeur4='".$valeur4."'
						") or die (mysql_error());
	while(list($id_scenario)=mysql_fetch_array($sql)){
		echo "<p>id_scenario - $id_scenario</p>";
		//on regarde toutes les conditions du scenario courant
		$sql_condition = mysql_query("	SELECT scenario_conditions.id_etat, objets.id_etat
										FROM scenario_conditions, objets
										WHERE id_scenario = '".$id_scenario."'
										AND scenario_conditions.id_objet = objets.id_objet_logique") or die (mysql_error());
		while(list($id_etat_condition,$id_etat_objet)=mysql_fetch_array($sql_condition)){
			//si au moins une condition n'est pas satisfaite, on break cette boucle, et on continue sur la boucle suivante
			//on peut avoir un autre scenario qui, lui, aurait ses conditions satisfaites
			$ce_scenario_ne_valide_pas_toutes_les_contitions=false;
			
			if($id_etat_condition != $id_etat_objet){
				$ce_scenario_ne_valide_pas_toutes_les_contitions=true;
				break;
			}
		}
		if ($ce_scenario_ne_valide_pas_toutes_les_contitions) continue;
	
		$sql_action = mysql_query("	SELECT id_eventGhost, scenario_actions.id_objet, scenario_actions.id_action,id_FK
									FROM scenario_actions LEFT OUTER JOIN actions_definies
										on scenario_actions.id_action = actions_definies.id_action
										and scenario_actions.id_objet = actions_definies.id_objet
									WHERE id_scenario = '".$id_scenario."'
									ORDER BY id_ordre
									") or die (mysql_error());
		$au_moins_un_scenario_a_ete_execute=false;
		while(list($id_eventGhost,$id_objet_cible,$id_action,$id_FK)=mysql_fetch_array($sql_action)){
			if($id_objet_cible == 0){
				//c'est une action du moteur dHome
				if($id_action==-2){
					//on autorise le sript a tourner pendant 1h
					set_time_limit(3600);
					//on ignore le timeout client
					ignore_user_abort(1);
					//c'est une action systeme
					$sql_sys = mysql_query("SELECT type_action,param1,param2
											FROM actions_systeme
											WHERE id_action='".$id_FK."'") or die(mysql_error());
					list($type_action,$param1,$param2)=mysql_fetch_array($sql_sys);
					
					if($type_action=="wait") sleep($param1);
					if($type_action=="script") include "../scripts_utilisateur/".$param2; 

				}
				else{
					//c'est une nootification
					$sql_notif = mysql_query("	SELECT type_notif, objet_notif, message_notif,destinataire_mail
												FROM actions_notification 
												WHERE id_notif = '".$id_FK."'
												") or die (mysql_error());
					list($type_notif, $objet_notif, $message_notif,$destinataire_mail)=mysql_fetch_array($sql_notif);
					switch ($type_notif){
						case "mail" : action_mail($objet_notif, $message_notif,$destinataire_mail);break;
						case "karotz" : action_karotz($message_notif);break;
						case "push" : action_push($message_notif);break;
						case "nabaztag" : action_nabaztag($id_FK,$message_notif);break;
					}
				}
			}else{
				//c'est une action executée par eventGhost
				echo "<p>Appel eventghost $id_eventGhost</p>";
				//on appel l'id eventghost distant
				appel_macro_eventghost($id_eventGhost);
				
				//on recupere l'etat cible de l'objet apres l'action
				$sql_etat_cible = mysql_query("	SELECT id_etat_cible
												FROM objets, actions_possibles
												WHERE objets.id_type_objet_logique = actions_possibles.id_type_objet
												AND objets.id_objet_logique = '".$id_objet_cible."'
												AND actions_possibles.id_action = '".$id_action."'")  or die (mysql_error());
				list($id_etat_cible) = mysql_fetch_array($sql_etat_cible);
				echo "UPDATE objets SET id_etat='".$id_etat_cible."' WHERE id_objet_logique = '".$id_objet_cible."'";
				//on met l'objet impacté dans son nouvel état
				$sql_update = mysql_query("UPDATE objets SET id_etat='".$id_etat_cible."' WHERE id_objet_logique = '".$id_objet_cible."'")  or die (mysql_error());
			}
			$au_moins_un_scenario_a_ete_execute=true;
			usleep (100);
		}
		if($au_moins_un_scenario_a_ete_execute){
			//toutes les actions de ce scenario sont terminées
			//il est interdit de faire plusieurs scenarios ayant les memes conditions
			//il n'y a plus rien à faire
			echo "ok";
			break;
		}
	}	

}
 
 
function appel_macro_eventghost($nom){
 
	$ch = curl_init();

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, "http://${EVENTGHOST_SERVER_URL}/?".$nom);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	// grab URL and pass it to the browser
	curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);

}

function action_mail($objet_notif, $message_notif,$destinataire_mail){

	$headers  = 'MIME-Version: 1.0' . "\r\n";
	$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
	$headers .= 'From: ${DHOME_MAIL}' . "\r\n" ;
	$headers .='Reply-To: ${DHOME_MAIL}' . "\r\n" ;
	$headers .='X-Mailer: PHP/' . phpversion();

	mail($destinataire_mail , $objet_notif , "<p>" . stripslashes($message_notif) ." </p><p> Message généré par dHome à " . date("r") . "</p>", $headers);

}

function action_karotz($message){
 
	$ch = curl_init();

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, "http://api.pushingbox.com/pushingbox?devid=${ID_PUSHINGBOX}&message=".urlencode($message));
	curl_setopt($ch, CURLOPT_HEADER, 0);

	// grab URL and pass it to the browser
	curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);

}

function action_push($message){
 
	$ch = curl_init();

	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, "http://api.pushingbox.com/pushingbox?devid=${ID_PUSHINGBOX}&message=".urlencode($message));
	curl_setopt($ch, CURLOPT_HEADER, 0);

	// grab URL and pass it to the browser
	curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);

}

 function action_nabaztag($id_notification,$message){
 
	//recuperation des parametres serveur
	$param_sql = mysql_query("	SELECT id_param, val_param
								FROM parametres");
	while(list($id_param,$val_param)=mysql_fetch_array($param_sql)){
		$parametres[$id_param] = $val_param;
	}
	
	//recuperation des identifiants internes dhome des nabaztag à appeler
	$id_nab_sql = mysql_query("	SELECT ids_nab
								FROM actions_notification
								WHERE id_notif=".$id_notification);
	list($ids_nab)=mysql_fetch_array($id_nab_sql);
	//on met en tableau cette liste
	$ids_nab_tab = split(',',$ids_nab);

	if(count($ids_nab_tab)>0)
		foreach($ids_nab_tab as $id_nab){
			if($id_nab == "") continue;
			//recuperation des adresses MAC des nabaztag appelés
			$nab_sql = mysql_query("SELECT serial_nab
									FROM nabaztag
									WHERE id_nab='".$id_nab."'");
			list($serial_nab)=mysql_fetch_array($nab_sql);
			$serial_nabs[] = $serial_nab;
		}
	
	
	//construction de l'url d'authentification
	$url_auth = "http://" . $parametres["openjabnab_url"] . "/ojn_api/accounts/auth?login=" . $parametres["openjabnab_login"] . "&pass=" . $parametres["openjabnab_pass"];
	
	echo "<p>Appel de : " . $url_auth . "</p>";
	
	//appel curl
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url_auth);
	curl_setopt($ch, CURLOPT_HEADER, 0);
	//on veut le resultat en chaine retournee par curl_exec
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
	//l'api renvoie le token dans du XML
	$reponse_XML = curl_exec($ch);
	
	//on parse le XML en XPATH
	$xml = new SimpleXMLElement($reponse_XML);
	list( $token_api) = $xml->xpath('/api/value');
	
		echo "<p>token_api : [" . $token_api . "]</p>";
	var_dump($token_api);
	curl_close($ch);
	
	//on fait nos appels API avec notre token frais
	foreach ($serial_nabs as $serial_nab){
		//url pour l'API de text to speech
		$url_tts = "http://" . $parametres["openjabnab_url"] . "/ojn_api/bunny/". $serial_nab ."/tts/say?text=" . urlencode($message) . "&token=" . $token_api ;
			echo "<p>Appel de : " . $url_tts . "</p>";

		appel_curl($url_tts);
	}


}


function appel_curl($url){

	$ch = curl_init();
	// set URL and other appropriate options
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_HEADER, 0);

	// grab URL and pass it to the browser
	curl_exec($ch);

	// close cURL resource, and free up system resources
	curl_close($ch);

}


 
 ?>