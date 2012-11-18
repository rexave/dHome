?<?php

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
	entete_page("Configuration des Objets - Home" , "../");

?>

	<div data-role="content">
		<div class="content-primary">

			<ul data-role="listview" data-inset="true">
				<li data-theme="a"><a href="?a=add">Ajouter un objet</a></li>
				<?php
					$sql = mysql_query("SELECT id_objet_logique,nom_objet from objets ORDER BY nom_objet");
					while(list($id_objet_logique,$nom_objet)=mysql_fetch_array($sql)){
						echo '<li><a href="gestion_objet.php?a=edit&&id='.$id_objet_logique.'">'.$nom_objet.'</a></li>';
					}
					
					//id_objet_logique	nom_objet	commentaire_objet	id_objet_physique	id_type_objet_logique	id_etat
				?>
			</ul>

		</div>
	</div><!-- /content -->

<?php
}

function add(){
	entete_page("Configuration des Objets - Ajout" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=add_post" method="post">

			<div data-role="fieldcontain">
				<label for="nom_objet">Nom de l'objet</label>
				<input type="text" name="nom_objet" id="nom_objet" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="commentaire_objet">Commentaire</label>
				<textarea cols="40" rows="8" name="commentaire_objet" id="commentaire_objet"></textarea>
			</div>
			<div data-role="fieldcontain">
				<label for="id_objet_physique">ID physique de l'objet</label>
				<input type="text" name="id_objet_physique" id="id_objet_physique" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="id_type_objet_logique" class="select">Type d'objet</label>
				<select name="id_type_objet_logique" id="id_type_objet_logique">
					<?php
					$sql = mysql_query("SELECT id_type_objet_logique,nom_type_objet from type_objet ORDER BY nom_type_objet");
					while(list($id_type_objet_logique,$nom_type_objet)=mysql_fetch_array($sql)){
						echo '<option value="'.$id_type_objet_logique.'">'.$nom_type_objet.'</option>';
					}
					?>
					
				</select>
			</div>
			<div data-role="fieldcontain">
				<label for="id_etat" class="select">Etat</label>
				Non géré pour l'instant
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

	entete_page("Configuration des Objets - Ajout en base " , "../");

	//verification parametres renvoyés
	if($_POST["nom_objet"] == ""){ echo "Error : pas de \"Nom d'objet\" renvoyé !";}else{
		if($_POST["id_type_objet_logique"] == ""){ echo "Error : pas de \"Type d'objet\" renvoyé !";}else{

			$sql = mysql_query ("	INSERT INTO objets
									(nom_objet,commentaire_objet,id_objet_physique,id_type_objet_logique) VALUES
									('".$_POST["nom_objet"]."','".$_POST["commentaire_objet"]."','".$_POST["id_objet_physique"]."','".$_POST["id_type_objet_logique"]."')") or die(mysql_error());
									
			echo "<p>Objet ajouté</p>";
		}
	}
	echo "<a href='?a=home'>Retour</a>";
}

function edit(){

	entete_page("Configuration des Objets - Modifier un objet " , "../");

	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query("SELECT nom_objet,commentaire_objet,id_objet_physique,id_type_objet_logique, id_etat FROM objets WHERE id_objet_logique='".$_GET["id"]."'") or die(mysql_error());
	list($nom_objet,$commentaire_objet,$id_objet_physique,$id_type_objet_logique,$id_etat)=mysql_fetch_array($sql);
	

?>

	<div data-role="content">
		<div class="content-primary">
			<div class="ui-body ui-body-a">
				<form action="?a=edit_post" method="post">
					<input type="hidden" name="id_objet_logique" id="id_objet_logique" value="<?php echo $_GET["id"];?>"  />
					<div data-role="fieldcontain">
						<label for="nom_objet">Nom de l'objet</label>
						<input type="text" name="nom_objet" id="nom_objet" value="<?php echo $nom_objet;?>"  />
					</div>
					<div data-role="fieldcontain">
						<label for="commentaire_objet">Commentaire</label>
						<textarea cols="40" rows="8" name="commentaire_objet" id="commentaire_objet"><?php echo $commentaire_objet;?></textarea>
					</div>
					<div data-role="fieldcontain">
						<label for="id_objet_physique">ID physique de l'objet</label>
						<input type="text" name="id_objet_physique" id="id_objet_physique" value="<?php echo $id_objet_physique;?>"  />
					</div>
					<div data-role="fieldcontain">
						<label for="id_type_objet_logique" class="select">Type d'objet</label>
						<select name="id_type_objet_logique" id="id_type_objet_logique">
							<?php
							$sql = mysql_query("SELECT id_type_objet_logique,nom_type_objet from type_objet ORDER BY nom_type_objet");
							while(list($id_type_objet_logique_sql,$nom_type_objet)=mysql_fetch_array($sql)){
								if($id_type_objet_logique_sql == $id_type_objet_logique)
									echo '<option value="'.$id_type_objet_logique_sql.'" selected="selected">'.$nom_type_objet.'</option>';
								else
									echo '<option value="'.$id_type_objet_logique_sql.'">'.$nom_type_objet.'</option>';
							}
							?>
							
						</select>
					</div>
					<div data-role="fieldcontain">
						<label for="id_etat" class="select">Forcer l'état</label>
						<select name="id_etat" id="id_etat">
        				<?php
    						$sql = mysql_query("SELECT etats.id_etat, lib_etat
    											FROM etats,etats_possibles,objets
    											WHERE etats.id_etat=etats_possibles.id_etat
    											AND objets.id_type_objet_logique = etats_possibles.id_type_objet
    											AND objets.id_objet_logique ='".$_GET["id"]."'
    											ORDER BY lib_etat");
    						while(list($id_etat_sql,$lib_etat) = mysql_fetch_array($sql)){
    							if($id_etat == $id_etat_sql)
    								echo '<option value="'.$id_etat_sql.'" selected="selected">'.$lib_etat.'</option>';
    							else
    								echo '<option value="'.$id_etat_sql.'">'.$lib_etat.'</option>';
    						}
    					?>
					</select>
					</div>
					
				<div data-role="fieldcontain">
					<h3>Définition des actions possibles</p>
						<?php
						$sql = mysql_query("SELECT actions.id_action,actions.lib_action, id_eventGhost
											FROM actions, actions_possibles LEFT OUTER JOIN actions_definies
												on actions_definies.id_action = actions_possibles.id_action
												and actions_definies.id_objet='".$_GET["id"]."'
											WHERE actions.id_action=actions_possibles.id_action
											AND actions_possibles.id_type_objet='".$id_type_objet_logique."'
											ORDER BY lib_action") or die (mysql_error());
						while(list($id_action,$lib_action,$id_eventGhost) = mysql_fetch_array($sql)){
							echo'<label for="id_eventGhost_'.$id_action.'">'.$lib_action.'</label>
							<input type="text" name="id_eventGhost['.$id_action.']" id="id_eventGhost_'.$id_action.'" value="'.$id_eventGhost.'"/> ';
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
					<div class="ui-body ui-body-d">
						<a href="#" data-role="button" data-icon="delete" onclick="if(confirm('Confirmer la suppression ?')){self.location='?a=suppr_post&id=<?php echo $_GET["id"];?>'} ">Delete</a>
					</div>
				</form>
			</div>
		</div>
	</div><!-- /content -->

<?php
}


function edit_post(){

	entete_page("Configuration des Objets - Modification en base " , "../");

	//verification parametres renvoyés
	if($_POST["id_objet_logique"] == ""){ echo "Error : pas d'identifiant renvoyé !";}else{
		$sql = mysql_query ("	UPDATE objets
								SET nom_objet='".$_POST["nom_objet"]."',
								commentaire_objet='".$_POST["commentaire_objet"]."',
								id_objet_physique='".$_POST["id_objet_physique"]."',
								id_type_objet_logique='".$_POST["id_type_objet_logique"]."',
                                id_etat='".$_POST["id_etat"]."'
								WHERE id_objet_logique ='".$_POST["id_objet_logique"]."'") or die(mysql_error());
								
		echo "<p>Objet modifié</p>";
	}
	
	$sql=mysql_query("DELETE FROM actions_definies WHERE id_objet='".$_POST["id_objet_logique"]."'") or die (mysql_error());
	$actions = $_POST["id_eventGhost"];
	if(count($actions)>0)
	foreach ($actions as $action => $id_eventGhost){
		$sql=mysql_query("INSERT INTO actions_definies (id_action, id_objet, id_eventGhost) VALUES 
		('".$action."','".$_POST["id_objet_logique"]."','".$id_eventGhost."')") or die (mysql_error());
		echo "Action $action ajoutée avec l'id evenghost $id_eventGhost.<br/>";
	}

	
	echo "<a href='?a=home'>Retour</a>";
}

function suppr_post(){
	entete_page("Configuration des Objets - Suppression en base " , "../");
	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query ("	DELETE FROM objets
							WHERE id_objet_logique ='".$_GET["id"]."'") or die(mysql_error());
							
	echo "<p>Objet supprimé</p>";
	echo "<a href='?'>Retour</a>";

}



pied_page();

?>

