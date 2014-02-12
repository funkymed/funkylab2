<?

//==================================================================================================
//	TEMPLATE
//==================================================================================================

	//==================================================================================================
	//	CHARGER
	//==================================================================================================						
				
	if (isset($_POST['loadskin'])){
		$target= "../template/";
			
		$tmp= $_FILES['fichier']['tmp_name'];		
		$workedir=explode("/",$target);
		$countdir=count($workedir);		
		$chemin = $target.$_FILES['fichier']['name'];
		if(@move_uploaded_file($tmp,$chemin)){
				@chmod($chemin,0644);
		}else{
			echo "<P class='ALERTEDOUBLON'>ERREUR D'UPLOAD</P>";
		}
	}		

	//==================================================================================================
	//	OPTMISATION DE LA BASE SQL A LA DEMANDE
	//==================================================================================================
			
	if (isset($_GET['optimisesql'])){
		optimisebase($_GET['optimisesql']);
	}	

	if (isset($_GET['opskin'])){
		$rep= "../template/";
		
		$file=$_GET['skin'];		
		switch($_GET['opskin']){
			//==================================================================================================
			//	DELETE
			//==================================================================================================
			case 1:
				if (file_exists($rep.$file)){
					template::EffacerRepSkin($rep.$file);
				}
				unlink($rep.$file.".zip");
				break;					
			
			//==================================================================================================
			//	SELECTIONNER
			//==================================================================================================
			case 2:
				$result=mysql_query("UPDATE config SET skinsite='$file' WHERE id=0");
				break;					
			
			//==================================================================================================
			//	INSTALLER
			//==================================================================================================
			case 3:				
				$test=require_once('../megabrowser/inc/pclzip.lib.php');
				$file.=".zip";
				$dir=$rep;
				$archive = new PclZip($dir."/".$file);
				$newfolder=$dir."/".substr($file, 0, strlen($file)-4);
				$return =@mkdir ($newfolder, 0777);		
				if ($archive->extract(PCLZIP_OPT_PATH, ''.$newfolder.'') == 0) {
					die("Error : ".$archive->errorInfo(true));
				}
				
				break;					
			
			//==================================================================================================
			//	DESINSTALLER
			//==================================================================================================						
			case 4:
				if (file_exists($rep.$file)){
					template::EffacerRepSkin($rep.$file);
				}
				break;	
		}
	}
		
	//==================================================================================================
	//	LIST DES TEMPLATES
	//==================================================================================================
		
	if (isset($_GET['list']) || isset($_POST['list'])){	
		if (testuseradmin()==true){
			template::listTemplate();	
			
		}
	}	
	
	

?>