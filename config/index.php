<?php

require_once ("../includes/global_ui.php");

entete_page("Configuration" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			<h2>Configuration</h2>

			<h3>Objets</h3>
			<ul data-role="listview" data-inset="true">
				<li><a href="gestion_objet.php">Gestion des Objets</a></li>
				<li><a href="gestion_type_objet.php">Gestion des Types d'objets</a></li>
                <li><a href="gestion_nabaztag.php">Gestion des Nabaztag</a></li>
			</ul>
    		<h3>Scenarii</h3>
			<ul data-role="listview" data-inset="true">
				<li><a href="gestion_scenario.php">Gestion des Scénarios</a></li>
				<li><a href="planification.php">Planification</a></li>
			</ul> 
            <h3>Dashboard</h3>
			<ul data-role="listview" data-inset="true">
				<li><a href="gestion_dashboard.php">Gestion du Dashboard</a></li>
			</ul>
			<h3>Paramètres</h3>
			<ul data-role="listview" data-inset="true">
                <li><a href="gestion_action.php">Gestion des Actions</a></li>
                <li><a href="gestion_etat.php">Gestion des Etats</a></li>
                <li><a href="gestion_notification.php">Gestion des Notifications prédéfinies</a></li>
			</ul>

		</div>
	</div><!-- /content -->

<?php

pied_page();

?>

