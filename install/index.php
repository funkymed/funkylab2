<?
	$version="2.19.7";
	$timedepart= get_micro_time();
	function get_micro_time(){
		list($usec, $sec)=explode(" ",microtime());
		return ((float)$usec+(float)$sec);
	}	
?>

<html>
	<head>
		<title>Installation de Funkylab V2 2005-2006</title>
	</head>
	<style>
		body {
			font: 10pt , Arial;
		}
		input{
			font: 10pt , Arial;
			text-align:center;
			border-width:0;
			background-color:#efefef;
			width:300;
			-moz-border-radius:1em 1em 1em 1em;
		}
		fieldset{
				font: 11px , Arial;
				background-color:#FFFFFF;
				text-align:center;
				border-width:4;
				border-style:solid;
				border-color:#efefef;
				-moz-border-radius:1em 1em 1em 1em;
		}	
		legend{		
			font: 15px , Arial;
			font-weight:bold;
			color:#FFBB00;
		}
		.funky{		
			font: 20pt , Arial;
			font-weight:bold;
			color:#FFBB00;
		}
		.lab{		
			font: 20pt , Arial;
			font-weight:bold;
			color:#FF0000;
		}
		.query{
				font: 11px , Arial;
				background-color:#FFFFFF;
				text-align:center;
				border-width:4;
				border-style:solid;
				border-color:#efefef;
				-moz-border-radius:1em 1em 1em 1em;
		}
		a {
			font: 10pt , Arial;	
		}
		
		a:hover {
			color:red;
			font: 10pt , Arial;		
			
		}
	</style>
	<body>
		<table align="center">
			<tr>
				<td align="center" style="font-size:10pt;">
					<span class="funky">Funky</span><span class="lab">lab</span> V2<br/>
					Cyril Pereira
					<p align="center"><a href="http://www.funkylab.info">http://www.funkylab.info</a>
					<?	
					//----------------------------------------------------------------------
					// INSTALLATION AUTOMATIQUE DE FUNKYLAB
					//----------------------------------------------------------------------	
				
					if (isset($_GET['install'])){
						echo"
						<table class=\"query\">
							<tr>
								<td>";
								
									if (!is_file("../admin/config/config.bdd.url.php")){
										
										//----------------------------------------------------------------------
										// CREATION BDD
										//----------------------------------------------------------------------				
										
										operation_db($_GET['host'],$_GET['user'],$_GET['pass'],$_GET['bdd']);
										
										//----------------------------------------------------------------------
										// DEZIP DES FICHIERS
										//----------------------------------------------------------------------	
								
										$dir="../";	
										$file="data/funkylab2.zip";
										$test=require_once('lib/pclzip.lib.php');
										$archive = new PclZip($file);
										$newfolder=$dir;
										if ($archive->extract(PCLZIP_OPT_PATH, ''.$newfolder.'') == 0) {
											die("Error : ".$archive->errorInfo(true));
										}else{
											echo "DECOMPACTAGE DES FICHIERS : OK<br/>\n";
											writelog("../admin/config/version",$version,false);
											writelog("../admin/config/logversion","Création de la base de donnée",true);
											writelog("../admin/config/logversion","Installation des fichiers",true);
										}
										
										$host="host";
										$user="user";
										$pass="pass";
										$bdd="bdd";
										$url="url";
										$emailpost="emailpost";
										
										$buffer="<?		
											$$host = \"".$_GET['host']."\";
											$$user = \"".$_GET['user']."\";
											$$pass = \"".$_GET['pass']."\";
											$$bdd  = \"".$_GET['bdd']."\";			
											$$url  = \"".$_GET['url']."/\";
											$$emailpost  = \"".$_GET['email']."/\";
										?>";
										
										$fp = @fopen("../admin/config/config.bdd.url.php","x+");
										
										if ($fp==false){
											echo "Le fichier existe déjà.";
										}else{
											fseek($fp,0);
											fputs($fp,$buffer);
											fclose($fp);
											@chmod($fp,0644);
											writelog("../admin/config/logversion","Configuration du de la base de données",true);
											writelog("../admin/config/logversion","Installation terminée",true);
											echo "FICHIER DE CONFIG : OK<br/>\n											
											<p>Veuillez maintenant effacer le repertoire install à la racine de votre site.<br/>
											Pour vous connecter dans la partie d'administration veuillez <a href=\"../admin\">cliquez ici</a><br/>
											Login : <b>Funkylab</b> Mot de passe : <b>admin</b></br>
											Pour voir le site <a href=\"../\">cliquez ici</a></p>.
											";
										}	
										$timearrive= get_micro_time();
										$temps=$timearrive-$timedepart;
										$temps=number_format($timearrive-$timedepart,2);											
										echo "<p>Funkylab s'est déployé en $temps secondes.</p>";
									}else{
										echo "Fichier déjà configuré !";
									}
								
									echo "
									</td>
								</tr>
							</table>";
						}else{
							if (is_file("../admin/config/config.bdd.url.php")){
								$disable="disabled=disabled";
							}else{
								$disable="";
							}
							
							$urlcheck=$_SERVER['REDIRECT_URL'];
							$urlcheck=substr($urlcheck,0,strlen($urlcheck)-strlen("/install/"));
							$root="http://".$_SERVER['SERVER_NAME'].$urlcheck;
							echo"
							<form action=\"index.php\" method=\"GET\">
								<fieldset>
									<legend>Adresse du site</legend>
									<table>
										<tr>
											<td>url</td><td><input type=\"test\" name=\"url\" value=\"$root\" $disable></td>
										</tr>
									</table>
								</fieldset>
								<fieldset>
									<legend>Base de donnée</legend>
									<table>
										<tr>
											<td>host</td><td><input type=\"test\" name=\"host\" value=\"Lien SQL\" $disable></td>
										</tr>
										<tr>
											<td>user</td><td><input type=\"test\" name=\"user\" value=\"Login SQL\" $disable></td>
										</tr>
										<tr>
											<td>pass</td><td><input type=\"test\" name=\"pass\" value=\"Mot de passe SQL\" $disable></td>
										</tr>
										<tr>
											<td>bdd</td><td><input type=\"test\" name=\"bdd\" value=\"Table base de donnée\" $disable></td>
										</tr>
										<tr>
											<td>email</td><td><input type=\"test\" name=\"email\" value=\"Email de l'administrateur\" $disable></td>
										</tr>
									</table>							
								</fieldset>
								<br/>
								<input type=\"submit\" value=\"install\" $disable>	
								<input type=\"hidden\" name=\"install\">
							</form>";
						}
		
						//----------------------------------------------------------------------
						// CREATION BDD 
						//----------------------------------------------------------------------	
							
						function operation_db($host,$user,$pass,$newbdd){
									
							$basetowork=@mysql_connect($host,$user,$pass) or die ("Impossible de se connecter à la base de données");			
								
							$query  = "CREATE DATABASE IF NOT EXISTS $newbdd";
							@mysql_query($query);
							
							@mysql_select_db($newbdd) or die('Cannot select database'); 
							$filename = "data/bddfunkylab2.sql" ;
							
							$dataFile = fopen( $filename, "r" );
							if ($dataFile){		
								$ligne=0;		
								while (!feof($dataFile)){
									$ligne++;
									$buffer=trim(fgets($dataFile, 4096));
									if ($buffer!=""){
										if (@mysql_query($buffer)){				
											"> OK....<br/>\n";
										}else{
											$varecho=mysql_error();
											echo "<span style=\"color:red;\">erreur ligne $ligne<br/>\n$varecho<br/></span>\n";
										}
									}
								}
								fclose($dataFile);
							}else{
								die( "fopen failed for $filename" ) ;
							}
						}
						
						function writelog($file,$log,$date){
							if ($date==true){
								$log=date('d/m/Y')." a ".date('H:i:s')." ".$log."\n";
								$file_open=fopen($file, 'a');
								fwrite ($file_open, $log);
							}else{
								$file_open=fopen($file, 'w');
								fwrite ($file_open, $log);
							}
							fclose($file_open);
						}
					?>
				</td>
			</tr>
		</table>
	</body>
</html>