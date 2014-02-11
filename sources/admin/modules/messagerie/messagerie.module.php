<?

class messagerie
{
	function listmesssage(){
		windowscreate("MESSAGERIE",null,null,"debut",0);
			echo "
			<FORM ACTION='index.php' method='GET' >
				<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
					<TR class='windcontenu3'>
						<TD></TD><TD></TD><TD>De</TD><TD>Objet</TD><TD>DATE</TD><TD>EFFACER</TD></TR>
					<TR class='winline'>
						<TD colspan=8></TD>
					</TR>";
					$iduser=$_SESSION["funkylab_id"];
					$res=mysql_query("SELECT * FROM messagerie WHERE recepteur=$iduser ORDER BY id DESC");
					$color=0;
					while ($row = mysql_fetch_array($res)) { 
						$id=$row['id'];
						$from=$row['fromqui'];	
						$recepteur=$row['recepteur'];
						$date=$row['date_envois'];
						$objet=$row['objet'];
						$message=$row['message'];
						$lu=$row['lu'];
						
						$color++;
						if ($color % 2 == 0) {
							 $class="windcontenu"; 
						}else{ 
							$class="windcontenu2"; 
						}
						if (isset($_GET['select'])){
							$select=$_GET['select'];
						}else{
							$select=0;
						}			
						$disable="";
						$check="";
						if ((isset($_GET['id'])) && ($select!=2)){
							$disable="DISABLED";
							if ($_GET['id']==$id){
								$check="checked=\"CHECKED\"";
								$class="winOVER";
							}		
						}		
							

						$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer le message de ".recupeall($from,"admin","prenom")." ".recupeall($from,"admin","nom")." ?','index.php?cat=253&select=2&id=$id&list'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
								
						echo"<TR  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">	
							<TD width=20 height=20><INPUT type=radio name='id' value='$id' $disable $check></TD>			
							<TD width=128><a href='index.php?cat=253&select=1&id=$id&list'>",messagerie::silu($lu),"</A></TD>
							<TD><a href='index.php?cat=253&select=ADDMSG&envoyerA=$from&list'>",recupeall($from,"admin","nom")," ",recupeall($from,"admin","prenom"),"</A></TD>
							<TD>",reduitletext($objet,40),"</TD>
							<TD width=128>$date</TD>
							<TD width=128>$delete</TD>
						</TR>
						";
					}
					echo "
					<TR class='winline'>
						<TD colspan=8></TD>
					</TR>
					<TR class='windcontenu3'>
						<TD colspan=8 align='right'>
							<SELECT NAME='select' class='listselect'>\n
								<OPTION VALUE='ADDMSG'>ENVOYER
								<OPTION VALUE='1'>LIRE	
								<OPTION VALUE='2'>EFFACER
							</select>
							<INPUT TYPE='submit' VALUE='ok' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
						</TD>
					</TR>
				</TABLE>	
			<input type='hidden' name='cat' value='253'>
			<input type='hidden' name='list' >
			
			</FORM>";
		windowscreate(null,null,null,null,null);
	}
	
	function silu($lu){
		if ($lu=="oui"){
			$file="maillu.gif";
		}else{
			$file="mailnonlu.gif";
		}
		echo "<IMG SRC='scripts/ThemeOffice/",$file,"' border=0>";
	}
	
	function checkmail(){
		$iduser=$_SESSION["funkylab_id"];
		$nb=messagerie::countmessagenonlu("messagerie","recepteur",$iduser);
		if ($nb>=1){
			if ($nb>=2){
				$pluriel="s";
			}else{
				$pluriel="";
			}
			echo "<a href='index.php?cat=253&list'><IMG SRC='scripts/ThemeOffice/mailnonlu.gif' border=\"0\"><span class='logintxt'>vous avez $nb message",$pluriel," non lu</span></A>";
		}
	}
	
	function countmessagenonlu($base,$colonne,$queltype){
		$txt="SELECT * FROM $base WHERE $colonne=$queltype AND lu='non'";
		$res_prod=mysql_query($txt);	
		$nb=0;
		while ($row = mysql_fetch_array($res_prod)){$nb+=1;}
		return($nb);
	}	

	function checkifread($id){
		$res=mysql_query("SELECT * FROM messagerie WHERE id=$id");
		$row = mysql_fetch_array($res);
		$lu=$row['lu'];
		if ($lu=="non"){
			$query="UPDATE messagerie SET lu='oui' WHERE id=$id"; 
			$result=mysql_query($query);
		}	
	}

	function list_contact_mail($base,$idselect,$nomselect,$from){
		$buffer="";
		$res=mysql_query("SELECT * FROM $base");
		$buffer.= "\n<SELECT NAME='recepteur' class='listselect'>\n";	
		while ($row = mysql_fetch_array($res)) {      	  
			$id = $row[$idselect];
				$nom = $row[$nomselect];
				$prenom = $row['prenom'];
				if ($id==$from){
					$select="SELECTED";
				}else{
					$select="";
				}
				$buffer.= "<OPTION VALUE='$id' $select>$nom $prenom\n";
		}
		$buffer.= "</SELECT>";
		return($buffer);
	}	
		
	function confirmsendmessage($iduser,$recepteur,$message,$datenow,$objet,$lu){
		$query="INSERT INTO messagerie (id,fromqui,recepteur,date_envois,objet,message,lu) VALUES ('','$iduser','$recepteur','$datenow','$objet','$message','$lu')";
		$resultat=mysql_query($query);						
		if ($resultat=="1"){ echo "<P align='center'>Message envoyé.</P>"; }else{ echo "<P align='center'>Erreur !</P>"; }		
	}	
}

?>