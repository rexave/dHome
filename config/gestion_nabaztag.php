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
	entete_page("Configuration des Nabaztag - Home" , "../");

?>

	<div data-role="content">
		<div class="content-primary">

			<ul data-role="listview" data-inset="true">
				<li data-theme="a"><a href="?a=add">Ajouter un Nabaztag</a></li>
				<?php
					$sql = mysql_query("SELECT id_nab,nom_nab from nabaztag ORDER BY nom_nab");
					while(list($id_nab,$nom_nab)=mysql_fetch_array($sql)){
						echo '<li><a href="?a=edit&id='.$id_nab.'">'.$nom_nab.'</a></li>';
					}
				?>
			</ul>

		</div>
	</div><!-- /content -->

<?php
}

function add(){
	entete_page("Configuration des Nabaztag - Ajout" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=add_post" method="post">

			<div data-role="fieldcontain">
				<label for="nom_nab">Nom du Nabaztag</label>
				<input type="text" name="nom_nab" id="nom_nab" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="emplacement_nab">Emplacement</label>
				<input type="text" name="emplacement_nab" id="emplacement_nab" value=""  />
			</div>
			<div data-role="fieldcontain">
				<label for="serial_nab">Adresse MAC</label>
				<input type="text" name="serial_nab" id="serial_nab" value=""  />
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

	entete_page("Configuration des Nabaztag - Ajout en base " , "../");

	//verification parametres renvoyés
	if($_POST["nom_nab"] == ""){ echo "Error : pas de \"Nom\" renvoyé !";}else{

		$sql = mysql_query ("	INSERT INTO nabaztag
								(nom_nab,emplacement_nab,serial_nab) VALUES
								('".$_POST["nom_nab"]."','".$_POST["emplacement_nab"]."','".$_POST["serial_nab"]."')") or die(mysql_error());
								
		echo "<p>Nabaztag ajouté</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}

function edit(){

	entete_page("Configuration des Nabaztag - Modifier" , "../");

	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query("SELECT nom_nab,emplacement_nab,serial_nab FROM nabaztag WHERE id_nab='".$_GET["id"]."'") or die(mysql_error());
	list($nom_nab,$emplacement_nab,$serial_nab)=mysql_fetch_array($sql);
	

?>

	<div data-role="content">
		<div class="content-primary">
			<div class="ui-body ui-body-a">
				<form action="?a=edit_post" method="post">
					<input type="hidden" name="id_nab" id="id_nab" value="<?php echo $_GET["id"];?>"  />
					<div data-role="fieldcontain">
						<label for="nom_nab">Nom du Nabaztag</label>
						<input type="text" name="nom_nab" id="nom_nab" value="<?php echo $nom_nab;?>"  />
					</div>
					<div data-role="fieldcontain">
						<label for="emplacement_nab">Emplacement</label>
						<input type="text" name="emplacement_nab" id="emplacement_nab" value="<?php echo $emplacement_nab;?>"  />
					</div>
					<div data-role="fieldcontain">
						<label for="serial_nab">Adresse MAC</label>
						<input type="text" name="serial_nab" id="serial_nab" value="<?php echo $serial_nab;?>"  />
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

	entete_page("Configuration des Nabaztag - Modification en base " , "../");

	//verification parametres renvoyés
	if($_POST["id_nab"] == ""){ echo "Error : pas d'identifiant renvoyé !";}else{
		$sql = mysql_query ("	UPDATE nabaztag
								SET nom_nab='".$_POST["nom_nab"]."',
								emplacement_nab='".$_POST["emplacement_nab"]."',
								serial_nab='".$_POST["serial_nab"]."'
								WHERE id_nab ='".$_POST["id_nab"]."'") or die(mysql_error());
								
		echo "<p>Nabaztag modifié</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}

function suppr_post(){
	entete_page("Configuration des Nabaztag - Suppression en base " , "../");
	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query ("	DELETE FROM nabaztag
							WHERE id_nab ='".$_GET["id"]."'") or die(mysql_error());
							
	echo "<p>Nabaztag supprimé</p>";
	echo "<a href='?a=home'>Retour</a>";

}



pied_page();

?>

