<?
//==================================================================================================
//	UPDATER
//==================================================================================================

	$path="modules/updater/patch/";

	//==================================================================================================
	//	INSTALLER
	//==================================================================================================						
				
	if (isset($_GET['install'])){
		$patch=$path.$_GET['install'];
		
		require_once('../megabrowser/inc/pclzip.lib.php');
		$archive = new PclZip($patch.".lab");
		$target="../";
		if ($archive->extract(PCLZIP_OPT_PATH, ''.$target.'') == 0) {
			die("Error : ".$archive->errorInfo(true));
		}
		
		$file_open=fopen($patch.".txt", 'r');
		$newversion =fread($file_open,1024*8);
		fclose($file_open);
		
		writelog("config/version",$newversion,false);
		writelog("config/logversion","Installation de la version ".$newversion,true);
	}
	
	
	$file_open=fopen("config/version", 'r');
	$version =fread($file_open,1024*8);
	fclose($file_open);
	define ("VERSION", $version);	
	
	//==================================================================================================
	//	DELETE
	//==================================================================================================						
		
	if (isset($_GET['delete'])){
		$filelab=$path.$_GET['delete'].".lab";
		$filetxt=$path.$_GET['delete'].".txt";
		if (is_file($filelab)){
			unlink($filelab);
		}
		if (is_file($filetxt)){
			unlink($filetxt);
		}
	}
	
	//==================================================================================================
	//	CHARGER
	//==================================================================================================						
				
	if (isset($_POST['loadpatch'])){
		$target= "modules/updater/patch/";
		$filelab=$_FILES['fichier']['name'];
		$tmp= $_FILES['fichier']['tmp_name'];		
		$workedir=explode("/",$target);
		$countdir=count($workedir);		
		$chemin = $target.$_FILES['fichier']['name'];
		$ext=strtolower(strrchr($filelab, '.'));	
		
		if(@move_uploaded_file($tmp,$chemin) && $ext==".lab"){
			@chmod($chemin,0644);
			$txtfile=substr($filelab,0,strlen($filelab)-strlen($ext)).".txt";
			require_once('../megabrowser/inc/pclzip.lib.php');
			$archive = new PclZip($target.$filelab);
			if ($archive->extract(PCLZIP_OPT_PATH, ''.$target.'') == 0) {
				die("Error : ".$archive->errorInfo(true));
			}
			unlink($target.$filelab);
			rename($target."_".$filelab,$target.$filelab);
		}else{
			echo "<P class='ALERTEDOUBLON'>ERREUR D'UPLOAD</P>";
		}
	}
	
	//==================================================================================================
	//	LIST DES PATCHS
	//==================================================================================================
		
	if (isset($_GET['list']) || isset($_POST['list'])){	
		if (testuseradmin()==true){
			updater::listupdate();	
			
		}
	}	
?>