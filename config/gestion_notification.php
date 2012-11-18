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
	entete_page("Configuration des Notifications prédéfinies - Home" , "../");

?>

	<div data-role="content">
		<div class="content-primary">

			<ul data-role="listview" data-inset="true">
				<li data-theme="a"><a href="?a=add">Ajouter une Notification</a></li>
				<?php
					$sql = mysql_query("SELECT id_notif,type_notif,objet_notif from actions_notification ORDER BY type_notif,objet_notif");
					while(list($id_notif,$type_notif,$objet_notif)=mysql_fetch_array($sql)){
						echo '<li><a href="?a=edit&id='.$id_notif.'">'.$type_notif.' - '.$objet_notif.'</a></li>';
					}
				?>
			</ul>

		</div>
	</div><!-- /content -->

<?php
}

function add(){
	entete_page("Configuration des Notifications prédéfinies - Ajout" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=add_post" method="post">

    		<div data-role="fieldcontain">
				<label for="type_notif">Type de notification</label>
                <select name="type_notif" id="type_notif">
                    <option value="push">Push PushingBox</option>
                    <option value="mail">Mail</option>
					<option value="karotz">Push Karotz PushingBox</option>
                    <option value="nabaztag">Nabaztag</option>
               </select>
			</div>

			<div data-role="fieldcontain">
				<label for="objet_notif">Objet du mail/Titre de la notification</label>
				<input type="text" name="objet_notif" id="objet_notif" value=""  />
			</div>
            
			<div data-role="fieldcontain">
				<label for="destinataire_mail">Destinataire du mail</label>
				<input type="text" name="destinataire_mail" id="destinataire_mail" value=""  />
			</div>
						
			<div data-role="fieldcontain">
				<label for="message_notif">Message</label>
				<textarea type="text" name="message_notif" id="message_notif"></textarea>
			</div>
			
			<div data-role="fieldcontain">
				<fieldset data-role="controlgroup">
					<legend>Si Nabaztag :</legend>
					<?php
						$sql_nab = mysql_query("SELECT id_nab,nom_nab from nabaztag ORDER BY nom_nab");
						while(list($id_nab,$nom_nab)=mysql_fetch_array($sql_nab)){
							echo'<input type="checkbox" name="nab['.$id_nab.']" value="'.$id_nab.'" id="nab_'.$id_nab.'" class="custom" />
								<label for="nab_'.$id_nab.'">'.$nom_nab.'</label>';
						}
					?>
				</fieldset>
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

	entete_page("Configuration des Notifications prédéfinies - Ajout en base " , "../");

	//verification parametres renvoyés
	if($_POST["objet_notif"] == ""){ echo "Error : pas de d'objet renvoyé !";}else{
	
		//construction de la liste des nabatag impactés
		$liste_nab_sql="";
		$nabs = $_POST["nab"];
		if(count($nabs)>0)
			foreach ($nabs as $nab){
				$liste_nab_sql .= $nab.",";
			}

		$sql = mysql_query ("	INSERT INTO actions_notification
								(type_notif,objet_notif,message_notif,destinataire_mail,ids_nab) VALUES
								('".$_POST["type_notif"]."','".$_POST["objet_notif"]."',
								'".addslashes($_POST["message_notif"])."',
								'".$_POST["destinataire_mail"]."',
								'".$liste_nab_sql."')") or die(mysql_error());
								
		echo "<p>Notification enregistrée</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}

function edit(){

	entete_page("Configuration des Notifications prédéfinies - Modifier un message " , "../");

	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query("SELECT type_notif,objet_notif,message_notif,destinataire_mail,ids_nab FROM actions_notification WHERE id_notif='".$_GET["id"]."'") or die(mysql_error());
	list($type_notif,$objet_notif,$message_notif,$destinataire_mail,$ids_nab)=mysql_fetch_array($sql);
	
	$ids_nab_tab = split(',',$ids_nab);
?>
	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=edit_post" method="post">
				<input type="hidden" name="id_notif" id="id_notif" value="<?php echo $_GET["id"];?>"  />
				
				<div data-role="fieldcontain">
					<label for="type_notif">Type de notification</label>
					<select name="type_notif" id="type_notif">
						<option value="push" <?php if($type_notif=="push") echo 'selected="selected"';?>>Push PushingBox</option>
						<option value="mail" <?php if($type_notif=="mail") echo 'selected="selected"';?>>Mail</option>
						<option value="karotz" <?php if($type_notif=="karotz") echo 'selected="selected"';?>>Push Karotz PushingBox</option>
						<option value="nabaztag" <?php if($type_notif=="nabaztag") echo 'selected="selected"';?>>Nabaztag</option>
					</select>
				</div>

				<div data-role="fieldcontain">
					<label for="objet_notif">Objet du mail/Titre de la notification</label>
					<input type="text" name="objet_notif" id="objet_notif" value="<?php echo $objet_notif;?>"  />
				</div>
				
				<div data-role="fieldcontain">
					<label for="destinataire_mail">Destinataire du mail</label>
					<input type="text" name="destinataire_mail" id="destinataire_mail" value="<?php echo $destinataire_mail;?>"  />
				</div>
							
				<div data-role="fieldcontain">
					<label for="message_notif">Message</label>
					<textarea type="text" name="message_notif" id="message_notif"><?php echo $message_notif;?></textarea>
				</div>
				
				<div data-role="fieldcontain">
					<fieldset data-role="controlgroup">
						<legend>Si Nabaztag :</legend>
						<?php
							$sql_nab = mysql_query("SELECT id_nab,nom_nab from nabaztag ORDER BY nom_nab");
							while(list($id_nab,$nom_nab)=mysql_fetch_array($sql_nab)){
								if(in_array($id_nab,$ids_nab_tab))
									echo'<input type="checkbox" name="nab['.$id_nab.']" value="'.$id_nab.'" id="nab_'.$id_nab.'" class="custom" checked/>
										<label for="nab_'.$id_nab.'">'.$nom_nab.'</label>';
								else
									echo'<input type="checkbox" name="nab['.$id_nab.']" value="'.$id_nab.'" id="nab_'.$id_nab.'" class="custom" />
										<label for="nab_'.$id_nab.'">'.$nom_nab.'</label>';
							}
						?>
					</fieldset>
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
	
<?php
}


function edit_post(){

	entete_page("Configuration des Notifications prédéfinies - Modification en base " , "../");
	
	//construction de la liste des nabatag impactés
	$liste_nab_sql="";
	$nabs = $_POST["nab"];
	if(count($nabs)>0)
		foreach ($nabs as $nab){
			$liste_nab_sql .= $nab.",";
		}

	//verification parametres renvoyés
	if($_POST["id_notif"] == ""){ echo "Error : pas d'identifiant renvoyé !";}else{
		$sql = mysql_query ("	UPDATE actions_notification
								SET type_notif='".$_POST["type_notif"]."',
								objet_notif='".$_POST["objet_notif"]."',
								destinataire_mail='".$_POST["destinataire_mail"]."',
								message_notif='".addslashes($_POST["message_notif"])."',
								ids_nab='".$liste_nab_sql."'
								WHERE id_notif ='".$_POST["id_notif"]."'") or die(mysql_error());
								
		echo "<p>Notification modifiée</p>";
	}
	echo "<a href='?a=home'>Retour</a>";
}

function suppr_post(){
	entete_page("Configuration des Notifications prédéfinies - Suppression en base " , "../");
	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query ("	DELETE FROM actions_notification
							WHERE id_notif ='".$_GET["id"]."'") or die(mysql_error());
							
	echo "<p>Notification supprimée</p>";
	echo "<a href='?a=home'>Retour</a>";

}



pied_page();

?>