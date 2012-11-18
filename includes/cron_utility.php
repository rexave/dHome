<?php

$debut = '#Les lignes suivantes sont gerees automatiquement via un script PHP. - Merci de ne pas editer manuellement';

$fin = '#Les lignes suivantes ne sont plus gerees automatiquement';

function ajouteScript($chpHeure, $chpMinute, $chpJourMois, $chpJourSemaine, $chpMois, $chpCommande, $chpCommentaire)
{
	$oldCrontab = Array();				/* pour chaque cellule une ligne du crontab actuel */
	$newCrontab = Array();				/* pour chaque cellule une ligne du nouveau crontab */
	$isSection = false;
	$maxNb = 0;					/* le plus grand num�ro de script trouv� */
	exec('crontab -l', $oldCrontab);		/* on r�cup�re l'ancienne crontab dans $oldCrontab */
	
	foreach($oldCrontab as $index => $ligne)	/* copie $oldCrontab dans $newCrontab et ajoute le nouveau script */
	{
		if ($isSection == true)			/* on est dans la section g�r�e automatiquement */
		{
			$motsLigne = explode(' ', $ligne);
			if ($motsLigne[0] == '#' && $motsLigne[1] > $maxNb)	/* si on trouve un num�ro plus grand */

			{
					$maxNb = $motsLigne[1];
			}
		}
		
		if ($ligne == $debut) { $isSection = true; }
		
		if ($ligne == $fin)			/* on est arriv� � la fin, on rajoute le nouveau script */
		{
			$id = $maxNb + 1;
			$newCrontab[] = '# '.$id.' : '.$chpCommentaire;

			$newCrontab[] = $chpMinute.' '.$chpHeure.' '.$chpJourMois.' '.
				$chpMois.' '.$chpJourSemaine.' '.$chpCommande;
		}
		
		$newCrontab[] = $ligne;			/* copie $oldCrontab, ligne apr�s ligne */
	}
	
	if ($isSection == false) 			/* s'il n'y a pas de section g�r�e par le script */
	{						/*  on l'ajoute maintenant */
		$id = 1;
		$newCrontab[] = $debut;
		$newCrontab[] = '# 1 : '.$chpCommentaire;

		$newCrontab[] = $chpMinute.' '.$chpHeure.' '.$chpJourMois.' '.$chpMois.' '.$chpJourSemaine.' '.$chpCommande;
		$newCrontab[] = $fin;
	}
	
	$f = fopen('/tmp', 'w');			/* on cr�e le fichier temporaire */
	fwrite($f, implode('\n', $newCrontab); 
	fclose($f);
	
	exec('crontab /tmp');				/* on le soumet comme crontab */
	
	return 	$id;
}


function retireScript($id)
{
	$oldCrontab = Array();				/* pour chaque cellule une ligne du crontab actuel */
	$newCrontab = Array();				/* pour chaque cellule une ligne du nouveau crontab */
	$isSection = false;
	
	exec('crontab -l', $oldCrontab);		/* on r�cup�re l'ancienne crontab dans $oldCrontab */
	
	foreach($oldCrontab as $ligne)			/* copie $oldCrontab dans $newCrontab sans le script � effacer */
	{
		if ($isSection == true)			/* on est dans la section g�r�e automatiquement */
		{
			$motsLigne = explode(' ', $ligne);
			if ($motsLigne[0] != '#' || $motsLigne[1] != $id)	/* ce n est pas le script � effacer */

			{
					$newCrontab[] = $ligne;			/* copie $oldCrontab, ligne apr�s ligne */
			}
		}else{
			$newCrontab[] = $ligne;		/* copie $oldCrontab, ligne apr�s ligne */
		}
		
		if ($ligne == $debut) { $isSection = true; }
	}
	
	$f = fopen('/tmpCronTab', 'w');			/* on cr�e le fichier temporaire */
	fwrite($f, implode('\n', $newCrontab); 
	fclose($f);
	
	exec('crontab /tmpCronTab');			/* on le soumet comme crontab */
	
	return 	$id;
}

?>