<?php

require_once ("../includes/global_ui.php");
require_once ("../connexion.php");

if(!isset($_GET["a"])) $a = "home"; else $a = $_GET["a"];

switch($a){

    case "home" : home(); break;
    case "maj" : maj(); break;
    default: echo "Erreur de redirection";
}


function home(){
	entete_page("Configuration du Dashboard - Home" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			<form action="?a=maj" method="post">
				<ul data-role="listview" data-inset="true">
					
					<?php
						$sql = mysql_query("SELECT type_objet.id_type_objet_logique,nom_type_objet, displayed, ordre
											FROM type_objet LEFT OUTER JOIN ui_type_objet
												on type_objet.id_type_objet_logique = ui_type_objet.id_type_objet
											ORDER BY COALESCE(ordre,999999999) ASC");
						while(list($id_type_objet_logique,$nom_type_objet, $displayed, $ordre)=mysql_fetch_array($sql)){
							echo '<li data-role="fieldcontain">
								<fieldset data-role="controlgroup" data-type="horizontal">
									<legend><b>'.$nom_type_objet.'</b></legend>
										<div data-role="fieldcontain" style="margin-left:30px;">
											<label for="displayed_'.$id_type_objet_logique.'">Affiché</label>';
							if($displayed != "" && $displayed != "0")
								echo '<select name="displayed['.$id_type_objet_logique.']" id="displayed_'.$id_type_objet_logique.'" data-role="slider">
										<option value="0">OFF</option>
										<option value="1" selected="selected">ON</option>
									</select>';
							else
								echo '<select name="displayed['.$id_type_objet_logique.']" id="displayed_'.$id_type_objet_logique.'" data-role="slider">
										<option value="0">OFF</option>
										<option value="1">ON</option>
									</select>';

								echo '</div>
								<div data-role="fieldcontain" style="margin-left:30px;">
									<label for="ordre_'.$id_type_objet_logique.'">Ordre</label>
									<input type="text" name="ordre['.$id_type_objet_logique.']" value="'.$ordre.'" id="ordre_'.$id_type_objet_logique.'" class="custom"/>
								</div>';
							echo '</li>';
						}
					?>
					<li data-role="fieldcontain">
						<div class="ui-body ui-body-a">
							<fieldset class="ui-grid-a">
								<div class="ui-block-a"><button type="reset" data-theme="d">Cancel</button></div>
								<div class="ui-block-b"><button type="submit" data-theme="a">Submit</button></div>
							</fieldset>
						</div>
					</li>
				</ul>
				
			</form>
		</div><!--/content-primary -->	
	</div>

<?php
}

function maj(){

	entete_page("Configuration du Dashboard - Modification en base " , "../");

	$sql=mysql_query("DELETE FROM ui_type_objet");
	$displayedS = $_POST["displayed"];
	if(count($displayedS)>0)
	foreach ($displayedS as $id_type_objet => $displayed){
	
		$ordre = $_POST["ordre"][$id_type_objet];
		if($ordre=="" || !isset($ordre)) $ordre="NULL"; 
		$sql=mysql_query("INSERT INTO ui_type_objet (id_type_objet, displayed,ordre) 
		VALUES (
		'".$id_type_objet."',
		'".$displayed."',
		".$ordre."
		)") or die (mysql_error());
		echo "<p>MAJ de $id_type_objet</p>";
	}

	echo "<a href='?a=home'>Retour</a>";
}


pied_page();

?>