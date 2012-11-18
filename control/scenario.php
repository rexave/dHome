<?php

require_once ("../includes/global_ui.php");
require_once ("../connexion.php");

entete_page("Scenario" , "../");

switch($_GET[a]){
    default: scenario(); break;
}

function scenario(){

    echo '
		<div data-role="content">
			<div class="content-primary">
				<ul data-role="listview" id="liste_objet_scenario">';
    $sql_type = mysql_query("	SELECT type_objet.id_type_objet_logique,nom_type_objet
								FROM type_objet, objets, ui_type_objet
								WHERE type_objet.id_type_objet_logique = objets.id_type_objet_logique
								AND ui_type_objet.id_type_objet=objets.id_type_objet_logique
								and displayed='1'
								GROUP BY type_objet.id_type_objet_logique
								ORDER BY ordre ASC") or die (mysql_error());
	while(list($id_type_objet_logique,$nom_type_objet,$nb)=mysql_fetch_array($sql_type)){
		echo '
					<li data-role="list-divider">'.$nom_type_objet.'</li>';
		$sql_objets = mysql_query("	SELECT id_objet_logique,nom_objet,lib_etat , nom_icone
									FROM (objets, etats) LEFT OUTER JOIN etats_possibles
										on objets.id_type_objet_logique=etats_possibles.id_type_objet
										and etats.id_etat=etats_possibles.id_etat
									WHERE objets.id_etat = etats.id_etat
									AND objets.id_type_objet_logique='".$id_type_objet_logique."'
									ORDER BY nom_objet");
		while(list($id_objet_logique,$nom_objet,$lib_etat,$nom_icone)=mysql_fetch_array($sql_objets)){
			echo '<li id="dash_object_'.$id_objet_logique.'">
					<img src="../images/'.$nom_icone.'" alt=""/>
					<h3>'.$nom_objet.'</h3>';
			$sql_action_possible = mysql_query("SELECT actions.id_action,lib_action
												FROM actions_possibles, objets, actions
												WHERE objets.id_type_objet_logique = actions_possibles.id_type_objet
												AND actions_possibles.id_etat_cible <> objets.id_etat
												AND actions_possibles.id_action = actions.id_action
												AND objets.id_objet_logique = '".$id_objet_logique."'");
			while(list($id_action, $lib_action)=mysql_fetch_array($sql_action_possible)){
				echo "<p><a href='#' onclick=\"lancer_action('".$id_objet_logique."','".$id_action."');\">$lib_action</a></p>";
			}
			echo "</li>";
		}


	}
	?>
				</ul>
		</div>
	</div>

	<script type="text/javascript">
		function lancer_action(id_objet,id_action) {
			$("#dash_object_"+id_objet).html("<img src='../images/ajax-loader.gif' alt='loading'/>");
			$.get("scenario_ajax.php?a=action_sur_objet" , {
				id:id_objet,
				action:id_action
			}, function(data){
				$("#dash_object_"+id_objet).html(data);
				$("#liste_objet_scenario").listview('refresh');;
			});
			
		}
		
	</script>
	
	
	<?php
}

echo "<div style='clear:both;'></div>";

pied_page();


?>