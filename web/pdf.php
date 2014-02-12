<?php
//==================================================================================================
//	LANCEMENT PDF
//==================================================================================================
 require_once('scripts/html2fpdf-3.0.2b/html2fpdf.php');

// activate Output-Buffer:
ob_start();
//==================================================================================================
//	ACTIVATION BDD
//==================================================================================================
	include "admin/config/config.bdd.url.php";
	define ("BASEDEFAUT", $bdd);
	define ("ROOT",$url);
	
	$basetowork=mysql_connect($host,$user,$pass) or die("Impossible de se connecter � la base de donn�es");
	@mysql_select_db("$bdd") or die("Impossible de se connecter � la base de donn�es");

	//==================================================================================================
	//	CONFIG
	//==================================================================================================
	$pageid=$_GET['billet'];	
	$tableglobal="";
	$resconfig=mysql_query("SELECT * FROM config");
	$rowconfig = mysql_fetch_array($resconfig);
	$nomsite=stripslashes($rowconfig['nomsite']);
	$online=$rowconfig['online'];
	$skinsite=$rowconfig['skinsite'];
	$skinadmin=$rowconfig['skinadmin'];
	
	define ("SKIN", "template/".$skinadmin);
	define ("SKINSITE", "../../../template/".$skinsite);
	define ("CURRENT_SKIN", "template/".$skinsite);
	define ("ROOTURL", "http://localhost/funkylab2/");	
	
	define("WATERMARK",$rowconfig['watermark']);
	define("WATERMARK_IMAGE",$rowconfig['watermark_image']);
	define("WATERMARK_POS",$rowconfig['watermark_position']);
	
	include_once ('admin/include/globale.inc.php'); 
	include_once ('includes/class.billet.php'); 
	
	//==================================================================================================
	//	RECUPERATION BASE DE DONNEE
	//==================================================================================================
	$querybillet="SELECT * FROM billet WHERE id='$pageid' AND visible='1'";	
	$restypepage=mysql_query($querybillet);
			
	while ($rowtypepage = mysql_fetch_array($restypepage)){
		$nom=$rowtypepage['nom'];	
		$type=$rowtypepage['type'];	
		$option=$rowtypepage['option_element'];	
		$pageid=$rowtypepage['id'];
	
		switch($type){
			case "page":
				$page=Generate_Contenu::createpage($pageid,null);	
				$tableglobal.=$page;
				break;				
			case "categorie":	
				$page="";
				$option=explode(",",$option);
				if ($option[0]=="croissant"){
					$DESC="";
				}else{
					$DESC="DESC";
				}				
				$LIMIT="LIMIT ".$option[2];				
				$resultpage=mysql_query("SELECT * FROM billet WHERE parent='$pageid' AND type='page' AND visible='1' ORDER BY id $DESC $LIMIT");			
				while ($row = mysql_fetch_array($resultpage)){ 
					$page.=Generate_Contenu::createpage($row['id'],$option[1]);						
				}
				$tableglobal.=$page;
				break;	
			default:
				break;	
		}
	}

	?>
<html>
	<head>
		<title>HTML 2 (F)PDF Project</title>			
		<link rel="stylesheet" type="text/css" href="<? echo CURRENT_SKIN; ?>/css/theme.css">	
	</head>
		<body>
		<h2 align="center"><? echo $nom; ?></h2>

					<? echo strip_tags($tableglobal,'<b><i><u><p><a><table><tr><td>'); ?>
	
	</body>
</html>
	
<?
//==================================================================================================
//	CREATION PAGE
//==================================================================================================

$html=ob_get_contents();
ob_end_clean();
$pdf = new HTML2FPDF();
//$pdf->DisplayPreferences('HideWindowUI');
$pdf->AddPage();
$pdf->WriteHTML($html);
$pdf->Output('doc.pdf','I'); 

//==================================================================================================
//	AFFICHAGE RESULTAT
//==================================================================================================
	
//$pdf->Output();

?>