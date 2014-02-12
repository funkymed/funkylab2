<?php
	if ($admin==true){
		if (isset($_GET['convertgalerie'])){	
			$dirconvert=$_GET['convertgalerie'];
			$buffer="";		
			$dir = opendir($dirconvert);
			$count=0;
			$error="";
			while ($f = readdir($dir)) {
				$count+=1;
				if ($count>=3){					
					if (is_file($dirset.$f)){							
						if (!strstr($f,"thumb")){							
							$nom=$f;
							$file=$dirconvert.$f;
							$description="";
							$type=$_GET['listgalerie'];	
							$prix="";	
							$tailleX="";
							$tailleY="";
							$motscle="";			
							$date="";	
							$pays="";
							$resolution="";
							$plimus="";
							
							$query="INSERT INTO photo (id,nom,file,description,type,prix,tailleX,tailleY,motscle,date,pays,resolution,plimus) VALUES 
							('','$nom','$file','$description','$type','$prix','$tailleX','$tailleY','$motscle','$date','$pays','$resolution','$plimus')";	
							$resultat=@mysql_query($query);
							
							if ($resultat!="1"){ 
								$error.= "erreur sur le fichier : ".$f."<br/>"; 	
							}
						}
						
					}	
				}
		  	}
		  	echo $error;			
		  	
		}
		
		if (isset($_GET['unzip'])){	
			$dir=$_GET['dir'];	
			$file=decodedirname($_GET['unzip']);
			$test=require_once('megabrowser/inc/pclzip.lib.php');
			
			$archive = new PclZip($dir."/".$file);
			$newfolder=$dir."/".substr($file, 0, strlen($file)-4);
			$return =@mkdir ($newfolder, 0777);		
			if ($archive->extract(PCLZIP_OPT_PATH, ''.$newfolder.'') == 0) {
				die("Error : ".$archive->errorInfo(true));
			}
		}
		
		if (isset($_GET['zipit'])){	
			$dir=$_GET['zipit'];
			$dirset=$_GET['dir'];
			$dirname=$_GET['dirname'];
			$dirname=explode("/",$dirname);
			$dirname=$dirname[0];
			$test=require_once('megabrowser/inc/pclzip.lib.php');
			echo ''.$dir.$dirname.'.zip';
			$archive = new PclZip(''.$dirset.$dirname.'.zip');
			$v_list = $archive->create(''.$dir.'');
			if ($v_list == 0) {
				die("Error : ".$archive->errorInfo(true));
			}
		}	
		
		if (isset($_GET['validrename'])){	
			$newname=$_GET['newname'];
			$oldname=$_GET['oldname'];
			$dir=$_GET['dir'];		
			$return=@rename ( $dir."/".$oldname, $dir."/".$newname);
			if ($return==true){			
				$ext=strtolower(strrchr($oldname, '.'));
				if (($ext==".gif") || ($ext==".jpg") || ($ext==".jpeg")|| ($ext==".png")){
					@unlink($dir."/thumb_".$oldname);	
				}					
				echo "<P class='bug'>renomm� $oldname par $newname</P>";
			}else{
				echo "<P class='bug'>ERREUR </P>";
			}				
		}
	
		if (isset($_GET['delete'])){
			$dirset=$_GET['dir'];	
			$file=$_GET['delete'];
			$return = @unlink($dirset."/".$file);
			$ext=strtolower(strrchr($file, '.'));
			if ($return==true){		
				if (($ext==".gif") || ($ext==".jpg") || ($ext==".jpeg")|| ($ext==".png")){
					@unlink($dirset."/thumb_".$file);	
				}
				echo "<P class='bug'>fichier $file dans le repertoire $dirset effac�</P>";
			}else{
				echo "<P class='bug'>ERREUR sur le fichier $file dans le repertoire $dirset</P>";
			}
		}
		
		if (isset($_GET['deleteDIR'])){	
			$dirset=$_GET['dir'];	
			$dir=$_GET['deleteDIR'];
			EffacerAllFile($dirset."/".$dir);						
		}			
		
		if (isset($_GET['newrep'])){	
			$dirset=$_GET['dir'];	
			$newdirname=trim($_GET['newrep']);
			if ($newdirname!=""){
				$return =@mkdir ($dirset."/".$newdirname, 0777);
				if ($return==true){			
					$erreur=false;
					$msg="Repertoire $newdirname cr�e";
				}else{
					$erreur=true;
					$msg="Le repertoire $newdirname existe d�j�";
				}
			}else{
				$erreur=true;
				$msg="Veuillez entrer le nom d'un repertoire afin de le cr�er";
			}				
			echo "<P class='bug'>$msg</P>";
		}
		
		if (isset($_POST['posted'])){
			$maxparligne=$_POST['maxparligne'];
			$tmp= $_FILES['fichier']['tmp_name'];
			$target=$_POST['target']."/";
			$dirset=$target;
			$workedir=explode("/",$target);
			$countdir=count($workedir);
			for ($AA=0;$AA<=$countdir-3;$AA++){
				$url.=$workedir[$AA]."/";
			}			
			$chemin = $target.$_FILES['fichier']['name'];
			if(move_uploaded_file($tmp,$chemin)){
					@chmod($chemin,0644);
				echo "<P class='bug'>Fichier upload�</P>";
			}else{
				$erreur=true;
				echo "<P class='bug'>ERREUR D'UPLOAD</P>";
			}
		}
	}
?>