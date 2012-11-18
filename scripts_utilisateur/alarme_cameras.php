<?php


function alerte_camera($camera,$action){

	switch($action){
		case "alerte":	$action_zm="Record"; break;
		case "normal":	$action_zm="None";	break;
		default: exit;
	}

	switch($camera){
		case "couloir":
			$id_cam_zm=3;
			$url_camera="1.2.3.4";
			$preset=31;
			break;
		case "entree":
			$id_cam_zm=1;
			$url_camera="1.2.3.5";
			$preset=31;
			break;
		default:
			exit;
	}

	//allumage ZM
	$url  = "http://${ZONEMINDER_SERVER_URL}/zm/index.php";
	//paametres
	$param = 'view=none';
	$param .= '&action=function';
	$param .= '&mid='.$id_cam_zm;
	$param .= '&newFunction='.$action_zm;
	$param .= '&newEnabled=1';

	//à appeler en POST
	$c = curl_init ($url);
	curl_setopt ($c, CURLOPT_POST, true);
	curl_setopt ($c, CURLOPT_POSTFIELDS, $param);
	curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
	curl_exec($c);
	curl_close($c);
	
	//deplacement camera en position initiale d'alarme
	$url  = "http://".$url_camera."/decoder_control.cgi?command=".$preset."&user=admin&pwd=password";

	$c = curl_init ($url);
	curl_setopt ($c, CURLOPT_RETURNTRANSFER, true);
	curl_exec($c);
	curl_close($c);
}

alerte_camera("entree",$_GET['etat']);
alerte_camera("couloir",$_GET['etat']);

?>

