<?
	
	if ($_GET['help']==""){
		$titrehelp = "Page d'aide en ligne";
		$pagehelp = "Le menu à gauche vous servira à trouver une solution à votre probleme.";
	}else{
		$id_help=$_GET['help'];
		$txt="SELECT * FROM help WHERE id='$id_help'";
	
		$res_modiftype=mysql_query($txt);
		$row = mysql_fetch_array($res_modiftype);  	 
		$titrehelp = $row['titre'];
		$pagehelp = $row['page'];
	}
	
		windowscreate("Documentation",null,null,"debut",null);
	?>


<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=90%>
	<TR >
		<TD>
			<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
			
				<TR>
					<TD>
						<TABLE border=0  class='loginbox' align='center' width=100% cellspacing="15" cellpadding="0">
							<TR>
								<TD valign='middle' align='center' class='wintitle'>
								<? echo "<IMG SRC='",SKIN,"/images/aide.png'>"; ?>
								<BR>AIDE</TD>
								<TD valign='middle' BGCOLOR=#000000 width=2 rowspan=3 class='winline'></TD>
								<TD valign=top rowspan=2>
								
								<IFRAME class="userbox" width="100%" 
								 height="300"
								 scrolling="auto" 
								 frameborder="0" 
								 name="frame" 
								 src ="help/welcome.php"  
								>
								</IFRAME>
								</TD>
							</TR>
							<TR>
								<TD ID=demo1 width=100 BGCOLOR=#F7F3F7 valign=top></TD>
							</TR>
						</TABLE>
					</TD>
				</TR>
			</TABLE>
		</TD>
	</TR>
</TABLE>

<P align='center'><a href='help/imprimeaide.php' target='_blank'>Version imprimable</a></P>

<?
		
	windowscreate(null,null,null,null,null);

	$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=0 ORDER BY titre"); 
	echo "<SCRIPT LANGUAGE=\"JavaScript\">
		var demoMenu =
		[
		    ";
		    while ($row = mysql_fetch_array($res_modiftype)){  
			    $id= $row['id'];   
			    $titrehelp = $row['titre'];    	  
		    	echo "[null, '$titrehelp', null, null, 'description',\n";
		    	childtype($id);
		    	echo"],";
	    	}
		echo"];	
		ctDraw ('demo1', demoMenu, ctThemeLibrary, 'ThemeLibrary', 0, 0);
	</SCRIPT>";	

		
	function childtype($id){
		$res_modiftype=mysql_query("SELECT * FROM help WHERE parent=$id ORDER BY titre"); 
		while ($row_child = mysql_fetch_array($res_modiftype)){   
			$id= $row_child['id'];   
			$titrehelp = addslashes($row_child['titre']);
			if ($row_child['page']==""){
				echo "[null, '$titrehelp', null, null, 'description',\n";
		    	childtype($id);
		    	echo"],";
			}else{
			echo "[null, '$titrehelp', 'help/page.php?id=$id', 'frame', 'description'],\n";
			}
		    	
		}
	}
?>