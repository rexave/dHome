<?php

function entete_page($titre,$chemin_relatif_racine=""){
?>

<!DOCTYPE html> 
<html>
	<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1"> 
	<title><?php echo $titre;?></title> 
	<link rel="stylesheet"  href="<?php echo $chemin_relatif_racine;?>css/themes/default/jquery.mobile-1.1.1.css" type="text/css" />  
	<link rel="stylesheet" href="<?php echo $chemin_relatif_racine;?>css/jqm-docs.css" type="text/css" />

	<script src="<?php echo $chemin_relatif_racine;?>js/jquery.js"></script>
	<script src="<?php echo $chemin_relatif_racine;?>js/jquery.mobile-1.1.1.js"></script>

</head> 
<body>

	<div data-role="page" class="type-interior">

		<div data-role="header" data-theme="f">
		<h1><?php echo $titre;?></h1>
		<a href="../" data-icon="home" data-iconpos="notext" data-direction="reverse">Home</a>
		<a href="../config/index.php" data-icon="gear" data-iconpos="notext" data-direction="reverse">Config</a>
		<!--<a href="../nav.html" data-icon="search" data-iconpos="notext" data-rel="dialog" data-transition="fade">Search</a>-->
	</div><!-- /header -->
<?php
}



function pied_page(){
?>
	<div data-role="footer" class="footer-docs" data-theme="c">
			<p>&copy; 2012 jQuery Foundation and other contributors</p>
	</div>

	</div><!-- /page -->

	</body>
	</html>
<?php
}


?>