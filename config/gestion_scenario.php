<?php

require_once ("../includes/global_ui.php");
require_once ("../connexion.php");

if(!isset($_GET["a"])) $a = "home"; else $a = $_GET["a"];

switch($a){

	case "home" : home(); break;
	case "add" : add(); break;
	case "add_post" : add_post(); break;
	case "edit" : edit(); break;
	case "edit_post" : edit_post(); break;
	case "suppr_post" : suppr_post(); break;
	default: echo "Erreur de redirection";
}


function home(){
	entete_page("Scénarios - Home" , "../");

?>

	<div data-role="content">
		<div class="content-primary">

			<ul data-role="listview" data-inset="true">
				<li data-theme="a"><a href="?a=add">Créer un scenario</a></li>
				<?php
					$sql = mysql_query("SELECT lib_scenario,id_scenario from scenario ORDER BY lib_scenario");
					while(list($lib_scenario,$id_scenario)=mysql_fetch_array($sql)){
						echo '<li><a href="?a=edit&&id='.$id_scenario.'">'.$lib_scenario.'</a></li>';
					}
					
				?>
			</ul>

		</div>
	</div><!-- /content -->

<?php
}

function add(){
	entete_page("Scénario - Création" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=add_post" method="post">

				<div data-role="fieldcontain">
					<label for="lib_scenario">Nom du scenario</label>
					<input type="text" name="lib_scenario" id="lib_scenario" value=""/>
				</div>
				<div data-role="fieldcontain">
					<label for="id_objet_source">Objet déclencheur</label>
					<select name="id_objet_source" id="id_objet_source" onchange="get_etat_from_selected_object(this.value);"> 
						<?php
						$sql = mysql_query("SELECT id_objet_logique,nom_objet from objets ORDER BY nom_objet ");
						while(list($id_objet_logique,$nom_objet)=mysql_fetch_array($sql)){
							echo '<option value="'.$id_objet_logique.'">'.$nom_objet.'</option>';
						}
						?>
						
					</select>
				</div>
				<div data-role="fieldcontain">
					<label for="id_etat_source">Etat de l'objet déclencheur</label>
					<select name="id_etat_source" id="id_etat_source">
						
					</select>
				</div>
				
				<div class="ui-body ui-body-b">
					<fieldset class="ui-grid-a">
						<div class="ui-block-a"><button type="button" data-theme="d" onClick="self.location='?a=home'">Cancel</button></div>
						<div class="ui-block-b"><button type="submit" data-theme="a">Submit</button></div>
					</fieldset>
				</div>
			</form>
		</div>
	</div><!-- /content -->
	
	<script type="text/javascript">
		function get_etat_from_selected_object(id) {
			document.getElementById('id_etat_source').disabled = true;

			$.post("gestion_scenario_ajax.php?ask=get_etat_from_selected_object" , {
				id_objet:id
			}, function(data){
				$("#id_etat_source").html(data);
			});
			
			document.getElementById('id_etat_source').disabled = false;
		}
		
	</script>


<?php
}


function add_post(){
	entete_page("Configuration des scénarios - Ajouter un scénario en base", "../");
	//verification parametres renvoyés
	if($_POST["lib_scenario"] == ""){ echo "Error : pas de \"Libellé de scénario\" renvoyé !";}
	else{
		$sql = mysql_query ("	INSERT INTO scenario
								(id_scenario,id_objet_source,id_etat_source,lib_scenario) VALUES
								('','".$_POST["id_objet_source"]."','".$_POST["id_etat_source"]."','".$_POST["lib_scenario"]."')") or die(mysql_error());
		echo "<p>Scénario ajouté</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}


function edit(){

	entete_page("Configuration des scénarios - Modifier un scénario", "../");

	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query("SELECT id_objet_source,id_etat_source,lib_scenario from scenario WHERE id_scenario='".$_GET["id"]."'");
	list($id_objet_source,$id_etat_source,$lib_scenario)=mysql_fetch_array($sql);
	
?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=edit_post" method="post">
				<input type="hidden" name="id_scenario" id="id_scenario" value="<?php echo $_GET["id"];?>"  />
				
				<div data-role="fieldcontain">
					<label for="lib_scenario">Nom du scenario</label>
					<input type="text" name="lib_scenario" id="lib_scenario" value="<?php echo $lib_scenario;?>" />
				</div>
				<div data-role="fieldcontain">
					<label for="id_objet_source">Objet déclencheur</label>
					<select name="id_objet_source" id="id_objet_source" onchange="get_etat_from_selected_object(this.value,'id_etat_source');"> 
						<?php
						$sql = mysql_query("SELECT id_objet_logique,nom_objet from objets ORDER BY nom_objet ");
						while(list($id_objet_logique,$nom_objet)=mysql_fetch_array($sql)){
							if($id_objet_source == $id_objet_logique)
								echo '<option value="'.$id_objet_logique.'" selected="selected">'.$nom_objet.'</option>';
							else
								echo '<option value="'.$id_objet_logique.'">'.$nom_objet.'</option>';
						}
						?>
						
					</select>
				</div>
				<div data-role="fieldcontain">
					<label for="id_etat_source">Etat de l'objet déclencheur</label>
					<select name="id_etat_source" id="id_etat_source">
					<?php
						$sql = mysql_query("SELECT etats.id_etat, lib_etat
											FROM etats,etats_possibles,objets
											WHERE etats.id_etat=etats_possibles.id_etat
											AND objets.id_type_objet_logique = etats_possibles.id_type_objet
											AND objets.id_objet_logique ='".$id_objet_source."'
											ORDER BY lib_etat");
						while(list($id_etat_sql,$lib_etat) = mysql_fetch_array($sql)){
							if($id_etat_source == $id_etat_sql)
								echo '<option value="'.$id_etat_sql.'" selected="selected">'.$lib_etat.'</option>';
							else
								echo '<option value="'.$id_etat_sql.'">'.$lib_etat.'</option>';
						}
					?>
					</select>
				</div>
				<div id="form_conditions" class="ui-body ui-body-d">
					<div data-role="fieldcontain">
						<h3>Conditions</h3>
					<?php

					$sql_condition = mysql_query("SELECT id_objet, id_etat
													FROM scenario_conditions
													WHERE id_scenario='".$_GET["id"]."'
													ORDER BY id_objet ASC") or die (mysql_error());
					echo '	<input type="hidden" value="'.mysql_num_rows($sql_condition).'" id="nb_condition" name="nb_condition"/>';
					$i=0;
					
					while(list($id_objet_sql, $id_etat_sql)=mysql_fetch_array($sql_condition)){
						echo '
						<div class="ui-body ui-body-b" style="margin:5px;" id="div_condition_'.$i.'">
						<div data-role="fieldcontain">
							<fieldset>
								<label for="id_objet_condition_'.$i.'">Objet déclencheur</label>
								<select name="id_objet_condition['.$i.']" id="id_objet_condition_'.$i.'" onchange="get_etat_from_selected_object(this.value,\'id_etat_condition_'.$i.'\');"> ';
								
							$sql = mysql_query("SELECT id_objet_logique, nom_objet
												FROM objets
												ORDER BY nom_objet") or die (mysql_error());
							while(list($id_objet_logique,$nom_objet) = mysql_fetch_array($sql)){
								if($id_objet_logique == $id_objet_sql)
									echo '<option value="'.$id_objet_logique.'" selected="selected">'.$nom_objet.'</option>';
								else
									echo '<option value="'.$id_objet_logique.'">'.$nom_objet.'</option>';
							}
								
							echo	'</select>
								<label for="id_etat_condition_'.$i.'">Etat</label>
								<select name="id_etat_condition['.$i.']" id="id_etat_condition_'.$i.'"> ';
									$sql = mysql_query("SELECT etats.id_etat, lib_etat
														FROM etats,etats_possibles,objets
														WHERE etats.id_etat=etats_possibles.id_etat
														AND objets.id_type_objet_logique = etats_possibles.id_type_objet
														AND objets.id_objet_logique ='".$id_objet_sql."'
														ORDER BY lib_etat") or die (mysql_error());
									while(list($id_etat,$lib_etat) = mysql_fetch_array($sql)){
										if($id_etat == $id_etat_sql)
											echo '<option value="'.$id_etat.'" selected="selected">'.$lib_etat.'</option>';
										else
											echo '<option value="'.$id_etat.'">'.$lib_etat.'</option>';
									}
							echo'</select>
							<a href="#" onClick="remove_condition(\''.$i.'\');" data-role="button" data-icon="minus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Supprimer cette condition</a>
							</fieldset>
						</div>
						</div>';
						$i++;
					}
					?>
						<div style="padding-bottom:5px;">
						<a href="#" onClick="add_condition();" data-role="button" data-icon="plus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Ajouter une condition</a>
						</div>
					</div>
				</div>
				<div id="form_actions" class="ui-body ui-body-d">
					<div data-role="fieldcontain">
						<h3>Actions</h3>
					<?php
					//on recupere le max de id_ordre enregistré
					//on peut en avoir "grillé" en cas de suppression d'action
					$sql_max_id_ordre_actions = mysql_query("SELECT MAX(id_ordre)
															FROM scenario_actions
															WHERE id_scenario='".$_GET["id"]."'");
					list($nb)=mysql_fetch_array($sql_max_id_ordre_actions);
					if($nb=="") $nb=1;
					echo '	<input type="hidden" value="'.$nb.'" id="nb_ordre" name="nb_ordre"/>';

					$sql_actions = mysql_query("SELECT id_ordre, id_action, id_objet, id_FK
												FROM scenario_actions
												WHERE id_scenario='".$_GET["id"]."'
												ORDER BY id_ordre ASC");
					
					while(list($id_ordre_sql, $id_action_sql, $id_objet_sql, $id_FK_sql)=mysql_fetch_array($sql_actions)){
						if($id_objet_sql == 0){
							$id=$id_ordre_sql;
							//on est dans une edition sur l'objet Core : notification ou action systeme
							//on affiche le formulaire concerné
							if($id_action_sql==-2){
								//formulaire d'action systeme
								//on récupere les infos d'actions_systeme
								
								$sql_sys = mysql_query("SELECT type_action,param1,param2
														FROM actions_systeme
														WHERE id_action='".$id_FK_sql."'") or die(mysql_error());
								list($type_action,$param1,$param2)=mysql_fetch_array($sql_sys);

								echo '
								<div class="ui-body ui-body-b" style="margin:5px;" id="div_action_'.$id.'">
									<div data-role="fieldcontain">
										<input type="hidden" name="id_objet_cible['.$id.']"  value="0"> 
										<input type="hidden" name="id_action['.$id.']" value="-2"> 
										<fieldset>
											<div data-role="fieldcontain">
												<label for="actions_systeme_'.$id.'">Type d\'action</label>
												<select name="actions_systeme['.$id.']" id="actions_systeme_'.$id.'" onchange="get_param_for_sysaction(this.value,'.$id.');">
													<option value="wait" '; if($type_action=="wait") echo 'selected="selected"'; echo'>Attendre</option>
													<option value="script" '; if($type_action=="script") echo 'selected="selected"'; echo'>Lancement d\'un script système</option>
												</select>	
											</div>
											<div data-role="fieldcontain" id="field_wait_'.$id.'" class="fields_'.$id.'" '; if($type_action=="script") echo 'style="display:none;"'; echo'>
												<label for="param1_'.$id.'">Durée d\'attente en seconde</label>
												<input type="text" id="param1_'.$id.'" name="param1['.$id.']" value="'.$param1.'" />
											</div>
											<div data-role="fieldcontain" id="field_script_'.$id.'" class="fields_'.$id.'"'; if($type_action=="wait") echo 'style="display:none;"'; echo'>
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
							
							}else{
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
										if($id_action == $id_action_sql)
											echo '<option value="'.$id_action.'" selected="selected">'.$lib_action.'</option>';
										else
											echo '<option value="'.$id_action.'">'.$lib_action.'</option>';
									}
										
								echo	'</select>
										<label for="id_notif_'.$id.'">Action</label>
										<select name="id_notif['.$id.']" id="id_notif_'.$id.'"> ';
										
											$sql = mysql_query("SELECT id_notif, type_notif, objet_notif
																FROM actions_notification
																ORDER BY type_notif, objet_notif");
											while(list($id_notif,$type_notif,$objet_notif) = mysql_fetch_array($sql)){
												if($id_notif == $id_FK_sql)
													echo '<option value="'.$id_notif.'" selected="selected">'.$type_notif.' - '.$objet_notif.'</option>';
												else
													echo '<option value="'.$id_notif.'">'.$type_notif.' - '.$objet_notif.'</option>';
											}
								echo	'</select>
										<a href="#" onClick="remove_action(\''.$id.'\');" data-role="button" data-icon="minus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Supprimer cette Notification</a>
									</fieldset>
								</div>
								</div>';
							}
						}else{
							//on est dans une action normal
							// on affiche le formulaire classique
							echo '
							<div class="ui-body ui-body-b" style="margin:5px;" id="div_action_'.$id_ordre_sql.'">
							<div data-role="fieldcontain">
								<fieldset>
									<label for="id_objet_cible_'.$id_ordre_sql.'">Objet déclencheur</label>
									<select name="id_objet_cible['.$id_ordre_sql.']" id="id_objet_cible_'.$id_ordre_sql.'" onchange="get_action_for_selected_object(this.value,\'id_action_'.$id_ordre_sql.'\');"> ';
									
								$sql = mysql_query("SELECT id_objet_logique, nom_objet
													FROM objets
													ORDER BY nom_objet");
								while(list($id_objet_logique,$nom_objet) = mysql_fetch_array($sql)){
									if($id_objet_logique == $id_objet_sql)
										echo '<option value="'.$id_objet_logique.'" selected="selected">'.$nom_objet.'</option>';
									else
										echo '<option value="'.$id_objet_logique.'">'.$nom_objet.'</option>';
								}
									
								echo	'</select>
									<label for="id_action_'.$id_ordre_sql.'">Action</label>
									<select name="id_action['.$id_ordre_sql.']" id="id_action_'.$id_ordre_sql.'"> ';
										$sql = mysql_query("SELECT actions.id_action, lib_action
															FROM actions,actions_possibles,objets
															WHERE actions.id_action=actions_possibles.id_action
															AND objets.id_type_objet_logique = actions_possibles.id_type_objet
															AND objets.id_objet_logique ='".$id_objet_sql."'
															ORDER BY lib_action") or die (mysql_error());
										while(list($id_action,$lib_action) = mysql_fetch_array($sql)){
											if($id_action == $id_action_sql)
												echo '<option value="'.$id_action.'" selected="selected">'.$lib_action.'</option>';
											else
												echo '<option value="'.$id_action.'">'.$lib_action.'</option>';
										}
								echo'</select>
								<a href="#" onClick="remove_action(\''.$id_ordre_sql.'\');" data-role="button" data-icon="minus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Supprimer cette Action</a>
								</fieldset>
							</div>
							</div>';
						}
					}
					?>
						<div style="padding-bottom:5px;">
							<a href="#" onClick="add_action();" data-role="button" data-icon="plus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Ajouter une action Objet</a>
							<a href="#" onClick="add_action_systeme();" data-role="button" data-icon="plus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Ajouter une action Système</a>
							<a href="#" onClick="add_notification();" data-role="button" data-icon="plus" data-iconpos="left" data-mini="true" data-inline="true" data-theme="b">Ajouter une action Notification</a>
						</div>
					</div>
				</div>
				
				<div class="ui-body ui-body-b">
					<fieldset class="ui-grid-a">
						<div class="ui-block-a"><button type="button" data-theme="d" onClick="self.location='?a=home'">Cancel</button></div>
						<div class="ui-block-b"><button type="submit" data-theme="a">Submit</button></div>
					</fieldset>
				</div>
				<div class="ui-body ui-body-d">
					<a href="#" data-role="button" data-icon="delete" onclick="if(confirm('Confirmer la suppression ?')){self.location='?a=suppr_post&id=<?php echo $_GET["id"];?>'} ">Delete</a>
				</div>
			</form>
		</div>
	</div><!-- /content -->
	
	<script type="text/javascript">
		function get_etat_from_selected_object(id,cible) {
			document.getElementById(cible).disabled = true;

			$.post("gestion_scenario_ajax.php?ask=get_etat_from_selected_object" , {
				id_objet:id
			}, function(data){
				$("#"+cible).html(data);
			});
			
			document.getElementById(cible).disabled = false;
		}
		
		function get_action_for_selected_object(id,cible) {
			document.getElementById(cible).disabled = true;

			$.post("gestion_scenario_ajax.php?ask=get_action_for_selected_object" , {
				id_objet:id
			}, function(data){
				$("#"+cible).html(data);
			});
			
			document.getElementById(cible).disabled = false;
		}
		
		function get_param_for_sysaction(sysaction,id) {
			
			$(".fields_"+id).hide();
			$("#field_"+sysaction+"_"+id).show();
			
		}
		
		function add_action() {
			nb=$("#nb_ordre").attr('value');
			nb=parseInt(nb)+1;
			$("#nb_ordre").attr('value',nb);

			$.post("gestion_scenario_ajax.php?ask=ajout_action" , {
				nb:nb
			}, function(data){
				$("#form_actions").append(data).trigger("create");
			});
		}
		
		function add_action_systeme() {
			nb=$("#nb_ordre").attr('value');
			nb=parseInt(nb)+1;
			$("#nb_ordre").attr('value',nb);

			$.post("gestion_scenario_ajax.php?ask=add_action_systeme" , {
				nb:nb
			}, function(data){
				$("#form_actions").append(data).trigger("create");
			});
		}
		
		function add_notification() {
			nb=$("#nb_ordre").attr('value');
			nb=parseInt(nb)+1;
			$("#nb_ordre").attr('value',nb);

			$.post("gestion_scenario_ajax.php?ask=ajout_notification" , {
				nb:nb
			}, function(data){
				$("#form_actions").append(data).trigger("create");
			});
		}
		
		function remove_action(id) {
			//on supprime simplement le div conteneur
			//l'id_ordre affecté est perdu
			//le nb_max ordre est conservé
			$("#div_action_"+id).remove();
		}
		
		function add_condition() {
			nb=$("#nb_condition").attr('value');
			nb=parseInt(nb)+1;
			$("#nb_condition").attr('value',nb);

			$.post("gestion_scenario_ajax.php?ask=ajout_condition" , {
				nb:nb
			}, function(data){
				$("#form_conditions").append(data).trigger("create");
			});
		}
		
		function remove_condition(id) {
			//on supprime simplement le div conteneur
			//l'id_ordre affecté est perdu
			//le nb_max ordre est conservé
			$("#div_condition_"+id).remove();
		}
		
	</script>

<?php
}


function edit_post(){
	entete_page("Configuration des types d'objet - Modifier un type d'objet en base ", "../");
	
	//verification parametres renvoyés
	if($_POST["lib_scenario"] == ""){ echo "Error : pas de \"Libellé de scénario\" renvoyé !";}
	else{
		$sql = mysql_query ("	UPDATE scenario
								SET id_objet_source='".$_POST["id_objet_source"]."',
								lib_scenario='".$_POST["lib_scenario"]."',
								id_etat_source='".$_POST["id_etat_source"]."'
								WHERE id_scenario='".$_POST["id_scenario"]."'") or die(mysql_error());
		echo "<p>Scénario modifié</p>";
		
		$sql=mysql_query("DELETE FROM scenario_conditions WHERE id_scenario='".$_POST["id_scenario"]."'") or die (mysql_error());
		$objets_condition = $_POST["id_objet_condition"];
		if(count($objets_condition)>0)
		foreach ($objets_condition as $id => $objet_condition){
			$sql=mysql_query("INSERT INTO scenario_conditions (id_scenario, id_objet, id_etat) VALUES 
			('".$_POST["id_scenario"]."','".$objet_condition."','".$_POST["id_etat_condition"][$id]."')") or die (mysql_error());
			echo "Condition $id ajoutée.<br/>";
		}
		
		$sql=mysql_query("DELETE FROM scenario_actions WHERE id_scenario='".$_POST["id_scenario"]."'") or die (mysql_error());
		$cibles = $_POST["id_objet_cible"];
		if(count($cibles)>0)
		foreach ($cibles as $no_ordre => $cible){
			//si cible = 0 alors on est sur une action Core, sinon action normale sur un objet 
			//action core ?
			if($cible==0){
				//est ce une notification ou une action système ?
				if(isset($_POST["id_notif"][$no_ordre])){
					//notification
					$id_FK=$_POST["id_notif"][$no_ordre];
				}else{
					//action systeme
					//on cree l'action systeme (qui n'est pas prédéfinie)
					$sql_sys = mysql_query("INSERT INTO actions_systeme (type_action,param1,param2) 
											VALUES ('".$_POST["actions_systeme"][$no_ordre]."','".$_POST["param1"][$no_ordre]."','".$_POST["param2"][$no_ordre]."')") or die(mysql_error());
					$id_FK = mysql_insert_id();
				}
			}
			$sql=mysql_query("INSERT INTO scenario_actions (id_scenario, id_ordre, id_action, id_objet, id_FK) VALUES 
			('".$_POST["id_scenario"]."','".$no_ordre."','".$_POST["id_action"][$no_ordre]."','".$cible."','".$id_FK."')") or die (mysql_error());
			echo "Action $no_ordre ajoutée.<br/>";
			
		}
	}
							
	echo "<a href='?a=home'>Retour</a>";
}

function suppr_post(){
	entete_page("Configuration des scenarios - Supprimer en base ", "../");
	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query ("	DELETE FROM scenario
							WHERE id_scenario ='".$_GET["id"]."'") or die(mysql_error());
	$sql = mysql_query ("	DELETE FROM scenario_conditions
							WHERE id_scenario ='".$_GET["id"]."'") or die(mysql_error());
	$sql = mysql_query ("	DELETE FROM scenario_actions
							WHERE id_scenario ='".$_GET["id"]."'") or die(mysql_error());
							
	echo "<p>Scenario supprimé<p>";
	echo "<a href='?a=home'>Retour</a>";

}



pied_page();

?>