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
	entete_page("Plannifications - Home" , "../");

?>

	<div data-role="content">
		<div class="content-primary">

			<ul data-role="listview" data-inset="true">
				<li data-theme="a"><a href="?a=add">Ajouter une planification</a></li>
				<?php
					$sql = mysql_query("SELECT id_cron,lib_cron from core_cron ORDER BY lib_cron");
					while(list($id_cron,$lib_cron)=mysql_fetch_array($sql)){
						echo '<li><a href="?a=edit&id='.$id_cron.'">'.$lib_cron.'</a></li>';
					}
				?>
			</ul>

		</div>
	</div><!-- /content -->

<?php
}

function cron_generator(){
?>
<script type="text/javascript" src="../js/crontab_generator_dhome.js"></script>

		<div style="margin-bottom:20px;">
			<div id="output">
				<p id="output-crontab" style="font-size:1.75em;font-weight:bold;text-align:center;">
					<span id="min-out">*</span>
					<span id="hour-out">*</span>
					<span id="dom-out">*</span>
					<span id="mon-out">*</span>
					<span id="dow-out">*</span>
				</p>
				<input type="hidden" id="valeur_cron" name="valeur_cron" value="* * * * *"/>
			</div>
		</div>

		<div data-role="collapsible-set">
			<div data-role="collapsible">
			<h3>Minute</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('min-all');">every minute</a></li>
						<li><a href="#" onclick="display_cron('min-every');">every <em>n</em> minutes</a></li>
						<li><a href="#" onclick="display_cron('min-selected');">each selected minute</a></li>
					</ul>
				</div>
				<div id="div-min-all" class="div-min">
				</div>
				<div id="div-min-every" class="div-min" style="display:none;">
				<h3>every <em>n</em> minutes</h3>
					<input type="range" name="min-out_slider" id="min-out_slider" value="0" min="0" max="59" data-highlight="true" onchange="action_slider(this);"/>
				</div>
				<div id="div-min-selected" class="div-min" style="display:none;">
				<h3>each selected minute</h3>
					<div data-role="fieldcontain">
							<fieldset data-role="controlgroup" data-type="horizontal">
								<?php
									for($i=0;$i<60;$i++){
										echo '<input type="checkbox" name="min-out" id="min-out_'.$i.'" onClick="update_each(\'min\');">
										<label class="min-out_'.$i.'" for="min-out_'.$i.'" >';printf('%02s', $i); echo'</label>';
										if(($i+1)%5==0 && $i!=0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
									}
								?>
							</fieldset>
					</div>
				</div>
			</p>
			</div>
			<div data-role="collapsible">
			<h3>Hour</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('hour-all');">every hour</a></li>
						<li><a href="#" onclick="display_cron('hour-every');">every <em>n</em> hour</a></li>
						<li><a href="#" onclick="display_cron('hour-selected');">each selected hour</a></li>
					</ul>
				</div>
				<div id="div-hour-all" class="div-hour">
				</div>
				<div id="div-hour-every" class="div-hour" style="display:none;">
					<h3>every <em>n</em> hours</h3>
					<input type="range" name="hour-out_slider" id="hour-out_slider" value="0" min="0" max="23" data-highlight="true" onchange="action_slider(this);"/>
				</div>
				<div id="div-hour-selected" class="div-hour" style="display:none;">
					<h3>each selected hour</h3>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<?php
								for($i=0;$i<24;$i++){
									echo '<input type="checkbox" name="hour-out" id="hour-out_'.$i.'" onClick="update_each(\'hour\');">
									<label class="hour-out_'.$i.'" for="hour-out_'.$i.'" >';printf('%02s', $i); echo'</label>';
									if(($i+1)%5==0 && $i!=0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
								}
							?>
						</fieldset>
					</div>
				</div>
			</p>
			</div>
			<div data-role="collapsible">
			<h3>Day of month</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('dom-all');">every day</a></li>
						<li><a href="#" onclick="display_cron('dom-selected');">each selected day</a></li>
					</ul>
				</div>
				<div id="div-dom-all" class="div-dom">
				</div>
				<div id="div-dom-selected" class="div-dom" style="display:none;">
					<h3>each selected day</h3>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<?php
								for($i=0;$i<32;$i++){
									echo '<input type="checkbox" name="dom-out" id="dom-out_'.$i.'" onClick="update_each(\'dom\');">
									<label class="dom-out_'.$i.'" for="dom-out_'.$i.'" >';printf('%02s', $i); echo'</label>';
									if(($i+1)%5==0 && $i!=0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
								}
							?>
						</fieldset>
					</div>
				</div>
			</p>
			</div>
			<div data-role="collapsible">
			<h3>Month</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('mon-all');">every month</a></li>
						<li><a href="#" onclick="display_cron('mon-selected');">each selected month</a></li>
					</ul>
				</div>
				<div id="div-mon-all" class="div-mon">
				</div>
				<div id="div-mon-selected" class="div-mon" style="display:none;">
					<h3>each selected month</h3>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<?php
							
							$array_mon = split("\t","January	February	March	April	May	June	July	August	September	October	November	December");
							
								for($i=1;$i<13;$i++){
									echo '<input type="checkbox" name="mon-out" id="mon-out_'.$i.'" onClick="update_each(\'mon\');">
									<label class="mon-out_'.$i.'" for="mon-out_'.$i.'" >'.$array_mon[$i-1].'</label>';
									if(($i)%2==0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
								}
							?>
						</fieldset>
					</div>
				</div>
			</p>
			</div>
			<div data-role="collapsible">
			<h3>Day of week</h3>
			<p>
				<div data-role="navbar">
					<ul>
						<li><a href="#" class="ui-btn-active ui-state-persist" onclick="display_cron('dow-all');">every day of the week</a></li>
						<li><a href="#" onclick="display_cron('dow-selected');">each selected day of the week</a></li>
					</ul>
				</div>
				<div id="div-dow-all" class="div-dow">
				</div>
				<div id="div-dow-selected" class="div-dow" style="display:none;">
					<h3>each selected day of the week</h3>
					<div data-role="fieldcontain">
						<fieldset data-role="controlgroup" data-type="horizontal">
							<?php
							$array_dow = array("Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday");
								for($i=0;$i<7;$i++){
									echo '<input type="checkbox" name="dow-out" id="dow-out_'.$i.'" onClick="update_each(\'dow\');">
									<label class="dow-out_'.$i.'" for="dow-out_'.$i.'" >'.$array_dow[$i].'</label>';
									if(($i+1)%2==0) echo "</fieldset><fieldset data-role=\"controlgroup\" data-type=\"horizontal\">";
								}
							?>
						</fieldset>
					</div>
				</div>
			</p>
			</div>
		</div>
<?php
}

function add(){
	entete_page("Plannifications - Ajout" , "../");

?>

	<div data-role="content">
		<div class="content-primary">
			
			<form action="?a=add_post" method="post">

			<div data-role="fieldcontain">
				<label for="lib_cron">Libellé de la plannification</label>
				<input type="text" name="lib_cron" id="lib_cron" value=""  />
			</div>
						
			<div data-role="fieldcontain">
				<label for="id_scenario">Scenario à lancer</label>
				<select name="id_scenario" id="id_scenario"> 
					<?php
					$sql = mysql_query("SELECT id_scenario,lib_scenario from scenario ORDER BY lib_scenario ");
					while(list($id_scenario,$lib_scenario)=mysql_fetch_array($sql)){
						echo '<option value="'.$id_scenario.'">'.$lib_scenario.'</option>';
					}
					?>
				</select>
			</div>
			
			<div data-role="fieldcontain">
				<label for="skip_condition">Prendre en compte les conditions ?</label>
				<select name="skip_condition" id="skip_condition" data-role="slider">
					<option value="0">Non</option>
					<option value="1" selected="selected">Oui</option>
				</select>
			</div>
			
			<p>Rythme : </p>
			<?php cron_generator();?>
						
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

	entete_page("Plannifications - Ajout en base " , "../");
	
	echo "<p>Ajout dans le système ... ";
	
	$id_cron_system = ajouteScript($_POST["valeur_cron"],$_POST["id_scenario"]);
	
	echo " OK</p><p>Ajout en base de données ...";


	//verification parametres renvoyés
	//if($_POST["lib_etat"] == ""){ echo "Error : pas de \"Nom d'état\" renvoyé !";}else{

		$sql = mysql_query ("	INSERT INTO core_cron
								(id_scenario,id_cron_system,valeur_cron,skip_condition,lib_cron) VALUES
								('".$_POST["id_scenario"]."',
								'".$id_cron_system."',
								'".$_POST["valeur_cron"]."',
								'".$_POST["skip_condition"]."',
								'".$_POST["lib_cron"]."')") or die(mysql_error());
								
		echo "OK </p><p> Terminé</p>";
	//}
	echo "<a href='?a=home'>Retour</a>";
}

function edit(){

	entete_page("Plannifications - Modifier un état " , "../");

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

	entete_page("Plannifications - Modification en base " , "../");

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
	entete_page("Plannifications - Suppression en base " , "../");
	if($_GET["id"] == ""){ echo "Error : pas d'identifiant renvoyé !";}
	
	$sql = mysql_query ("	DELETE FROM etats
							WHERE id_etat ='".$_GET["id"]."'") or die(mysql_error());
							
	echo "<p>Etat supprimé</p>";
	echo "<a href='?a=home'>Retour</a>";

}



pied_page();


$debut = '#Les lignes suivantes sont gerees automatiquement via un script PHP. - Merci de ne pas editer manuellement';

$fin = '#Les lignes suivantes ne sont plus gerees automatiquement';

function ajouteScript($valeur_cron, $chpCommentaire)
{
	$chpCommande = "php /volume1/web/dhome/cmd_line.php " . $chpCommentaire;

	$oldCrontab = Array();				/* pour chaque cellule une ligne du crontab actuel */
	$newCrontab = Array();				/* pour chaque cellule une ligne du nouveau crontab */
	$isSection = false;
	$maxNb = 0;					/* le plus grand numéro de script trouvé */
	exec('crontab -l', $oldCrontab);		/* on récupère l'ancienne crontab dans $oldCrontab */
	
	foreach($oldCrontab as $index => $ligne)	/* copie $oldCrontab dans $newCrontab et ajoute le nouveau script */
	{
		if ($isSection == true)			/* on est dans la section gérée automatiquement */
		{
			$motsLigne = explode(' ', $ligne);
			if ($motsLigne[0] == '#' && $motsLigne[1] > $maxNb)	/* si on trouve un numéro plus grand */

			{
					$maxNb = $motsLigne[1];
			}
		}
		
		if ($ligne == $debut) { $isSection = true; }
		
		if ($ligne == $fin)			/* on est arrivé à la fin, on rajoute le nouveau script */
		{
			$id = $maxNb + 1;
			$newCrontab[] = '# '.$id.' : '.$chpCommentaire;

			$newCrontab[] = $valeur_cron.' '.$chpCommande;
		}
		
		$newCrontab[] = $ligne;			/* copie $oldCrontab, ligne après ligne */
	}
	
	if ($isSection == false) 			/* s'il n'y a pas de section gérée par le script */
	{						/*  on l'ajoute maintenant */
		$id = 1;
		$newCrontab[] = $debut;
		$newCrontab[] = '# 1 : '.$chpCommentaire;

		$newCrontab[] = $valeur_cron.' '.$chpCommande;
		$newCrontab[] = $fin;
	}
	
	$f = fopen('/tmp/php_cron_tmp', 'w');			/* on crée le fichier temporaire */
	fwrite($f, implode('\n', $newCrontab)); 
	fclose($f);
	
	exec('crontab /tmp');				/* on le soumet comme crontab */
	
	return 	$id;
}

function retireScript($id)
{
	$oldCrontab = Array();				/* pour chaque cellule une ligne du crontab actuel */
	$newCrontab = Array();				/* pour chaque cellule une ligne du nouveau crontab */
	$isSection = false;
	
	exec('crontab -l', $oldCrontab);		/* on récupère l'ancienne crontab dans $oldCrontab */
	
	foreach($oldCrontab as $ligne)			/* copie $oldCrontab dans $newCrontab sans le script à effacer */
	{
		if ($isSection == true)			/* on est dans la section gérée automatiquement */
		{
			$motsLigne = explode(' ', $ligne);
			if ($motsLigne[0] != '#' || $motsLigne[1] != $id)	/* ce n est pas le script à effacer */

			{
					$newCrontab[] = $ligne;			/* copie $oldCrontab, ligne après ligne */
			}
		}else{
			$newCrontab[] = $ligne;		/* copie $oldCrontab, ligne après ligne */
		}
		
		if ($ligne == $debut) { $isSection = true; }
	}
	
	$f = fopen('/tmp/php_cron_tmp', 'w');			/* on crée le fichier temporaire */
	fwrite($f, implode('\n', $newCrontab)); 
	fclose($f);
	
	exec('crontab /tmpCronTab');			/* on le soumet comme crontab */
	
	return 	$id;
}

?>