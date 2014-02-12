<?
class newsletter
{
	//==================================================================================================
	//	EMAILS
	//==================================================================================================
	
	function checkinfo($mail,$date,$IPcreation){
		/***** TEST EMAIL VALIDE ****/
		if (verifmail($mail)==false){
			return(2);			
		}else{
			/***** TEST EMAIL DOUBLONG ****/
			if (countbase("mail_newsletter","mail",$mail)>=2){		
				return(1);
				
			}else{						
				/***** IP DOUBLON ****/
				if (countbase("mail_newsletter","IPcreation",$IPcreation)>=2){
					$test=explode(" ",$IPcreation);
					if (count($test)!=2){
						return(3);
					}
					
				}
			}
		}
	}
	
	function listmail(){
		
		echo "<br/>
		<table align=\"center\" cellspacing=\"0\" cellpadding=\"0\" class=\"loginbox\" width=\"250\">
			<tr>
				<td class='windcontenu3' colspan=\"2\">LEGENDE</td>
			</tr>
			<tr>
				<td align='right'>ALERTE DE DOUBLON &nbsp;</td><td width=\"10\" class='ALERTEDOUBLON'></td>
			</tr>		
			<tr>
				<td align='right'>ALERTE EMAIL ERRONNÉ &nbsp;</td><td width=\"10\" class='ALERTEMAIL'></td>
			</tr>		
			<tr>
				<td align='right'>ALERTE IP REPETÉE &nbsp;</td><td width=\"10\" class='ALERTEIP'></td>
			</tr>						
		</table>";
		
		$txt="<table cellpadding=0 cellspacing=0 border=0><tr><td class=\"onglet\">Emails</td><td class=\"onglet_over\"><a href='index.php?cat=252&listletter'>Newsletters</a></td></tr></table>";
		
		windowscreate("EMAILS NEWSLETTER $txt",null,null,"debut",0);
		echo"
		<FORM ACTION='index.php' method='GET'>
		
			<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
				<TR class='windcontenu3'>
					<TD></TD>
					<TD>ID</TD>					
					<TD width=\"40%\">MAIL</TD>
					<TD>DATE</TD>
					<TD>IP</TD>
					<TD width=10%>SUPPRIMER</TD>
				</TR>
				<TR class='winline'>
					<TD colspan=6></TD>
				</TR>";
				$res=mysql_query("SELECT * FROM mail_newsletter ORDER BY date DESC");
				$count=1;
				while ($row = mysql_fetch_array($res)) 
				{      	  
					$id = $row['id'];
					$mail = $row['mail'];
					$date = $row['date'];
					$IPcreation = $row['IPcreation'];
					$error=newsletter::checkinfo($mail,$date,$IPcreation);
					newsletter::linewindows($id,$mail,$date,$IPcreation,"252",$count,$error);
					$count+=1;
				}
				echo "
				<TR class='winline'>
					<TD colspan=6></TD>
				</TR>
				<TR class='windcontenu3'>
					<TD colspan=6 align='right'>
						<SELECT NAME='select' class='listselect'>\n
							<OPTION VALUE='ADDEMAIL'>AJOUTER
							<OPTION VALUE='1' >MODIFIER
							<OPTION VALUE='2'>EFFACER
						</select>
						<INPUT TYPE='submit' VALUE='ok' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
					</TD>
				</TR>
			</TABLE>
			<input type=\"hidden\" name=\"cat\" value=\"252\">
			<input type=\"hidden\" name=\"listmail\">
		</FORM>";
		windowscreate(null,null,null,null,null);
	}
	
	function linewindows($id,$mail,$date,$IPcreation,$cat,$color,$error){
		$disable="";
		$check="";
		if ($error==null){
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

			if ((isset($_GET['id'])) && ($select!=2)){
				$disable="DISABLED";
				if ($_GET['id']==$id){
					$check="checked=\"checked\"";
					$class="winOVER";
				}		
			}	
		}else{
			switch($error){
				case 1:
					$class="ALERTEDOUBLON"; 
					break;
				case 2:
					$class="ALERTEMAIL"; 
					break;
				case 3:
					$class="ALERTEIP"; 
					break;
			}
		}
			if ($error==null){	
				echo"<TR  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">";
			}else{
				echo"<TR  class='$class'>";
			}
			$realdate=explode(" ",$date);
						
			$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment enlever l\'email $mail ?','index.php?cat=$cat&select=2&id=$id&email&listmail'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
			
			echo"
			<TD width=20><INPUT type=radio name='id' value='$id' $check $disable></TD>
			<TD><a href='index.php?cat=$cat&select=1&id=$id&listmail'>$id</a></TD>
			<TD>$mail</TD>
			<TD>",decodedate($realdate[0])," à ",$realdate[1],"</TD>
			<TD>$IPcreation</TD>
			<TD >$delete</TD>
		</TR>";
	}	

	//==================================================================================================
	//	NEWSLETTER
	//==================================================================================================
	
	function listletter(){		
		
		$txt="<table cellpadding=0 cellspacing=0 border=0><tr><td class=\"onglet_over\"><a href='index.php?cat=252&listmail'>Emails</a></td><td class=\"onglet\">Newsletters</td></tr></table>";
		
		windowscreate("NEWSLETTER $txt",null,null,"debut",0);
		
		echo"	
		<FORM ACTION='index.php' method='GET'>
			<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
				<TR class='windcontenu3'>
					<TD></TD>
					<TD>ID</TD>					
					<TD>TITRE</TD>
					<TD>DATE DE MODIFICATION</TD>
					<TD>NOM DE L'AUTEUR</TD>
					<TD>ENVOYÉ LE</TD>					
					<TD>SUPPRIMER</TD>
				</TR>
				<TR class='winline'>
					<TD colspan=10></TD>
				</TR>";
				$res=mysql_query("SELECT id,date,parqui,titre,senddate FROM newsletter");
				$count=0;
				while ($row = mysql_fetch_array($res)) 
				{      	  				
					$id = $row['id'];
					$date = $row['date'];
					$parqui = $row['parqui'];
					$titre = $row['titre'];
					$senddate = $row['senddate'];
					
					$dateexplode=explode(" ",$date);
					$date=decodedate($dateexplode[0])." à ".$dateexplode[1];
					
					if ($senddate!="0000-00-00 00:00:00"){
						$senddateexplode=explode(" ",$senddate);
						$senddate=decodedate($senddateexplode[0])." à ".$senddateexplode[1];
						$file="newsletter_oui.gif";
					}else{
						$file="newsletter_non.gif";
						$senddate="pas encore envoyée";
					}
							
					$count+=1;
	
					if ($count % 2 == 0) {
						 $class="windcontenu"; 
					}else{ 
						$class="windcontenu2"; 
					}
					
					$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer la newsletter $titre ?','index.php?cat=251&select=2&id=$id&listletter'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
			
			
					echo"<TR  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">
					<TD width=20><INPUT type=radio name='id' value='$id'></TD>
					<TD><a href='index.php?cat=251&select=1&id=$id&listletter'>$id</A></TD>			
					<TD><a href=\"javascript:open_newpopup('modules/newsletter/newsletter.display.module.php?id=$id&debug','newsletter',",680,",",600,",'no','no');\">$titre</A></TD>
					<TD>$date</TD>
					<TD>",recupeall($parqui,"admin","nom")," ",recupeall($parqui,"admin","prenom"),"</TD>
					<TD><IMG SRC='scripts/ThemeOffice/",$file,"' align='middle'> $senddate</TD>					
					<TD >$delete</TD>
				</TR>";
				}
				echo "
				<TR class='winline'>
					<TD colspan=10></TD>
				</TR>
				<TR class='windcontenu3'>
					<TD colspan=10 align='right'>
						<SELECT NAME='select' class='listselect'>\n							
							<OPTION VALUE='ADDNEWSLETTER' >CREER UNE NOUVELLE NEWSLETTER
							<OPTION VALUE='SENDNEWSLETTER' >ENVOYER CETTE NEWSLETTER
							<OPTION VALUE='1' >MODIFIER CETTE NEWSLETTER
							<OPTION VALUE='2'>EFFACER CETTE NEWSLETTER
						</select>
						<INPUT TYPE='submit' VALUE='ok' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
					</TD>
				</TR>
			</TABLE>
			<input type=\"hidden\" name=\"cat\" value=\"251\">
			<input type=\"hidden\" name=\"listletter\">
		</FORM>";
		
		windowscreate(null,null,null,null,null);
	}
	
	//==================================================================================================
	//	ENVOIS LA NEWSLETTER
	//==================================================================================================
	
	function sendnewsletter($id){
		windowscreate("ENVOIS NEWSLETTER",null,null,"debut",0);
		echo"
			<IFRAME  width=\"100%\" 
			 height=\"300\"
			 scrolling=\"auto\" 
			 frameborder=\"0\" 
			 name=\"frame\" 
			 src =\"modules/newsletter/newsletter.display.module.php?id=$id\"  
			>
			</IFRAME>		
			";
		windowscreate(null,null,null,null,null);		
	}
	
}

?>