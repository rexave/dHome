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
	entete_page("Configuration des Actions - Home" , "../");

?>

	<div data-role="content">
		<div class="content-primary">

			<ul data-role="listview" data-inset="true">
				<li data-theme="a"><a href="?a=add">Ajouter une action</a></li>
				<?php
					$sql = mysql_query("SELECT id_action,lib_action from actions ORDER BY lib_action");
					while(list($id_action,$lib_action)=mysql_fetch_array($sql)){
						echo '<li><a href="?a=edit&id='.$id_action.'">'.$lib_action.'</a></li>';
					}
				?>
			</ul>

		</div>
	</div><!-- /content -->

<?php
}

function add(){
	entete_page("Configuration des Actions - Ajout" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=add_post" method="post">

			<div data-role="fieldcontain">
				<label for="lib_action">Nom de l'action</label>
				<input type="text" name="lib_action" id="lib_action" value=""  />
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

	entete_page("Configuration des Actions - Ajout en base " , "../");

	//verification parametres renvoyés
	if($_POST["lib_action"] == ""){ echo "Error : pas de \"Nom d'action\" renvoyé !";}else{

		$sql = mysql_query ("	INSERT INTO actions
								(lib_action) VALUES
								('".$_POST["lib_action"]."')") or die(mysql_error());
								
		echo "<p>Action ajoutée</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}

function edit(){

	entete_page("Configuration des Actions - Modifier une action " , "../");

	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query("SELECT lib_action FROM actions WHERE id_action='".$_GET["id"]."'") or die(mysql_error());
	list($lib_action)=mysql_fetch_array($sql);
	

?>

	<div data-role="content">
		<div class="content-primary">
			<div class="ui-body ui-body-a">
				<form action="?a=edit_post" method="post">
					<input type="hidden" name="id_action" id="id_action" value="<?php echo $_GET["id"];?>"  />
					<div data-role="fieldcontain">
						<label for="lib_action">Nom de l'action</label>
						<input type="text" name="lib_action" id="lib_action" value="<?php echo $lib_action;?>"  />
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

	entete_page("Configuration des Actions - Modification en base " , "../");

	//verification parametres renvoyés
	if($_POST["id_action"] == ""){ echo "Error : pas d'identifiant renvoyé !";}else{
		$sql = mysql_query ("	UPDATE actions
								SET lib_action='".$_POST["lib_action"]."'
								WHERE id_action ='".$_POST["id_action"]."'") or die(mysql_error());
								
		echo "<p>Action modifiée</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}

function suppr_post(){
	entete_page("Configuration des Actions - Suppression en base " , "../");
	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query ("	DELETE FROM actions
							WHERE id_action ='".$_GET["id"]."'") or die(mysql_error());
							
	echo "<p>Action supprimée</p>";
	echo "<a href='?a=home'>Retour</a>";

}



pied_page();

?>

