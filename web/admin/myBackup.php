<?php
	session_start();
	
	include "config/config.bdd.url.php"; 
	include "config/root.config.php"; 
	include "include/globale.inc.php";
	if (testuseradmin()==true){
		
/*******************************************

	GENERATION DU FICHIER XML DE SAUVEGARDE

*******************************************/
		if (isset($_POST['export'])){
			print mainmenu();	
			saveBDD($bdd);
			
			echo "<p align='center'><a href=\"backup/".date("YmdHis")."_".$bdd.".xml\" target=\"_blank\">voir le fichier</a></p>";
/*******************************************

	RESTAURATION DE LA BDD

*******************************************/

		}else if(isset($_POST['restore'])){
			print mainmenu();	
			
			saveBDD($bdd);
			include "include/XMLreader.php";
	
			$fichierXml="backup/".$_POST['backup_file'];
			$tab=xmlEnTableau($fichierXml);
			for($xx=0;$xx<count($tab);$xx++){
				$tabName=$tab[$xx]["attributs_xml"]["name"];
				
				empty_table($tabName);
				
				for($rr=0;$rr<count($tab[$xx]["valeur_xml"]);$rr++){
					$bufferQueryString="";
					if(isset($tab[$xx]["valeur_xml"][$rr])){
						for($tt=0;$tt<count($tab[$xx]["valeur_xml"][$rr]);$tt++){
							$type=$tab[$xx]["valeur_xml"][$rr][$tt]["attributs_xml"]["type"];
							if ($type=="string" || $type=="blob" || $type="datetime"){
								$bufferQueryString.="\"".addslashes(html_entity_decode(utf8_decode($tab[$xx]["valeur_xml"][$rr][$tt]["valeur_xml"])))."\",";
							}else{
								$bufferQueryString.=html_entity_decode(utf8_decode($tab[$xx]["valeur_xml"][$rr][$tt]["valeur_xml"])).",";
							}
							
						}
					}
					
					
					if ($bufferQueryString!=""){
						if (substr($bufferQueryString,strlen($bufferQueryString)-1,strlen($bufferQueryString))==","){
							$bufferQueryString=substr($bufferQueryString,0,strlen($bufferQueryString)-1);
						}
						
						$bufferQuery="INSERT INTO ".$tabName." VALUES (";
						
						$bufferQuery.=$bufferQueryString;
						$bufferQuery.=");\n";
						
						if (@mysql_query($bufferQuery)){				
							"> OK....<br/>\n";
						}else{
							echo "<span style=\"color:red;\">erreur ".mysql_error()."<br/></span>\n";
						}
						
					}
					
				}
				
				
			}
			
			
			//print_r($tab);
			
/*******************************************

	TELCHARGEMENT

*******************************************/

		}else if(isset($_POST['download'])){
			
			print "<META HTTP-EQUIV=\"Refresh\" CONTENT=\"0;URL=backup/".$_POST['backup_file']."\">";
			
/*******************************************

	IMPORT/UPLOAD D'UN FICHIER

*******************************************/

		}else if(isset($_POST['import'])){
			print mainmenu();
			$target= "backup/";
			
			$tmp= $_FILES['fichier']['tmp_name'];	
				
			$chemin = $target.$_FILES['fichier']['name'];
			if(@move_uploaded_file($tmp,$chemin)){
					@chmod($chemin,0644);
			}else{
				echo "erreur de chargement ...";
			}
		}else{
			print mainmenu();
		}
	}else{
		echo "vous n'avez pas d'autorisation";
	}
	
	
	function listBackup(){
		$buffer="";
		$rep="backup";
		$dir = opendir($rep);
		$count=0;
		$buffer.= "<SELECT NAME='backup_file' class='listselect'>\n";	
		while ($f = readdir($dir)) {
			$count+=1;
			 if(is_file($rep.$f) || ($count>=3)) {
				$buffer.= "<OPTION VALUE='$f'> $f\n";					
			 }
	  	}
	  	echo "</SELECT> ";
		closedir($dir);
		
		return($buffer);
	}
	
	function mainmenu(){
		echo "<html>
		<head>
		<link rel=\"stylesheet\" href=\"".SKIN."/css/css.css\" type=\"text/css\">
		<link rel=\"stylesheet\" href=\"".SKIN."/css/theme.css\" type=\"text/css\">
		</head>
			<body>";
			windowscreate("GESTIONNAIRE DE BASE DE DONN�E",null,null,"debut",0);	
			echo "
				<fieldset>
					<legend>Sauvegarder la base de donn�e dans son etat actuel</legend>
					<form method='Post' Action=''>
						<P align='center'>
							<input type='submit' value='Sauvegarder'>
							<input type='hidden' name='export'>
						</p>
					</form>
				</fieldset>	
				<fieldset>
					<legend>Importer un fichier de backup Xml</legend>
					<form enctype=\"multipart/form-data\" method='Post' Action=''>
						<p align='center'>
							<input name='fichier' type='file' />
							<input type='submit' value='importBDD'/>
							<input type='hidden' name='import'/>
						</p>
					</form>
				</fieldset>
				<fieldset>
					<legend>Selectionner le fichier � utiliser pour restaurer le base</legend>
						<form method='Post' Action=''>
							<p align='center'>";
								echo ListBackup();
								echo "<input type='hidden' name='restore'/> <input type='submit' value='Restaurer'/>
							</p>
						</form>
				</fieldset>
				<fieldset>
					<legend>Telecharger un export Xml de la base de donn�e</legend>
						<form method='Post' Action=''>
							<p align='center'>";
								echo ListBackup();
								echo "<input type='hidden' name='download'/> <input type='submit' value='Telecharger'/>
							</p>
						</form>
				</fieldset>";
				windowscreate(null,null,null,null,null);
				echo "
			</body>
		</html>";
		
	}
	
	function saveBDD($bdd){
		if ($result = mysql_list_tables($bdd)){
			$buffer= "<?xml version=\"1.0\"?>\n";
			$buffer.= "<bdd>\n";
			$num_rows = mysql_num_rows($result);
			
			for ($i = 0; $i < $num_rows; $i++) {
				$NameTab=mysql_tablename($result, $i);
				$buffer.= "\t<table name=\"".$NameTab."\">\n";
				$query = "SELECT * FROM ".$NameTab;
				$resultsQuery = mysql_query($query);
				/*ROWS*/
				while ($row = mysql_fetch_array($resultsQuery)){ 
					/*FIELD*/
					$numberfields = mysql_num_fields($resultsQuery);
					$buffer.= "\t\t<row>\n";
					for ($x=0; $x<$numberfields ; $x++ ) {
						$type  = mysql_field_type($resultsQuery, $x);
						$name  = mysql_field_name($resultsQuery, $x);
						$len  = mysql_field_len($resultsQuery, $x);
						$flags = mysql_field_flags($resultsQuery, $x);
						$FieldName=mysql_field_name($resultsQuery, $x);
						$buffer.= "\t\t\t<field  name=\"". $name."\" flags=\"". $flags."\" len=\"". $len."\" type=\"". $type."\">\n";
						$buffer.= "\t\t\t\t<![CDATA[".utf8_encode($row[$name])."]]>\n";
						$buffer.= "\t\t\t</field>\n";
					}
					$buffer.= "\t\t</row>\n";
				}
				$buffer.= "\t</table>\n";			
			}
			$buffer.= "</bdd>\n";
			
			$fp = @fopen("backup/".date("YmdHis")."_".$bdd.".xml","w+");
		
			if ($fp==false){
				echo "<center>Le fichier existe d�j�.";
				echo "<p><a href=\"backup/".date("YmdHis")."_".$bdd.".xml\">voir le fichier</a></p></center>";
			}else{
				fseek($fp,0);
				fputs($fp,$buffer);
				fclose($fp);
				@chmod($fp,0777);	
			}			
		}else{
			echo "Erreur : impossible de lister les bases de donn�es\n";
			echo 'Erreur MySQL : ' . mysql_error();
			exit;
		}
	}
	
	function empty_table($tableName){
		$query = sprintf("delete from %s" , $tableName);
		$result = mysql_query($query);
		return $result;
	} 
?>

