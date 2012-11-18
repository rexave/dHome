<?php
include("../connexion.php");

include("class/pData.class.php");
include("class/pDraw.class.php");
include("class/pImage.class.php");

switch($_POST["type"]){
    case "temp":temperatures_jour();break;
    case "temp_week":temperatures_semaine();break;
    case "temp_month":temperatures_mois();break;
    case "elec":electrique();break;
    case "elec_week":electrique_semaine();break;
    case "elec_month":electrique_mois();break;
}

function temperatures_jour(){

//initialisation des variables tableau
$timestamp="";
$temperature_dehors="";
$temperature_chambre="";
$hygro_chambre="";
$temperature_sejour="";
$hygro_sejour="";
$pression_sejour="";

$sql  = mysql_query("SELECT TIMESTAMP(CONCAT(YEAR(date_histo ),'-',MONTH(date_histo ),'-',DAY(date_histo ),' ',HOUR(date_histo ),':',MINUTE(date_histo ))),
        			date_histo, id_objet,AVG(valeur1),AVG(valeur2),AVG(valeur3)
					FROM historique_donnees
					WHERE id_objet IN (1,3,4)
					AND date_histo >  DATE_SUB(NOW( ), INTERVAL 1 DAY)
					GROUP BY YEAR( date_histo ) , MONTH( date_histo ),DAY( date_histo ),HOUR( date_histo ) , MINUTE( date_histo ), id_objet
					HAVING MINUTE( date_histo ) IN ( 00, 15, 30, 45 ) 
					ORDER BY date_histo");
while(list($date_histo,$date_histo2,$id_objet,$temp,$hygro,$pression) = mysql_fetch_array($sql))
{
	$timestamp[$date_histo]   = strtotime($date_histo);
	switch($id_objet){
		case 1: //chambre
			$temperature_chambre[$date_histo] = $temp;
			$hygro_chambre[$date_histo] = $hygro;
			break;
		case 3: //dehors
			$temperature_dehors[$date_histo] = $temp;
			break;
		case 4: //sejour
			$temperature_sejour[$date_histo] = $temp;
			$hygro_sejour[$date_histo] = $hygro;
			$pression_sejour[$date_histo] = $pression;
			break;
	}
}

//on garde les trous en cas de pertes d'informations
$temperature_chambre=combler_les_trous($timestamp,$temperature_chambre);
$hygro_chambre=combler_les_trous($timestamp,$hygro_chambre);
$temperature_dehors=combler_les_trous($timestamp,$temperature_dehors);
$temperature_sejour=combler_les_trous($timestamp,$temperature_sejour);
$hygro_sejour=combler_les_trous($timestamp,$hygro_sejour);
$pression_sejour=combler_les_trous($timestamp,$pression_sejour);

 
$myData = new pData();  
$myData->addPoints($timestamp,"Timestamp");
$myData->addPoints($temperature_dehors,"T° Exterieure");
$myData->addPoints($temperature_sejour,"T° Séjour");
$myData->addPoints($temperature_chambre,"T° Chambre");
$myData->addPoints($hygro_chambre,"Hygro Chambre");
$myData->addPoints($hygro_sejour,"Hygro Séjour");
$myData->addPoints($pression_sejour,"Pression Atmo");

$myData->setSerieOnAxis("T° Exterieure",0);
$myData->setSerieOnAxis("T° Séjour",0);
$myData->setSerieOnAxis("T° chambre",0);

$myData->setSerieOnAxis("Hygro Séjour",1);
$myData->setSerieOnAxis("Hygro Chambre",1);
$myData->setSerieTicks("Hygro Séjour",4);
$myData->setSerieTicks("Hygro Chambre",4);

$myData->setSerieOnAxis("Pression Atmo",2);

$myData->setAbscissa("Timestamp");
$myData->setXAxisName("Time");
// $myData->setXAxisDisplay(AXIS_FORMAT_TIME,"H:i");
$myData->setXAxisDisplay(AXIS_FORMAT_TIME,"H\h");
//$myData->setXAxisDisplay(0,AXIS_FORMAT_CUSTOM,"format_absisse_jour");
$myData->setAxisName(0,"Temperatures en °C");
$myData->setAxisUnit(0,"°");
//temperature
$bornes_axe_ordonnees[0] = array("Min"=>-8,"Max"=>35);
//humidite
$bornes_axe_ordonnees[1] = array("Min"=>30,"Max"=>90);
$myData->setAxisName(1,"Humidite");
$myData->setAxisUnit(1,"%");
$myData->setAxisName(2,"Pression Atmosphérique");
$myData->setAxisUnit(2,"HPa");
$myData->setAxisPosition(2,AXIS_POSITION_RIGHT);

$myPicture = new pImage(1250,550,$myData);
//$Settings = array("R"=>48, "G"=>124, "B"=>183, "Dash"=>1, "DashR"=>68, "DashG"=>144, "DashB"=>203);
//$myPicture->drawFilledRectangle(0,0,1200,500,$Settings);

$Settings = array("StartR"=>48, "StartG"=>124, "StartB"=>183, "EndR"=>33, "EndG"=>86, "EndB"=>128, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,1250,550,DIRECTION_VERTICAL,$Settings);

//$myPicture->drawRectangle(0,0,1199,499,array("R"=>0,"G"=>0,"B"=>0));

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

$myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
, "R"=>255, "G"=>255, "B"=>255);
$myPicture->drawText(350,25,"Météo Nantaise",$TextSettings);

$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(110,50,1160,500);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/Forgotte.ttf","FontSize"=>14));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50,
"TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50,
"LabelRotation"=>0,
 "CycleBackground"=>1,
 "DrawXLines"=>1,
 "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50,
 "DrawYLines"=>ALL,
 "LabelSkip"=>3//,
// "Mode"=>SCALE_MODE_MANUAL,
// "ManualScale"=>$bornes_axe_ordonnees
);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Config = "";
$myPicture->drawSplineChart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/Forgotte.ttf", "FontSize"=>14, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
, "Mode"=>LEGEND_HORIZONTAL
);
$myPicture->drawLegend(563,16,$Config);

//$myPicture->stroke();

$myPicture->render("tmp/graphe_tj.png");

echo "<img src='tmp/graphe_tj.png' alt='graphe'/>";

}

function temperatures_semaine(){

//initialisation des variables tableau
$timestamp="";
$temperature_dehors="";
$temperature_chambre="";
$hygro_chambre="";
$temperature_sejour="";
$hygro_sejour="";
$pression_sejour="";

$sql  = mysql_query("SELECT TIMESTAMP( CONCAT( YEAR( date_histo ) ,  '-', MONTH( date_histo ) ,  '-', DAY( date_histo ) ,  ' ', HOUR( date_histo ) ,  ':00' ) ) , date_histo, id_objet, AVG( valeur1 ) , AVG( valeur2 ) , AVG( valeur3 ) 
					FROM historique_donnees
					WHERE id_objet IN ( 1, 3, 4 ) 
					AND date_histo >  DATE_SUB(NOW( ), INTERVAL 7 DAY)
					GROUP BY YEAR( date_histo ) , MONTH( date_histo ) , DAY( date_histo ) , HOUR( date_histo ),  id_objet
					ORDER BY date_histo");
while(list($date_histo,$date_histo2,$id_objet,$temp,$hygro,$pression) = mysql_fetch_array($sql))
{
	$timestamp[$date_histo]   = strtotime($date_histo);
	switch($id_objet){
		case 1: //chambre
			$temperature_chambre[$date_histo] = $temp;
			$hygro_chambre[$date_histo] = $hygro;
			break;
		case 3: //dehors
			$temperature_dehors[$date_histo] = $temp;
			break;
		case 4: //sejour
			$temperature_sejour[$date_histo] = $temp;
			$hygro_sejour[$date_histo] = $hygro;
			$pression_sejour[$date_histo] = $pression;
			break;
	}
}

//on garde les trous en cas de pertes d'informations
$temperature_chambre=combler_les_trous($timestamp,$temperature_chambre);
$hygro_chambre=combler_les_trous($timestamp,$hygro_chambre);
$temperature_dehors=combler_les_trous($timestamp,$temperature_dehors);
$temperature_sejour=combler_les_trous($timestamp,$temperature_sejour);
$hygro_sejour=combler_les_trous($timestamp,$hygro_sejour);
$pression_sejour=combler_les_trous($timestamp,$pression_sejour);

 
$myData = new pData();  
$myData->addPoints($timestamp,"Timestamp");
$myData->addPoints($temperature_dehors,"T° Exterieure");
$myData->addPoints($temperature_sejour,"T° Séjour");
$myData->addPoints($temperature_chambre,"T° Chambre");
$myData->addPoints($hygro_chambre,"Hygro Chambre");
$myData->addPoints($hygro_sejour,"Hygro Séjour");
$myData->addPoints($pression_sejour,"Pression Atmo");

$myData->setSerieOnAxis("T° Exterieure",0);
$myData->setSerieOnAxis("T° Séjour",0);
$myData->setSerieOnAxis("T° chambre",0);

$myData->setSerieOnAxis("Hygro Séjour",1);
$myData->setSerieOnAxis("Hygro Chambre",1);
$myData->setSerieTicks("Hygro Séjour",4);
$myData->setSerieTicks("Hygro Chambre",4);

$myData->setSerieOnAxis("Pression Atmo",2);

$myData->setAbscissa("Timestamp");
$myData->setXAxisName("Time");
// $myData->setXAxisDisplay(AXIS_FORMAT_TIME,"H:i");
$myData->setXAxisDisplay(AXIS_FORMAT_TIME,"D");
//$myData->setXAxisDisplay(0,AXIS_FORMAT_CUSTOM,"format_absisse_jour");
$myData->setAxisName(0,"Temperatures en °C");
$myData->setAxisUnit(0,"°");
//temperature
$bornes_axe_ordonnees[0] = array("Min"=>-8,"Max"=>35);
//humidite
$bornes_axe_ordonnees[1] = array("Min"=>30,"Max"=>90);
$myData->setAxisName(1,"Humidite");
$myData->setAxisUnit(1,"%");
$myData->setAxisName(2,"Pression Atmosphérique");
$myData->setAxisUnit(2,"HPa");
$myData->setAxisPosition(2,AXIS_POSITION_RIGHT);

$myPicture = new pImage(1250,550,$myData);
//$Settings = array("R"=>48, "G"=>124, "B"=>183, "Dash"=>1, "DashR"=>68, "DashG"=>144, "DashB"=>203);
//$myPicture->drawFilledRectangle(0,0,1200,500,$Settings);

$Settings = array("StartR"=>48, "StartG"=>124, "StartB"=>183, "EndR"=>33, "EndG"=>86, "EndB"=>128, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,1250,550,DIRECTION_VERTICAL,$Settings);

//$myPicture->drawRectangle(0,0,1199,499,array("R"=>0,"G"=>0,"B"=>0));

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

$myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
, "R"=>255, "G"=>255, "B"=>255);
$myPicture->drawText(350,25,"Météo Nantaise",$TextSettings);

$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(110,50,1160,500);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/Forgotte.ttf","FontSize"=>14));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50,
"TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50,
"LabelRotation"=>0,
 "CycleBackground"=>1,
 "DrawXLines"=>1,
 "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50,
 "DrawYLines"=>ALL,
 "LabelSkip"=>23//,
// "Mode"=>SCALE_MODE_MANUAL,
// "ManualScale"=>$bornes_axe_ordonnees
);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Config = "";
$myPicture->drawSplineChart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/Forgotte.ttf", "FontSize"=>14, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
, "Mode"=>LEGEND_HORIZONTAL
);
$myPicture->drawLegend(563,16,$Config);

//$myPicture->stroke();

$myPicture->render("tmp/graphe_ts.png");

echo "<img src='tmp/graphe_ts.png' alt='graphe'/>";

}

function temperatures_mois(){

//initialisation des variables tableau
$timestamp="";
$temperature_dehors="";
$temperature_chambre="";
$hygro_chambre="";
$temperature_sejour="";
$hygro_sejour="";
$pression_sejour="";

$sql  = mysql_query("SELECT TIMESTAMP( CONCAT( YEAR( date_histo ) ,  '-', MONTH( date_histo ) ,  '-', DAY( date_histo ) ,  ' ', HOUR( date_histo ) ,  ':00' ) ) , date_histo, id_objet, AVG( valeur1 ) , AVG( valeur2 ) , AVG( valeur3 ) 
					FROM historique_donnees
					WHERE id_objet IN ( 1, 3, 4 ) 
					AND date_histo >  DATE_SUB(NOW( ), INTERVAL 31 DAY)
					GROUP BY YEAR( date_histo ) , MONTH( date_histo ) , DAY( date_histo ),  HOUR( date_histo ),  id_objet
					HAVING HOUR( date_histo ) IN ( 00, 06, 12, 18 ) 
					ORDER BY date_histo");
while(list($date_histo,$date_histo2,$id_objet,$temp,$hygro,$pression) = mysql_fetch_array($sql))
{
	$timestamp[$date_histo]   = strtotime($date_histo);
	switch($id_objet){
		case 1: //chambre
			$temperature_chambre[$date_histo] = $temp;
			$hygro_chambre[$date_histo] = $hygro;
			break;
		case 3: //dehors
			$temperature_dehors[$date_histo] = $temp;
			break;
		case 4: //sejour
			$temperature_sejour[$date_histo] = $temp;
			$hygro_sejour[$date_histo] = $hygro;
			$pression_sejour[$date_histo] = $pression;
			break;
	}
}

//on garde les trous en cas de pertes d'informations
$temperature_chambre=combler_les_trous($timestamp,$temperature_chambre);
$hygro_chambre=combler_les_trous($timestamp,$hygro_chambre);
$temperature_dehors=combler_les_trous($timestamp,$temperature_dehors);
$temperature_sejour=combler_les_trous($timestamp,$temperature_sejour);
$hygro_sejour=combler_les_trous($timestamp,$hygro_sejour);
$pression_sejour=combler_les_trous($timestamp,$pression_sejour);

 
$myData = new pData();  
$myData->addPoints($timestamp,"Timestamp");
$myData->addPoints($temperature_dehors,"T° Exterieure");
$myData->addPoints($temperature_sejour,"T° Séjour");
$myData->addPoints($temperature_chambre,"T° Chambre");
$myData->addPoints($hygro_chambre,"Hygro Chambre");
$myData->addPoints($hygro_sejour,"Hygro Séjour");
$myData->addPoints($pression_sejour,"Pression Atmo");

$myData->setSerieOnAxis("T° Exterieure",0);
$myData->setSerieOnAxis("T° Séjour",0);
$myData->setSerieOnAxis("T° chambre",0);

$myData->setSerieOnAxis("Hygro Séjour",1);
$myData->setSerieOnAxis("Hygro Chambre",1);
$myData->setSerieTicks("Hygro Séjour",4);
$myData->setSerieTicks("Hygro Chambre",4);

$myData->setSerieOnAxis("Pression Atmo",2);

$myData->setAbscissa("Timestamp");
$myData->setXAxisName("Time");
// $myData->setXAxisDisplay(AXIS_FORMAT_TIME,"H:i");
$myData->setXAxisDisplay(AXIS_FORMAT_TIME,"d/m");
//$myData->setXAxisDisplay(0,AXIS_FORMAT_CUSTOM,"format_absisse_jour");
$myData->setAxisName(0,"Temperatures en °C");
$myData->setAxisUnit(0,"°");
//temperature
$bornes_axe_ordonnees[0] = array("Min"=>-8,"Max"=>35);
//humidite
$bornes_axe_ordonnees[1] = array("Min"=>30,"Max"=>90);
$myData->setAxisName(1,"Humidite");
$myData->setAxisUnit(1,"%");
$myData->setAxisName(2,"Pression Atmosphérique");
$myData->setAxisUnit(2,"HPa");
$myData->setAxisPosition(2,AXIS_POSITION_RIGHT);

$myPicture = new pImage(1250,550,$myData);
//$Settings = array("R"=>48, "G"=>124, "B"=>183, "Dash"=>1, "DashR"=>68, "DashG"=>144, "DashB"=>203);
//$myPicture->drawFilledRectangle(0,0,1200,500,$Settings);

$Settings = array("StartR"=>48, "StartG"=>124, "StartB"=>183, "EndR"=>33, "EndG"=>86, "EndB"=>128, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,1250,550,DIRECTION_VERTICAL,$Settings);

//$myPicture->drawRectangle(0,0,1199,499,array("R"=>0,"G"=>0,"B"=>0));

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

$myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
, "R"=>255, "G"=>255, "B"=>255);
$myPicture->drawText(350,25,"Météo Nantaise",$TextSettings);

$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(110,50,1160,500);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/Forgotte.ttf","FontSize"=>14));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50,
"TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50,
"LabelRotation"=>45,
 "CycleBackground"=>1,
 "DrawXLines"=>1,
 "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50,
 "DrawYLines"=>ALL,
 "LabelSkip"=>3//,
// "Mode"=>SCALE_MODE_MANUAL,
// "ManualScale"=>$bornes_axe_ordonnees
);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Config = "";
$myPicture->drawSplineChart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/Forgotte.ttf", "FontSize"=>14, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
, "Mode"=>LEGEND_HORIZONTAL
);
$myPicture->drawLegend(563,16,$Config);

//$myPicture->stroke();

$myPicture->render("tmp/graphe_tm.png");

echo "<img src='tmp/graphe_tm.png' alt='graphe'/>";

}


function electrique(){

//initialisation des variables tableau
$timestamp="";
$conso="";

$sql  = mysql_query("SELECT TIMESTAMP(CONCAT(YEAR(date_histo ),'-',MONTH(date_histo ),'-',DAY(date_histo ),' ',HOUR(date_histo ),':',MINUTE(date_histo ))),
					date_histo, AVG(valeur1),MAX(valeur1),MIN(valeur1)
					FROM historique_donnees
					WHERE id_objet = 2
					AND date_histo >  DATE_SUB(NOW( ), INTERVAL 1 DAY)
					GROUP BY YEAR( date_histo ) , MONTH( date_histo ),DAY( date_histo ),HOUR( date_histo ) , MINUTE( date_histo ), id_objet
					HAVING MINUTE( date_histo ) IN ( 00, 15, 30, 45 )
					ORDER BY date_histo");
while(list($date_histo,$date_histo2,$conso_sql,$conso_sql_max,$conso_sql_min) = mysql_fetch_array($sql))
{
	$timestamp[]   = strtotime($date_histo);
	$conso[]   = $conso_sql;
	$conso_max[]   = $conso_sql_max;
	$conso_min[]   = $conso_sql_min;
}
 
$myData = new pData();  
$myData->addPoints($timestamp,"Timestamp");
$myData->addPoints($conso,"Consommation Instantanée Moyenne");
$myData->addPoints($conso_max,"Conso. Inst. Max");
$myData->addPoints($conso_min,"Conso. Inst. Min");

$myData->setSerieOnAxis("Consommation Instantanée",0);
$myData->setSerieOnAxis("Conso. Inst. Max",0);
$myData->setSerieOnAxis("Conso. Inst. Min",0);

$myData->setAbscissa("Timestamp");
$myData->setXAxisName("Time");
$myData->setXAxisDisplay(AXIS_FORMAT_TIME,"H\h");
$myData->setAxisName(0,"Consommation Instantanée");
$myData->setAxisUnit(0,"W");

$myPicture = new pImage(1250,550,$myData);

$Settings = array("StartR"=>48, "StartG"=>124, "StartB"=>183, "EndR"=>33, "EndG"=>86, "EndB"=>128, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,1250,550,DIRECTION_VERTICAL,$Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

$myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
, "R"=>255, "G"=>255, "B"=>255);
$myPicture->drawText(350,25,"Consommation éléctrique",$TextSettings);

$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(110,50,1160,500);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/Forgotte.ttf","FontSize"=>14));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50,
"TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50,
"LabelRotation"=>0,
 "CycleBackground"=>1,
 "DrawXLines"=>1,
 "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50,
 "DrawYLines"=>ALL,
 "LabelSkip"=>3
);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Config = "";
$myPicture->drawSplineChart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/Forgotte.ttf", "FontSize"=>14, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
, "Mode"=>LEGEND_HORIZONTAL
);
$myPicture->drawLegend(563,16,$Config);


$myPicture->render("tmp/graphe_ej.png");

echo "<img src='tmp/graphe_ej.png' alt='graphe'/>";

}

function electrique_semaine(){

//initialisation des variables tableau
$timestamp="";
$conso="";

$sql  = mysql_query("SELECT TIMESTAMP(CONCAT(YEAR(date_histo ),'-',MONTH(date_histo ),'-',DAY(date_histo ),' ',HOUR(date_histo ),':',MINUTE(date_histo ))),
					date_histo, AVG(valeur1),MAX(valeur1),MIN(valeur1)
					FROM historique_donnees
					WHERE id_objet = 2
					AND date_histo >  DATE_SUB(NOW( ), INTERVAL 7 DAY)
					GROUP BY YEAR( date_histo ) , MONTH( date_histo ) , DAY( date_histo ) , HOUR( date_histo ),  id_objet
					ORDER BY date_histo");
while(list($date_histo,$date_histo2,$conso_sql,$conso_sql_max,$conso_sql_min) = mysql_fetch_array($sql))
{
	$timestamp[]   = strtotime($date_histo);
	$conso[]   = $conso_sql;
	$conso_max[]   = $conso_sql_max;
	$conso_min[]   = $conso_sql_min;
}
 
$myData = new pData();  
$myData->addPoints($timestamp,"Timestamp");
$myData->addPoints($conso,"Consommation Instantanée Moyenne");
$myData->addPoints($conso_max,"Conso. Inst. Max");
$myData->addPoints($conso_min,"Conso. Inst. Min");

$myData->setSerieOnAxis("Consommation Instantanée",0);
$myData->setSerieOnAxis("Conso. Inst. Max",0);
$myData->setSerieOnAxis("Conso. Inst. Min",0);

$myData->setAbscissa("Timestamp");
$myData->setXAxisName("Time");
$myData->setXAxisDisplay(AXIS_FORMAT_TIME,"D");
$myData->setAxisName(0,"Consommation Instantanée");
$myData->setAxisUnit(0,"W");

$myPicture = new pImage(1250,550,$myData);

$Settings = array("StartR"=>48, "StartG"=>124, "StartB"=>183, "EndR"=>33, "EndG"=>86, "EndB"=>128, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,1250,550,DIRECTION_VERTICAL,$Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

$myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
, "R"=>255, "G"=>255, "B"=>255);
$myPicture->drawText(350,25,"Consommation éléctrique",$TextSettings);

$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(110,50,1160,500);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/Forgotte.ttf","FontSize"=>14));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50,
"TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50,
"LabelRotation"=>0,
 "CycleBackground"=>1,
 "DrawXLines"=>1,
 "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50,
 "DrawYLines"=>ALL,
 "LabelSkip"=>23
);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Config = "";
$myPicture->drawSplineChart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/Forgotte.ttf", "FontSize"=>14, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
, "Mode"=>LEGEND_HORIZONTAL
);
$myPicture->drawLegend(563,16,$Config);


$myPicture->render("tmp/graphe_es.png");

echo "<img src='tmp/graphe_es.png' alt='graphe'/>";

}

function electrique_mois(){

//initialisation des variables tableau
$timestamp="";
$conso="";

$sql  = mysql_query("SELECT TIMESTAMP(CONCAT(YEAR(date_histo ),'-',MONTH(date_histo ),'-',DAY(date_histo ),' ',HOUR(date_histo ),':00')),
					date_histo, AVG(valeur1),MAX(valeur1),MIN(valeur1)
					FROM historique_donnees
					WHERE id_objet = 2
					AND date_histo >  DATE_SUB(NOW( ), INTERVAL 31 DAY)
					GROUP BY YEAR( date_histo ) , MONTH( date_histo ) , DAY( date_histo ),  HOUR( date_histo ),  id_objet
					HAVING HOUR( date_histo ) IN ( 00, 06, 12, 18 ) 
					ORDER BY date_histo");
while(list($date_histo,$date_histo2,$conso_sql,$conso_sql_max,$conso_sql_min) = mysql_fetch_array($sql))
{
	$timestamp[]   = strtotime($date_histo);
	$conso[]   = $conso_sql;
	$conso_max[]   = $conso_sql_max;
	$conso_min[]   = $conso_sql_min;
}
 
$myData = new pData();  
$myData->addPoints($timestamp,"Timestamp");
$myData->addPoints($conso,"Consommation Instantanée Moyenne");
$myData->addPoints($conso_max,"Conso. Inst. Max");
$myData->addPoints($conso_min,"Conso. Inst. Min");

$myData->setSerieOnAxis("Consommation Instantanée",0);
$myData->setSerieOnAxis("Conso. Inst. Max",0);
$myData->setSerieOnAxis("Conso. Inst. Min",0);

$myData->setAbscissa("Timestamp");
$myData->setXAxisName("Time");
$myData->setXAxisDisplay(AXIS_FORMAT_TIME,"d/m");
$myData->setAxisName(0,"Consommation Instantanée");
$myData->setAxisUnit(0,"W");

$myPicture = new pImage(1250,550,$myData);

$Settings = array("StartR"=>48, "StartG"=>124, "StartB"=>183, "EndR"=>33, "EndG"=>86, "EndB"=>128, "Alpha"=>50);
$myPicture->drawGradientArea(0,0,1250,550,DIRECTION_VERTICAL,$Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>20));

$myPicture->setFontProperties(array("FontName"=>"fonts/Forgotte.ttf","FontSize"=>18));
$TextSettings = array("Align"=>TEXT_ALIGN_MIDDLEMIDDLE
, "R"=>255, "G"=>255, "B"=>255);
$myPicture->drawText(350,25,"Consommation éléctrique",$TextSettings);

$myPicture->setShadow(FALSE);
$myPicture->setGraphArea(110,50,1160,500);
$myPicture->setFontProperties(array("R"=>0,"G"=>0,"B"=>0,"FontName"=>"fonts/Forgotte.ttf","FontSize"=>14));

$Settings = array("Pos"=>SCALE_POS_LEFTRIGHT
, "Mode"=>SCALE_MODE_FLOATING
, "LabelingMethod"=>LABELING_ALL
, "GridR"=>255, "GridG"=>255, "GridB"=>255, "GridAlpha"=>50,
"TickR"=>0, "TickG"=>0, "TickB"=>0, "TickAlpha"=>50,
"LabelRotation"=>45,
 "CycleBackground"=>1,
 "DrawXLines"=>1,
 "DrawSubTicks"=>1, "SubTickR"=>255, "SubTickG"=>0, "SubTickB"=>0, "SubTickAlpha"=>50,
 "DrawYLines"=>ALL,
 "LabelSkip"=>3
);
$myPicture->drawScale($Settings);

$myPicture->setShadow(TRUE,array("X"=>1,"Y"=>1,"R"=>50,"G"=>50,"B"=>50,"Alpha"=>10));

$Config = "";
$myPicture->drawSplineChart($Config);

$Config = array("FontR"=>0, "FontG"=>0, "FontB"=>0, "FontName"=>"fonts/Forgotte.ttf", "FontSize"=>14, "Margin"=>6, "Alpha"=>30, "BoxSize"=>5, "Style"=>LEGEND_NOBORDER
, "Mode"=>LEGEND_HORIZONTAL
);
$myPicture->drawLegend(563,16,$Config);


$myPicture->render("tmp/graphe_em.png");

echo "<img src='tmp/graphe_em.png' alt='graphe'/>";

}

//ajoute des entrées VOID dans le cas où le capteur n'a pas renvoyé d'info durant une période
function combler_les_trous($temps,$valeurs){

	foreach ($temps as $key => $tps){
		if(!isset($valeurs[$key]) || $valeurs[$key] == "") $valeurs[$key] = VOID;
	}
	
	ksort($valeurs);
	return $valeurs;

}

?>