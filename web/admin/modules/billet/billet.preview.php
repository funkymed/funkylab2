<?php
//==================================================================================================
//	PREVIEW DE BILLET
//==================================================================================================

	header("Content-type: text/html"); 	
	header("Content-Disposition: filename=index.php\r\n\r\n"); 
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache"); 
	
	include "../../config/config.bdd.url.php";	
	include "../../config/root.config.php";
	include "../../include/globale.inc.php";
	include "../../../includes/class.billet.php"; 	

	$tableglobal="";
	if (isset($_GET['page'])){	
		$pageid=$_GET['page'];
		$restypepage=mysql_query("SELECT * FROM billet WHERE id='$pageid'");
		$rowtypepage = mysql_fetch_array($restypepage);
		$type=$rowtypepage['type'];	
		$option=$rowtypepage['option_element'];	
		
		if ($rowtypepage['visible']==true){
			switch($type){
				case "page":
					$page=Generate_Contenu::createpage($pageid,null);			
					$tableglobal=$page;
					break;
				case "categorie":		
					$page="";
					$option=explode(",",$option);
					if ($option[0]=="croissant"){
						$DESC="";
					}else{
						$DESC="DESC";
					}
					$resultpage=mysql_query("SELECT * FROM billet WHERE parent='$pageid' AND type='page' ORDER BY id $DESC");			
					while ($row = mysql_fetch_array($resultpage)){ 
						$page.=Generate_Contenu::createpage($row['id'],$option[1]);
					}
					$tableglobal=$page;
					break;	
				default:
					$tableglobal="";
					break;	
			}
		}
		
	}
	
//==================================================================================================
//	AFFICHAGE E LA PAGE
//==================================================================================================
		
	$head="
	<html>
		<head>
			<title>PREVIEW</title>
			<link rel=\"stylesheet\" href=\"".ROOTURL.CURRENT_SKIN."/css/theme.css\" type=\"text/css\">	
			<script language=\"javascript\" src=\"scripts/transmenu.js\"></script>	
			<script language=\"JavaScript1.2\">
				function closewin(){
					window.close();
				}
			
				function open_newpopup(bUrl, bName, bWidth, bHeight, bResize){
					var lar=screen.width/2;
					var hau=screen.height/2;
					var lo=lar-bWidth/2;
					var ho=hau-bHeight/2;
					var newFenetre = window.open(bUrl,bName,'directories=no,location=no,toolbar=no,directories=no,menubar=no,resizable='+bResize+',scrollbars=yes,status=no,top='+ho+',left='+lo+',width='+bWidth+',height='+bHeight);
					if (navigator.appVersion.indexOf(\"MSIE 4.0\") < 0) newFenetre.focus();
				}		
			</script>				
		</head>
		<body>
		<p align='center'><a href='javascript:closewin()'>Fermer la fenetre</A></p>
		
		<table align=\"center\"><tr><td align=\"center\">
	";
	
	$foot="
		</td></tr></table>
		<p align='center'><a href='javascript:closewin()'>Fermer la fenetre</A></p>
		</body>
	</html>
	";

	print $head.$tableglobal.$foot;	

?>