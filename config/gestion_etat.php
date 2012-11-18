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
	entete_page("Configuration des Etats - Home" , "../");

?>

	<div data-role="content">
		<div class="content-primary">

			<ul data-role="listview" data-inset="true">
				<li data-theme="a"><a href="?a=add">Ajouter un état</a></li>
				<?php
					$sql = mysql_query("SELECT id_etat,lib_etat from etats ORDER BY lib_etat");
					while(list($id_etat,$lib_etat)=mysql_fetch_array($sql)){
						echo '<li><a href="?a=edit&id='.$id_etat.'">'.$lib_etat.'</a></li>';
					}
				?>
			</ul>

		</div>
	</div><!-- /content -->

<?php
}

function add(){
	entete_page("Configuration des Etats - Ajout" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=add_post" method="post">

			<div data-role="fieldcontain">
				<label for="lib_etat">Nom de l'état</label>
				<input type="text" name="lib_etat" id="lib_etat" value=""  />
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

	entete_page("Configuration des Etats - Ajout en base " , "../");

	//verification parametres renvoyés
	if($_POST["lib_etat"] == ""){ echo "Error : pas de \"Nom d'état\" renvoyé !";}else{

		$sql = mysql_query ("	INSERT INTO etats
								(lib_etat) VALUES
								('".$_POST["lib_etat"]."')") or die(mysql_error());
								
		echo "<p>Etat ajouté</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}

function edit(){

	entete_page("Configuration des Etats - Modifier un état " , "../");

	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query("SELECT lib_etat FROM etats WHERE id_etat='".$_GET["id"]."'") or die(mysql_error());
	list($lib_etat)=mysql_fetch_array($sql);
	

?>

	<div data-role="content">
		<div class="content-primary">
			<div class="ui-body ui-body-a">
				<form action="?a=edit_post" method="post">
					<input type="hidden" name="id_etat" id="id_etat" value="<?php echo $_GET["id"];?>"  />
					<div data-role="fieldcontain">
						<label for="lib_etat">Nom de l'état</label>
						<input type="text" name="lib_etat" id="lib_etat" value="<?php echo $lib_etat;?>"  />
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

	entete_page("Configuration des Etats - Modification en base " , "../");

	//verification parametres renvoyés
	if($_POST["id_etat"] == ""){ echo "Error : pas d'identifiant renvoyé !";}else{
		$sql = mysql_query ("	UPDATE etats
								SET lib_etat='".$_POST["lib_etat"]."'
								WHERE id_etat ='".$_POST["id_etat"]."'") or die(mysql_error());
								
		echo "<p>Etat modifié</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}

function suppr_post(){
	entete_page("Configuration des Etats - Suppression en base " , "../");
	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query ("	DELETE FROM etats
							WHERE id_etat ='".$_GET["id"]."'") or die(mysql_error());
							
	echo "<p>Etat supprimé</p>";
	echo "<a href='?a=home'>Retour</a>";

}



pied_page();

?>