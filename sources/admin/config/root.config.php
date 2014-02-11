<?
//==================================================================================================
//	CONFIGURATION DE BASE INCHANGEABLE
//==================================================================================================
	define ("BASEDEFAUT", $bdd);
	define ("ROOT",$url);	
	$basetowork=mysql_connect($host,$user,$pass) or die("Impossible de se connecter à la base de données");
	@mysql_select_db("$bdd") or die("Impossible de se connecter à la base de données");

//==================================================================================================
//	UPDATE DE LA CONFIGURATION
//==================================================================================================

	if (isset($_GET['updateconfig'])){
		$nomsite=addslashes($_GET['nomsite']);
		$skinsite=$_GET['skinsite'];
		$skinadmin=$_GET['skinadmin'];
		if(isset($_GET['filtrecom'])){	$filtrecom=1;	}else{	$filtrecom=0;	}	
		if(isset($_GET['watermark'])){	$watermark=1;	}else{	$watermark=0;	}
		if($_GET['online']==1)		 {	$online="on";	}else{ 	$online="off";	}
		
		$watermark_image=$_GET['minibrowserlist'];
		$watermark_position=$_GET['watermark_position'];
		
		$query="UPDATE config SET nomsite='$nomsite',online='$online',skinsite='$skinsite',skinadmin='$skinadmin',watermark='$watermark',filtrecom='$filtrecom',watermark_image='$watermark_image',watermark_position='$watermark_position' WHERE id=0"; 
		$result=mysql_query($query);
	}

//==================================================================================================
//	LECTURE DE LA CONFIGURATION
//==================================================================================================

	$resconfig=mysql_query("SELECT * FROM config");
	$rowconfig = mysql_fetch_array($resconfig);
	$nomsite=stripslashes($rowconfig['nomsite']);
	$online=$rowconfig['online'];
	$skinsite=$rowconfig['skinsite'];
	$skinadmin=$rowconfig['skinadmin'];
	
	define ("SKIN", "template/".$skinadmin);
	define ("SKINSITE", "../../../template/".$skinsite);
	
	if (isset($_GET['billet'])){
		$id=$_GET['billet'];
		$override=true;
	}
	if (isset($_GET['page'])){
		$id=$_GET['page'];
		$override=true;
	}	
	
	if (isset($override)){
		$res=mysql_query("SELECT type,overrideskin FROM billet WHERE id='$id'");
		$row = mysql_fetch_array($res);
		if ($row['type']=="page" && $row['overrideskin']!="" || $row['type']=="categorie" && $row['overrideskin']!=""){
			$skinsite=$row['overrideskin'];
		}
	}

	
	
	define ("CURRENT_SKIN", "template/".$skinsite);
	
	define ("ROOTURL", ROOT);	
	
	define("WATERMARK",$rowconfig['watermark']);
	define("WATERMARK_IMAGE",$rowconfig['watermark_image']);
	define("WATERMARK_POS",$rowconfig['watermark_position']);
	
	define("FILTRECOM",$rowconfig['filtrecom']);	
	define ("EMAILPOST",$emailpost);
	/*	
	ini_set("SMTP", "localhost");
	ini_set("smtp_port", 25);
	ini_set("sendmail_from ", EMAILPOST); 
	*/
?>


