<html>
	<head>
		<title>DOCUMENTATION FUNKYLAB V2.1</title>
		<script language="javascript">
			<!--
			function imprimer(){
				if (navigator.appName == "Netscape") {
					window.print() ;
				}else {
					var n = '<OBJECT ID="navi1" WIDTH=0 HEIGHT=0 CLAS"CLSID:8856F961-340A-11D0-A96B-00C04FD705A2"></OBJECT>';
					document.body.insertAdjacentHTML('beforeEnd', n);
					navi1.ExecWB(6, 2);
				}
			}
			// -->
		</script>
		<style>
			body{
				text-align:justify;
				font-familly:Arial;
			}
			
			td{
				text-align:justify;
				font-familly:Arial;
			}
		</style>
	</head>
	<body>
		<p align='center'><a class=i href="javascript:imprimer();">Imprimer cette page</a></p>

		<?
			include "../config/config.bdd.url.php";
			include "../config/root.config.php";
		
		echo "
		<table border=\"0\" width=\"800\">
			<tr>
				<td>
					<B>DOCUMENTATION FUNKYLAB V2.1</B>";
					echo "<BR>
					AUTEUR : Cyril Pereira<BR>
					DATE : 05 / 08 / 2006<BR>
					";
					//-----------------------------------------------------------------
					// SOMMAIRE
					//-----------------------------------------------------------------	
					echo "<p><b>SOMMAIRE : </b></p>";
					$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=0 ORDER BY titre"); 
					echo "<ul>";
				    while ($row = mysql_fetch_array($res_modiftype)){  
					    $id= $row['id'];   
					    $titrehelp = $row['titre'];  
					    echo "$titrehelp<ul>";
				    	childtypesommaire($id);
				    	echo "</ul><br/>";
					}	
					echo "</ul>";
			
			function childtypesommaire($id){
				$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=$id ORDER BY titre"); 
				while ($row_child = mysql_fetch_array($res_modiftype)){   
					$id= $row_child['id'];   
					if (trim(stripslashes($row_child['page']))==""){
					    echo $row_child['titre'];
					    echo "<ul>";
				    		childtypesommaire($id);
				    	echo "</ul>";
					}else{	   
						$titrehelp = stripslashes($row_child['titre']);  			
						echo $titrehelp."<br/>\n";
					}
				}
			}
			
			//-----------------------------------------------------------------
			// PAGE
			//-----------------------------------------------------------------	
			$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=0 ORDER BY titre"); 
			
			echo "<ul>";
			    while ($row = mysql_fetch_array($res_modiftype)){  
				    $id= $row['id'];   
				    $titrehelp = $row['titre'];  
				    echo "<b><u>$titrehelp</u></b>";
			    	childpage($id);
				} 	
			echo "</ul>";
			
			function childpage($id){
				$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=$id ORDER BY titre"); 
				 echo"<ul>";
				while ($row_child = mysql_fetch_array($res_modiftype)){
					$id= $row_child['id'];   
					$titrehelp = stripslashes($row_child['titre']);
					if (trim(stripslashes($row_child['page']))==""){
						 echo "<b><u>$titrehelp</u></b>";
				   		 echo"<ul>";
			    		childpage($id);
			    		echo "</ul>";
					}else{
						echo "<p>";
						displaycontenu($id);
						echo "</p>";
					}
				}
				 echo"</ul>";
			}    	
			    	
			 function displaycontenu($id){
					$res_modiftype=mysql_query("SELECT * FROM help WHERE id=$id"); 
					$row = mysql_fetch_array($res_modiftype) ;
					$pagehelp = $row['page'];	
					$titrehelp = $row['titre'];
					echo "<p class='wintitle'><b>$titrehelp</b></p>";	
					echo "<ul>";
						echo $pagehelp;		 		 
					echo "</ul>";
			 }  
			
		?>
		
						
				</td>
			</tr>
		</table>
	</body>
</html>