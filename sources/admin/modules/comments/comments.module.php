<?
class comments{
	
	function listcom($page=0){
		
		$cat=242;
		$nbcom=allcount("comments");
		$nbparpage=20;
		$pageset=$page*$nbparpage;
		$nbpage=$nbcom/$nbparpage;
		echo checkbase(BASEDEFAUT,$cat,"0","&list");	
		
		$txt="<table cellpadding=0 cellspacing=0 border=0><tr><td class=\"onglet_over\"><a href='index.php?cat=241&list'>Blacklist</a></td><td class=\"onglet\">Commentaire</td></tr></table>";
		
		windowscreate("COMMENTAIRES ($nbcom) $txt",null,null,"debut",3);		
		
		echo"
		<FORM ACTION='index.php' method='GET'>
			<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
				<TR class='windcontenu3'>
					<TD></TD>
					<TD>ID</TD>
					<TD>DATE</TD>
					<TD>HEURE</TD>
					<TD>POST</TD>
					<TD>PSEUDO</TD>
					<TD>COMMENTAIRE</TD>					
					<TD>SUPPRIMER</TD>
				</TR>
				<TR class='winline'>
					<TD colspan=10></TD>
				</TR>";

				$res=mysql_query("SELECT * FROM comments ORDER BY ID DESC LIMIT $pageset,$nbparpage");
				$count=0;
				$error=false;
				while ($row = mysql_fetch_array($res)){ 
					$count+=1;
					$id = $row['id'];
					$billetid = $row['billetid'];
					$date = $row['commentdate'];
					
					
					$blacklist_check=comments::checkblacklist($row['text']);
					
					$commentaire = reduitletext(strip_tags(stripslashes($row['text'])),50);
			
					
					$pseudo = $row['pseudo'];
					if ($row['ip_utilisateur']=="admin"){ 
						$pseudo="<B>".$pseudo."</B>";
						$commentaire="<B>".$commentaire."</B>";
					}
					$billet=recupeall($billetid,"billet","nom");
					if ($count % 2 == 0) {
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
					
					if ($blacklist_check==true){
						$class="ALERTBLACKLIST";
					}
					
					if ((isset($_GET['id'])) && ($select!=2)){
						$disable="DISABLED";
						if ($_GET['id']==$id){
							$check="CHECKED";
							$class="winOVER";
						}		
					}						
					
					$date=explode(" ",$date);
					
					$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer le commentaire de $pseudo le ".decodedate($date[0])." ?','index.php?cat=$cat&select=2&id=$id&page=$page&list'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
					
					echo"
					<tr  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">
						<td width=20><INPUT type=radio name='id' value='$id' $check $disable></td>
						<td><a href='index.php?cat=$cat&select=1&id=$id&page=$page&list'>$id</A></td>
						<td>",decodedate($date[0]),"</td>
						<td>",$date[1],"</td>
						<td>$billet</td>
						<td>$pseudo</td>
						<td>$commentaire</td>
						<td>$delete</td>
					</tr>";	
				}
				echo "
				<TR class='winline'>
					<TD colspan=10></TD>
				</TR>
				<TR class='windcontenu3'>			
					<TD colspan=10 align='right'>
					<TABLE border=0 width=100%><TR><TD>";
					if ($nbpage>=1.01){
						if ($page>=1){
							$pageback=$page-1;
							echo "<a href='index.php?cat=$cat&listcom&page=$pageback'> << </A>";
						}
						for ($BB=0;$BB<=$nbpage;$BB++){
							if ($page==$BB){
								echo "<span class='titrelogin'>",$BB+1,"</span> - ";
							}else{
								echo "<a href='index.php?cat=$cat&list&page=$BB'>",$BB+1,"</A> - ";
							}
						}
						if ($page<=$nbpage-1){
							$pagenext=$page+1;
							echo "<a href='index.php?cat=$cat&list&page=$pagenext'> >> </A>";
						}
					}
					echo"</TD><TD align='right'>
						<SELECT NAME='select' class='listselect'>\n
							<OPTION VALUE='ADDCOMMENT' >REPONDRE
							<OPTION VALUE='1' >MODIFIER
							<OPTION VALUE='2'>EFFACER
						</select>
						<INPUT TYPE='submit' VALUE='ok' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
					</TD></TR></TABLE>
					</TD>
				</TR>
			</TABLE>
			<input type='hidden' name='cat' value=$cat>
			<input type='hidden' name='comedit'>			
			<input type='hidden' name='list'>	
			<input type='hidden' name='page' value=$page>
		</FORM>";
		windowscreate(null,null,null,null,null);
	}		
	
	function checkblacklist($commentaire){
		$resblack=mysql_query("SELECT * FROM blacklist");
		$error=false;		
		while ($rowblack = mysql_fetch_array($resblack)){   
			$mot=$rowblack['mot'];
			if ( strstr($commentaire, $mot) ) {
				$error=true;
			}			
		}		
		return($error);
	}

	
}
?>