<?php

require_once ("../connexion.php");

switch($ask){

	case "get_etat_from_selected_object" : get_etat_from_selected_object(); break;
	case "get_action_for_selected_object" : get_action_for_selected_object(); break;
	case "ajout_action" : ajout_action(); break;
	case "add_action_systeme" : add_action_systeme(); break;
	case "ajout_notification" : ajout_notification(); break;
	case "ajout_condition" : ajout_condition(); break;
}


function get_etat_from_selected_object(){

	$sql = mysql_query("SELECT etats.id_etat, lib_etat
						FROM etats,etats_possibles,objets
						WHERE etats.id_etat=etats_possibles.id_etat
						AND objets.id_type_objet_logique = etats_possibles.id_type_objet
						AND objets.id_objet_logique ='".$_POST["id_objet"]."'
						ORDER BY lib_etat");
	while(list($id_etat,$lib_etat) = mysql_fetch_array($sql)){
		echo '<option value="'.$id_etat.'">'.$lib_etat.'</option>';
	}
}

function get_action_for_selected_object(){

	$sql = mysql_query("SELECT actions.id_action, lib_action
						FROM actions,actions_possibles,objets
						WHERE actions.id_action=actions_possibles.id_action
						AND objets.id_type_objet_logique = actions_possibles.id_type_objet
						AND objets.id_objet_logique ='".$_POST["id_objet"]."'
						ORDER BY lib_action") or die (mysql_error());
	while(list($id_action,$lib_action) = mysql_fetch_array($sql)){
		echo '<option value="'.$id_action.'">'.$lib_action.'</option>';
	}
}

function ajout_condition(){

	$id=$_POST["nb"];

	echo '
	<div class="ui-body ui-body-b" style="margin:5px;" id="div_condition_'.$id.'">
	<div data-role="fieldcontain">
		<fieldset>
			<label for="id_objet_condition_'.$id.'">Objet</label>
			<select name="id_objet_condition['.$id.']" id="id_objet_condition_'.$id.'" onchange="get_etat_from_selected_object(this.value,\'id_etat_condition_'.$id.'\');"> ';
			
		$sql = mysql_query("SELECT id_objet_logique, nom_objet
							FROM objets
							ORDER BY nom_objet");
		while(list($id_objet_logique,$nom_objet) = mysql_fetch_array($sql)){
			echo '<option value="'.$id_objet_logique.'">'.$nom_objet.'</option>';
		}
			
		echo	'</select>
			<label for="id_etat_condition_'.$id.'">Etat</label>
			<select name="id_etat_condition['.$id.']" id="id_etat_condition_'.$id.'"> 
			
			</select>
			<a href="#" onClick="remove_condition(\''.$id.'\');" data-role="button" data-icon="minus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Supprimer cette condition</a>
		</fieldset>
	</div>
	</div>';

}

function ajout_action(){

	$id=$_POST["nb"];

	echo '
	<div class="ui-body ui-body-b" style="margin:5px;" id="div_action_'.$id.'">
	<div data-role="fieldcontain">
		<fieldset>
			<label for="id_objet_cible_'.$id.'">Objet déclencheur</label>
			<select name="id_objet_cible['.$id.']" id="id_objet_cible_'.$id.'" onchange="get_action_for_selected_object(this.value,\'id_action_'.$id.'\');"> ';
			
		$sql = mysql_query("SELECT id_objet_logique, nom_objet
							FROM objets
							ORDER BY nom_objet");
		while(list($id_objet_logique,$nom_objet) = mysql_fetch_array($sql)){
			echo '<option value="'.$id_objet_logique.'">'.$nom_objet.'</option>';
		}
			
		echo	'</select>
			<label for="id_action_'.$id.'">Action</label>
			<select name="id_action['.$id.']" id="id_action_'.$id.'"> 
			
			</select>
			<a href="#" onClick="remove_action(\''.$id.'\');" data-role="button" data-icon="minus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Supprimer cette Action</a>
		</fieldset>
	</div>
	</div>';

}

function add_action_systeme(){

	$id=$_POST["nb"];

	echo '
	<div class="ui-body ui-body-b" style="margin:5px;" id="div_action_'.$id.'">
		<div data-role="fieldcontain">
			<input type="hidden" name="id_objet_cible['.$id.']"  value="0"> 
			<input type="hidden" name="id_action['.$id.']" value="-2"> 
			<fieldset>
				<div data-role="fieldcontain">
					<label for="actions_systeme_'.$id.'">Type d\'action</label>
					<select name="actions_systeme['.$id.']" id="actions_systeme_'.$id.'" onchange="get_param_for_sysaction(this.value,'.$id.');">
						<option value="wait">Attendre</option>
						<option value="script">Lancement d\'un script système</option>
					</select>	
				</div>
				<div data-role="fieldcontain" id="field_wait_'.$id.'" class="fields_'.$id.'">
					<label for="param1_'.$id.'">Durée d\'attente en seconde</label>
					<input type="text" id="param1_'.$id.'" name="param1['.$id.']" value="" />
				</div>
				<div data-role="fieldcontain" id="field_script_'.$id.'" class="fields_'.$id.'" style="display:none;">
					<label for="param2_'.$id.'">Nom du script</label>
					<select id="param2_'.$id.'" name="param2['.$id.']">';
					echo "<option value='0'>Selectionnez</option>";
					if ($repertoire_scripts = opendir('../scripts_utilisateur')) {
						while (false !== ($entry = readdir($repertoire_scripts))) {
							if($entry!="." && $entry!="..")
							echo "<option value='".$entry."'>".$entry."</option>";
						}
						closedir($repertoire_scripts);
					}			
					echo	'</select>
				</div>
				<a href="#" onClick="remove_action(\''.$id.'\');" data-role="button" data-icon="minus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Supprimer cette Action</a>
			</fieldset>
		</div>
	</div>';

}

function ajout_notification(){

	$id=$_POST["nb"];

	echo '
	<div class="ui-body ui-body-b" style="margin:5px;" id="div_action_'.$id.'">
	<div data-role="fieldcontain">
		<input type="hidden" name="id_objet_cible['.$id.']" id="id_objet_cible_'.$id.'" value="0"> 

		<fieldset>
			<label for="id_action_'.$id.'">Type de notification</label>
			<select name="id_action['.$id.']" id="id_action_'.$id.'"> ';
			
		$sql = mysql_query("SELECT id_action, lib_action
							FROM actions
							WHERE id_action <=1
							AND id_action >-1
							ORDER BY id_action");
		while(list($id_action,$lib_action) = mysql_fetch_array($sql)){
			echo '<option value="'.$id_action.'">'.$lib_action.'</option>';
		}
			
	echo	'</select>
			<label for="id_notif_'.$id.'">Action</label>
			<select name="id_notif['.$id.']" id="id_notif_'.$id.'"> ';
			
				$sql = mysql_query("SELECT id_notif, type_notif, objet_notif
									FROM actions_notification
									ORDER BY type_notif, objet_notif");
				while(list($id_notif,$type_notif,$objet_notif) = mysql_fetch_array($sql)){
					echo '<option value="'.$id_notif.'">'.$type_notif.' - '.$objet_notif.'</option>';
				}
	echo	'</select>
			<a href="#" onClick="remove_action(\''.$id.'\');" data-role="button" data-icon="minus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Supprimer cette Notification</a>
		</fieldset>
	</div>
	</div>';

}



?>