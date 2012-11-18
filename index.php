<?php 
require_once "connexion.php";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>dHome</title>
	<link rel="stylesheet"  href="css/themes/default/jquery.mobile-1.1.1.css" />
	<link rel="stylesheet" href="css/jqm-docs.css" />
	<script src="js/jquery.js"></script>
	<script src="docs/_assets/js/jqm-docs.js"></script>
	<script src="js/jquery.mobile-1.1.1.js"></script>
	
</head>
<body>
<div data-role="page" class="type-home">
	<div data-role="content">


		<div class="content-secondary">

			<div id="jqm-homeheader">
				<h1 id="jqm-logo">dHome</h1>
			</div>


			<p class="intro"><strong>Welcome.</strong> Vous êtes ici chez vous!</p>

			<div>
				<a href="#" onclick="rafraichir_temperature();" data-role="button" data-icon="refresh" data-iconpos="left" data-mini="true" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="b" class="ui-btn ui-btn-inline ui-shadow ui-btn-corner-all ui-mini ui-btn-icon-left ui-btn-hover-c ui-btn-up-c"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text">Rafraichir les temperatures</span><span class="ui-icon ui-icon-refresh ui-icon-shadow">&nbsp;</span></span></a>
			</div>
			<div id="temperatures">
			</div>
			

		</div><!--/content-primary-->

		<div class="content-primary">
			<nav>


				<ul data-role="listview" data-inset="true" data-theme="c" data-dividertheme="b">
					<li data-role="list-divider">Components</li>
					<li><a href="control/dashboard.php">Controles</a></li>
					<li><a href="graphe/index.php">Graphiques</a></li>

					<li data-role="list-divider">Configurations</li>
					<li><a href="config/index.php">Accéder aux paramètres</a></li>
					<li><a href="test.php">test</a></li>
				</ul>
			</nav>
		</div>



	</div>

	<div data-role="footer" class="footer-docs" data-theme="c">
			<p>Pied de page</p>
	</div>

</div>

	<script type="text/javascript">
		function rafraichir_temperature() {
			$("#temperatures").html("<img src='images/ajax-loader.gif' alt='loading'/>");
			
			$.post("moteur/index_ajax.php" , {
			}, function(data){
				$("#temperatures").html(data);
			});
		}		
	</script>

</body>
</html>
