<?
class blacklist{	
	
	function listmots(){
		$cat=241;
		echo checkbase(BASEDEFAUT,$cat,"0","&list");	
		
		$txt="<table cellpadding=0 cellspacing=0 border=0><tr><td class=\"onglet\">Blacklist</td><td class=\"onglet_over\"><a href='index.php?cat=242&list&page=0'>Commentaire</a></td></tr></table>";
		
		windowscreate("LES MOTS INTERDITS $txt",null,null,"debut",0);
		echo"
		<FORM ACTION='index.php' method='GET'>
			<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
				<TR class='windcontenu3'>
					<TD></TD>
					<TD width=30>ID</TD>					
					<TD align='left'>MOT</TD>
					<TD width=200>NB DE COMMENTAIRE</TD>
					<TD width=100>SUPPRIMER</TD>
				</TR>
				<TR class='winline'>
					<TD colspan=10></TD>
				</TR>";
				$res=mysql_query("SELECT * FROM blacklist");
				$count=0;
				while ($row = mysql_fetch_array($res)){    
					$id = $row['id'];
					$mot = $row['mot'];
					blacklist::linewindows($id,$mot,$cat,$count);
					$count+=1;
				}
				echo "
				<TR class='winline'>
					<TD colspan=10></TD>
				</TR>
				<TR class='windcontenu3'>
					<TD colspan=10 align='right'>
						<SELECT NAME='select' class='listselect'>\n
							<OPTION VALUE='ADDBLACKLIST' >AJOUTER
							<OPTION VALUE='1' >MODIFIER
							<OPTION VALUE='2'>EFFACER
						</select>
						<INPUT TYPE='submit' VALUE='ok' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
					</TD>
				</TR>
			</TABLE>
			<input type='hidden' name='cat' value=$cat>
			<input type='hidden' name='list'>
		</FORM>";
		windowscreate(null,null,null,null,null);
	}
	
	function linewindows($id,$mot,$cat,$color){
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
					$check="CHECKED";
					$class="winOVER";
				}		
			}		
			
			$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer $mot ?','index.php?cat=$cat&select=2&id=$id'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
			
			echo"
			<TR  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">	
				<TD width=20><INPUT type=radio name='id' value='$id' $check $disable></TD>
				<TD><a href='index.php?cat=$cat&select=1&id=$id&list'>$id</a></TD>
				<TD align='left'>$mot</TD>
				<TD>",blacklist::checkmot($mot),"</TD>
				<TD>$delete</TD>
			</TR>";
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
	
	function checkmot($mot){
		$mot=addslashes($mot);
		$resblack=mysql_query("SELECT * FROM comments WHERE text LIKE '%$mot%'");
		$count=0;		
		while ($rowblack = mysql_fetch_array($resblack)){ $count+=1; }		
		return($count);
	}	
	
	function censure($commentaire){
		$resblack=mysql_query("SELECT * FROM blacklist");
		$error=false;		
		while ($rowblack = mysql_fetch_array($resblack)){   
			$mot=$rowblack['mot'];
			if ( strstr($commentaire, $mot) ) {
				$commentaire=str_replace($mot, "xxx", $commentaire);
			}			
		}		
		return($commentaire);
	}
	
}
?>