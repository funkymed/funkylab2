<? 	
	header("Content-type: text/html"); 	
	header("Content-Disposition: filename=index.php\r\n\r\n"); 
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache"); 	
	
	session_start();
	require_once('config.php');	
	$basetowork=mysql_connect($host,$user,$pass) or die("Impossible de se connecter à la base de données");
	@mysql_select_db("$bdd") or die("Impossible de se connecter à la base de données");

	/**********************************************************************
	|						MEGABROWSER									  |		
	|						PAR CYRIL PEREIRA						      |
	|    					EN AVRIL 2006							      |
	**********************************************************************/
	
	$timedepart= get_micro_time();
	$addurl="";
	$url="";
	
	if ($dirROOT==""){
		$dirROOT=".";
	}
	
	function get_micro_time(){
		list($usec, $sec)=explode(" ",microtime());
		return ((float)$usec+(float)$sec);
	}
		
	//if (isset($_GET['delog'])){ unset($_SESSION["adminbrowser"]); }
	if (isset($_GET['dir'])){	
		$dirset=$_GET['dir'];	
		if (strstr($dirset,"..")){
			echo "<P class='bug'>manipulation incorrect</P>";
			$dirset=$dirROOT;
		}
	}else{
		$dirset=$dirROOT;
	}
		
	/************************** CONFIG PAR DEFAUT ************************/
	$versionmegabrowser="1.0betaRC2";
	
	$credit="
	<P>Megabrowser PHP par <a href='http://www.cyrilpereira.com'>Cyril Pereira</A> (version $versionmegabrowser septembre 2005)</P>
	Icones par <a href='http://www.jairoboudewyn.com' target='_blank'>http://www.jairoboudewyn.com</A><BR>
	PhpConcept Library - Zip Module 2.4 par Vincent Blavet <a href='http://www.phpconcept.net' target='_blank'>http://www.phpconcept.net</A>
	";		
	
	require_once('megabrowser/inc/func.global.php');
	
	if (isset($_POST['login'])){	
		if (($_POST['login']=="") OR ($_POST['pass']=="")){ 
		}else{	
			if (($_POST['login']==$login) AND ($_POST['pass']==$pass)){	
				$_SESSION["adminbrowser"]="adminbrowser";
			}
		}
	}
	if (isset($_SESSION["funkylab_login"])){	
		if (isset($_SESSION['funkylab_admin'])){	
			$admin=true;
		}else{
			$admin=false;
		}
	}else{
		header("HTTP/1.0 404 Not Found");				
		$admin=false;
	}
		
	include "megabrowser/inc/inc.isset.php";
	
	/**** RECUPERATION D'INFORMATION DE BASE ****/
	
	$filefound=false;
	$dirfound=false;						
	if (isset($_GET['maxparligne'])){	$maxparligne=$_GET['maxparligne'];	$addurl="&maxparligne=$maxparligne";}			
	
	$buffer0= "MEGABROWSER V$versionmegabrowser<BR>";
		
	include "megabrowser/inc/inc.form.php";
	
	/**** RECHERCHE D'INFO DANS LE DOSSIER COURANT ****/
	
	$folder = $dirset;				
	$dossier = opendir($folder);
	while (false !== ($Fichier=@readdir($dossier))){	
		if (substr($Fichier,0,1)!="."){
			if (is_dir($dirset."/".$Fichier)){
				$dirs[]=$Fichier.'/';
				$dirfound=true;
			}else{
				$ext=strtolower(strrchr($Fichier, '.'));
				if ($checkext==true){																		
					if (in_array($ext, $extvalid)){
						$testthumb=explode("_",$Fichier);
						if ($testthumb[0]!="thumb"){ //	JE NE COMPTE PAS LES THUMB :)
							$files[]=$Fichier; 
							$filefound=true;
						}
					}
				}else{
					$testthumb=explode("_",$Fichier);
					if ($testthumb[0]!="thumb"){ //	JE NE COMPTE PAS LES THUMB :)
						$files[]=$Fichier; 
						$filefound=true;
					}
				}
			}
		}
	}	
	
	include "megabrowser/inc/inc.gestion.php";	
	
	$buffertexte=file_get_contents("megabrowser/template/template.html");
	
	$buffertexte=ereg_replace("#TITRE#","MEGABROWSER",$buffertexte);
	$buffertexte=ereg_replace("#VERSION#","V".$versionmegabrowser,$buffertexte);
	$buffertexte=ereg_replace("#FORMULAIRES#",$buffer0,$buffertexte);
	$buffertexte=ereg_replace("#RESULTAT#",$buffer,$buffertexte);
	$buffertexte=ereg_replace("#CREDITS#",$credit,$buffertexte);
			
	$timearrive= get_micro_time();
	$temps=number_format($timearrive-$timedepart,2);
	
	$buffertexte=ereg_replace("#TIMECODE#","<BR>Generé en $temps secondes",$buffertexte);
	
	$autorisation=$_SESSION["funkylab_autorisation"];
	if ($autorisation[4]=="1"){
		print $buffertexte;
	}
	
?>