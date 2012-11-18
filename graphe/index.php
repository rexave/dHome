?<?php

require_once ("../includes/global_ui.php");

entete_page("Graphes" , "../");
?>
<div>
    <a href="#" onclick="afficher_graphe('temp');" data-role="button" data-icon="refresh" data-iconpos="left" data-mini="true" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="b" class="ui-btn ui-btn-inline ui-shadow ui-btn-corner-all ui-mini ui-btn-icon-left ui-btn-hover-c ui-btn-up-c"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text">T° Jour</span><span class="ui-icon ui-icon-refresh ui-icon-shadow">&nbsp;</span></span></a>
    <a href="#" onclick="afficher_graphe('temp_week');" data-role="button" data-icon="refresh" data-iconpos="left" data-mini="true" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="b" class="ui-btn ui-btn-inline ui-shadow ui-btn-corner-all ui-mini ui-btn-icon-left ui-btn-hover-c ui-btn-up-c"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text">T° Semaine</span><span class="ui-icon ui-icon-refresh ui-icon-shadow">&nbsp;</span></span></a>
    <a href="#" onclick="afficher_graphe('temp_month');" data-role="button" data-icon="refresh" data-iconpos="left" data-mini="true" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="b" class="ui-btn ui-btn-inline ui-shadow ui-btn-corner-all ui-mini ui-btn-icon-left ui-btn-hover-c ui-btn-up-c"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text">T° Mois</span><span class="ui-icon ui-icon-refresh ui-icon-shadow">&nbsp;</span></span></a>
    <a href="#" onclick="afficher_graphe('elec');" data-role="button" data-icon="refresh" data-iconpos="left" data-mini="true" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="b" class="ui-btn ui-btn-inline ui-shadow ui-btn-corner-all ui-mini ui-btn-icon-left ui-btn-hover-c ui-btn-up-c"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text">Elect Jour</span><span class="ui-icon ui-icon-refresh ui-icon-shadow">&nbsp;</span></span></a>
    <a href="#" onclick="afficher_graphe('elec_week');" data-role="button" data-icon="refresh" data-iconpos="left" data-mini="true" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="b" class="ui-btn ui-btn-inline ui-shadow ui-btn-corner-all ui-mini ui-btn-icon-left ui-btn-hover-c ui-btn-up-c"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text">Elect Semaine</span><span class="ui-icon ui-icon-refresh ui-icon-shadow">&nbsp;</span></span></a>
    <a href="#" onclick="afficher_graphe('elec_month');" data-role="button" data-icon="refresh" data-iconpos="left" data-mini="true" data-inline="true" data-corners="true" data-shadow="true" data-iconshadow="true" data-wrapperels="span" data-theme="b" class="ui-btn ui-btn-inline ui-shadow ui-btn-corner-all ui-mini ui-btn-icon-left ui-btn-hover-c ui-btn-up-c"><span class="ui-btn-inner ui-btn-corner-all"><span class="ui-btn-text">Elect Mois</span><span class="ui-icon ui-icon-refresh ui-icon-shadow">&nbsp;</span></span></a>
</div>
<div id="graphe">
</div>


<script type="text/javascript">
    function afficher_graphe(type) {
    	$("#graphe").html("<img src='../images/ajax-loader.gif' alt='loading'/>");
		
		$.post("graphes_ajax.php" , {
			type:type
		}, function(data){
			$("#graphe").html(data);
		});
	}		
</script>
<?php
pied_page();
?>

