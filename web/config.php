<?	
	include "admin/config/config.bdd.url.php";	
	$filephp="browser.php";	
	$root=$url;			
	$dirROOT="image";
	$maxparligne=5;							   	// MAXIMUM D'IMAGES PAR LIGNE PAR DEFAUT
	$lenghtname=10;								// LONGUEUR NOM DE FICHIER	
	$iconeW=64;
	$iconeH=64;
	$checkext=false;
	$extvalid=array(
					'.mpg','.mpeg','.avi','.flv','.mov','.rm','.ram','.wmv',	// VIDEO
					'.swf','.doc','.pdf','.txt','.xls','.html','.htm',			// INTERNET
					'.mp3','.midi','.wav','.ogg','.mod','.xm',					// AUDIO
					'.jpg','.jpeg','.gif','.png',								// IMAGE
					'.zip'														// ARCHIVE
	);

?>