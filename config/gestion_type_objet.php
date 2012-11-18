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
	entete_page("Configuration des types d'objet - Home" , "../");

?>

	<div data-role="content">
		<div class="content-primary">

			<ul data-role="listview" data-inset="true">
				<li data-theme="a"><a href="?a=add">Ajouter un type d'objet</a></li>
				<?php
					$sql = mysql_query("SELECT id_type_objet_logique,nom_type_objet from type_objet ORDER BY nom_type_objet");
					while(list($id_type_objet_logique,$nom_type_objet)=mysql_fetch_array($sql)){
						echo '<li><a href="gestion_type_objet.php?a=edit&&id='.$id_type_objet_logique.'">'.$nom_type_objet.'</a></li>';
					}
					
					//id_type_objet_logique,nom_type_objet,id_physique_type_objet,lib_valeur1,lib_valeur2,lib_valeur3,lib_valeur4
				?>
			</ul>

		</div>
	</div><!-- /content -->

<?php
}

function add(){
	entete_page("Configuration des types d'objet - Ajouter un type" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=add_post" method="post">

			<div data-role="fieldcontain">
				<label for="nom_type_objet">Libellé du type d'objet</label>
				<input type="text" name="nom_type_objet" id="nom_type_objet" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="lib_valeur1">Libellé Valeur1</label>
				<input type="text" name="lib_valeur1" id="lib_valeur1" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="lib_valeur2">Libellé Valeur2</label>
				<input type="text" name="lib_valeur2" id="lib_valeur2" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="lib_valeur3">Libellé Valeur3</label>
				<input type="text" name="lib_valeur3" id="lib_valeur3" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="lib_valeur4">Libellé Valeur4</label>
				<input type="text" name="lib_valeur4" id="lib_valeur4" value=""  />
			</div>
			
			<div class="ui-body ui-body-b">
				<fieldset class="ui-grid-a">
					<div class="ui-block-a"><button type="button" data-theme="d" onClick="self.location='?a=home'">Cancel</button></div>
					<div class="ui-block-b"><button type="submit" data-theme="a">Submit</button></div>
				</fieldset>
			</div>

		</div>
	</div><!-- /content -->

<?php
}


function add_post(){
	entete_page("Configuration des types d'objet - Ajouter un type d'objet en base", "../");
	//verification parametres renvoyés
	if($_POST["nom_type_objet"] == ""){ echo "Error : pas de \"Libellé du type d'objet\" renvoyé !";}
	else{
	
		$sql = mysql_query ("	INSERT INTO type_objet
								(id_type_objet_logique,nom_type_objet,lib_valeur1,lib_valeur2,lib_valeur3,lib_valeur4) VALUES
								('','".$_POST["nom_type_objet"]."','".$_POST["lib_valeur1"]."','".$_POST["lib_valeur2"]."','".$_POST["lib_valeur3"]."','".$_POST["lib_valeur4"]."')") or die(mysql_error());
								
		echo "<p>Type d'objet ajouté</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}

function edit(){

	entete_page("Configuration des types d'objet - Modifier un type d'objet ", "../");

	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query("SELECT nom_type_objet,lib_valeur1,lib_valeur2,lib_valeur3,lib_valeur4 from type_objet WHERE id_type_objet_logique='".$_GET["id"]."'");
	list($nom_type_objet,$lib_valeur1,$lib_valeur2,$lib_valeur3,$lib_valeur4)=mysql_fetch_array($sql);
	

?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=edit_post" method="post">
				<input type="hidden" name="id_type_objet_logique" id="id_type_objet_logique" value="<?php echo $_GET["id"];?>"  />
			<div data-role="fieldcontain">
				<label for="nom_type_objet">Libellé du type d'objet</label>
				<input type="text" name="nom_type_objet" id="nom_type_objet" value="<?php echo $nom_type_objet;?>"  />
			</div>
			<div data-role="fieldcontain">
				<label for="lib_valeur1">Libellé Valeur1</label>
				<input type="text" name="lib_valeur1" id="lib_valeur1" value="<?php echo $lib_valeur1;?>"  />
			</div>
			<div data-role="fieldcontain">
				<label for="lib_valeur2">Libellé Valeur2</label>
				<input type="text" name="lib_valeur2" id="lib_valeur2" value="<?php echo $lib_valeur2;?>"  />
			</div>
			<div data-role="fieldcontain">
				<label for="lib_valeur3">Libellé Valeur3</label>
				<input type="text" name="lib_valeur3" id="lib_valeur3" value="<?php echo $lib_valeur3;?>"  />
			</div>
			<div data-role="fieldcontain">
				<label for="lib_valeur4">Libellé Valeur4</label>
				<input type="text" name="lib_valeur4" id="lib_valeur4" value="<?php echo $lib_valeur4;?>"  />
			</div>
			<div  data-role="fieldcontain">
			 	<fieldset data-role="controlgroup">
					<legend>Etats possibles</legend>
					<?php
					$sql = mysql_query("SELECT etats.id_etat,etats.lib_etat,id_type_objet,valeur1,valeur2,valeur3,valeur4,nom_icone
										FROM etats LEFT OUTER JOIN etats_possibles 
											on etats.id_etat=etats_possibles.id_etat
											and etats_possibles.id_type_objet='".$_GET["id"]."'
										ORDER BY lib_etat");
					while(list($id_etat,$lib_etat,$check,$valeur1,$valeur2,$valeur3,$valeur4,$nom_icone) = mysql_fetch_array($sql)){
						echo '<fieldset data-role="controlgroup" data-type="horizontal">';
						if($check != "")
							echo '<input type="checkbox" name="etat['.$id_etat.']" value="'.$id_etat.'" id="etat_'.$id_etat.'" class="custom" checked onChange="afficher_valeurs_conditionnelles(\''.$id_etat.'\');"/>
								<label for="etat_'.$id_etat.'">'.$lib_etat.'</label>';
						else
							echo '<input type="checkbox" name="etat['.$id_etat.']" value="'.$id_etat.'" id="etat_'.$id_etat.'" class="custom" onChange="afficher_valeurs_conditionnelles(\''.$id_etat.'\');"/>
								<label for="etat_'.$id_etat.'">'.$lib_etat.'</label>';
						
						echo'</select>
						<select name="nom_icone['.$id_etat.']" id="nom_icone_'.$id_etat.'">';
						
						// create a handler for the directory
						$handler = opendir("../images");

						// open directory and walk through the filenames
						while ($file = readdir($handler)) {

						  // if file isn't this directory or its parent, add it to the results
						  if ($file != "." && $file != ".." && $file != "Thumbs.db"&& $file != "@eaDir") {
							if($file == $nom_icone)
								echo '<option value="'.$file.'" selected="selected">'.$file.'</option>';
							else
								echo '<option value="'.$file.'">'.$file.'</option>';
						  }

						}

						// tidy up: close the handler
						closedir($handler);
					
						echo'</select>
                        </fieldset>';
						
						if($check != "")
							echo '<div id="valeurs_conditionnelles_'.$id_etat.'" class="ui-grid-c">';
						else
							echo '<div id="valeurs_conditionnelles_'.$id_etat.'" class="ui-grid-c" style="display:none;">';
						echo'<div class="ui-block-a">
								<label for="val1_'.$id_etat.'">V1</label>
								<input type="text" id="val1_'.$id_etat.'" name="val1['.$id_etat.']" value="'.$valeur1.'"  />
							</div>
							<div class="ui-block-b">
								<label for="val2_'.$id_etat.'">V2</label>
								<input type="text" id="val2_'.$id_etat.'" name="val2['.$id_etat.']" value="'.$valeur2.'"  />
							</div>
							<div class="ui-block-c">
								<label for="val3_'.$id_etat.'">V3</label>
								<input type="text" id="val3_'.$id_etat.'" name="val3['.$id_etat.']" value="'.$valeur3.'"  />
							</div>
							<div class="ui-block-d">
								<label for="val4_'.$id_etat.'">V4</label>
								<input type="text" id="val4_'.$id_etat.'" name="val4['.$id_etat.']" value="'.$valeur4.'"  />
							</div>
						</div>
						';
						
					}
					
					?>
			    </fieldset>
			</div>
			<div data-role="fieldcontain">
			 	<h3>Actions possibles - Etat Cible</p>
					<?php
					$sql = mysql_query("SELECT actions.id_action,actions.lib_action,id_type_objet
										FROM actions LEFT OUTER JOIN actions_possibles 
											on actions.id_action=actions_possibles.id_action
											and actions_possibles.id_type_objet='".$_GET["id"]."'
										ORDER BY lib_action") or die (mysql_error());
					while(list($id_action,$lib_action,$check) = mysql_fetch_array($sql)){
                        echo '<fieldset data-role="controlgroup" data-type="horizontal">';
						if($check != "")
							echo '<input type="checkbox" name="action['.$id_action.']" value="'.$id_action.'" id="action_'.$id_action.'" class="custom" checked/>
								<label for="action_'.$id_action.'">'.$lib_action.'</label>';
						else
							echo '<input type="checkbox" name="action['.$id_action.']" value="'.$id_action.'" id="action_'.$id_action.'" class="custom" />
								<label for="action_'.$id_action.'">'.$lib_action.'</label>';
					
						echo'
								<select name="id_etat_cible['.$id_action.']" id="id_etat_cible_'.$id_action.'"> ';
									$sql_etat_cible = mysql_query("SELECT etats.id_etat, lib_etat, id_etat_cible
														FROM etats LEFT OUTER JOIN actions_possibles
                                                            on actions_possibles.id_etat_cible = etats.id_etat
                                                        AND actions_possibles.id_type_objet='".$_GET["id"]."'
                                                        AND actions_possibles.id_action = '".$id_action."'
														ORDER BY lib_etat") or die (mysql_error());
									while(list($id_etat,$lib_etat,$id_etat_cible) = mysql_fetch_array($sql_etat_cible)){
										if($id_etat == $id_etat_cible)
											echo '<option value="'.$id_etat.'" selected="selected">'.$lib_etat.'</option>';
										else
											echo '<option value="'.$id_etat.'">'.$lib_etat.'</option>';
									}
						echo'</select>
                        </fieldset>';

					}
					
					?>
			   <!-- </fieldset>-->
			</div>
			
			<div class="ui-body ui-body-a">
				<fieldset class="ui-grid-a">
					<div class="ui-block-a"><button type="button" data-theme="d" onClick="self.location='?a=home'">Cancel</button></div>
					<div class="ui-block-b"><button type="submit" data-theme="a">Submit</button></div>
					
				</fieldset>
			</div>
			<div class="ui-body ui-body-a">
				<a href="#" data-role="button" data-icon="delete" onclick="if(confirm('Confirmer la suppression ?')){self.location='?a=suppr_post&id=<?php echo $_GET["id"];?>'} ">Delete</a>
			</div>
			
	<script type="text/javascript">
		function afficher_valeurs_conditionnelles(id_etat) {

			if($("#etat_"+id_etat).attr('checked')){
				$("#valeurs_conditionnelles_"+id_etat).show();
			}
			else{
				$("#valeurs_conditionnelles_"+id_etat).hide();
			}
		}
		
	</script>

		</div>
	</div><!-- /content -->

<?php
}


function edit_post(){
	entete_page("Configuration des types d'objet - Modifier un type d'objet en base ", "../");

	//verification parametres renvoyés
	if($_POST["id_type_objet_logique"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	//maj du type d'objet
	$sql = mysql_query ("	UPDATE type_objet
							SET nom_type_objet='".$_POST["nom_type_objet"]."',
							lib_valeur1='".$_POST["lib_valeur1"]."',
							lib_valeur2='".$_POST["lib_valeur2"]."',
							lib_valeur3='".$_POST["lib_valeur3"]."',
							lib_valeur4='".$_POST["lib_valeur4"]."'
							WHERE id_type_objet_logique ='".$_POST["id_type_objet_logique"]."'") or die(mysql_error());
	
	//suppression des etats possibles
	$sql=mysql_query("DELETE FROM etats_possibles WHERE id_type_objet='".$_POST["id_type_objet_logique"]."'");
	$etats = $_POST["etat"];
	if(count($etats)>0)
	foreach ($etats as $etat){
		$sql=mysql_query("INSERT INTO etats_possibles (id_type_objet, id_etat,valeur1, valeur2, valeur3, valeur4,nom_icone) 
		VALUES (
		'".$_POST["id_type_objet_logique"]."',
		'".$etat."',
		'".$_POST["val1"][$etat]."',
		'".$_POST["val2"][$etat]."',
		'".$_POST["val3"][$etat]."',
		'".$_POST["val4"][$etat]."',
		'".$_POST["nom_icone"][$etat]."'
		)") or die (mysql_error());

	}
	//suppression des actions possibles
	$sql=mysql_query("DELETE FROM actions_possibles WHERE id_type_objet='".$_POST["id_type_objet_logique"]."'");
	$actions = $_POST["action"];
	if(count($actions)>0)
	foreach ($actions as $action){
		$sql=mysql_query("INSERT INTO actions_possibles (id_type_objet, id_action, id_etat_cible) VALUES ('".$_POST["id_type_objet_logique"]."','".$action."','".$_POST["id_etat_cible"][$action]."')") or die (mysql_error());

	}
							
	echo "<p>Type d'objet modifié</p>";
	echo "<a href='?a=home'>Retour</a>";
}

function suppr_post(){
	entete_page("Configuration des types d'objet - Supprimer un type d'objet en base ", "../");
	
	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query ("	DELETE FROM type_objet
							WHERE id_type_objet_logique ='".$_GET["id"]."'") or die(mysql_error());
	$sql = mysql_query ("	DELETE FROM actions_possibles
							WHERE id_type_objet ='".$_GET["id"]."'") or die(mysql_error());
	$sql = mysql_query ("	DELETE FROM etats_possibles
							WHERE id_type_objet ='".$_GET["id"]."'") or die(mysql_error());
							
	echo "<p>Type d'objet supprimé</p>";
	echo "<a href='?'>Retour</a>";

}



pied_page();

?>

