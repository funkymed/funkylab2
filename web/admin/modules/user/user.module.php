<?php
class Admin_user{
	
	static function listuser(){
		
		echo checkbase(BASEDEFAUT,255,"0","&list");	
		
		windowscreate("ADMIN USER",null,null,"debut",0);
		echo"
		<FORM ACTION='index.php' method='GET'>
			<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
				<TR class='windcontenu3'>
					<TD></TD>
					<TD>ID</TD>					
					<TD >LOGIN</TD>
					<TD>PASS</TD>
					<TD>NOM PRENOM</TD>					
					<TD>EMAIL</TD>
					<TD>DATE DE CREATION</TD>
					<TD>DERNIERE CONNEXION</TD>
					<TD>SUPPRIMER</TD>
				</TR>
				<TR class='winline'>
					<TD colspan=10></TD>
				</TR>";
				$res=mysql_query("SELECT * FROM admin");
				$count=0;
				while ($row = mysql_fetch_array($res)) 
				{      	  
					$id = $row['id'];
					$login = $row['login'];
					$pass = $row['pass'];
					$nom = $row['nom'];
					$prenom = $row['prenom'];
					$email = $row['email'];
					$datecreation = $row['datecreation'];
					$lastconnexion = $row['derniereconnexion'];
					
					$count+=1;
					Admin_user::linewindows($id,$login,$pass,$nom,$prenom,$email,$datecreation,$lastconnexion,"255",$count);
				}
				echo "
				<TR class='winline'>
					<TD colspan=10></TD>
				</TR>
				<TR class='windcontenu3'>
					<TD colspan=10 align='right'>
						<SELECT NAME='select' class='listselect'>\n
							<OPTION VALUE='ADDUSER'>AJOUTER
							<OPTION VALUE='1' >MODIFIER
							<OPTION VALUE='2'>EFFACER
						</select>
						<INPUT TYPE='submit' VALUE='ok' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
					</TD>
				</TR>
			</TABLE>
			<input type='hidden' name='cat' value=255>
			<input type='hidden' name='list'>
		</FORM>";
		windowscreate(null,null,null,null,null);
	}

  static function linewindows($id,$login,$pass,$nom,$prenom,$email,$datecreation,$lastconnexion,$cat,$color){
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
					
			$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer l\'utilisateur $login ?','index.php?cat=$cat&select=2&id=$id&list'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
			
			echo"<TR  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">
			<TD width=20><INPUT type=radio name='id' value='$id' $check $disable></TD>
			<TD><a href='index.php?cat=$cat&select=1&id=$id&list'>$id</a></TD>
			<TD>$login</TD>
			<TD>$pass</TD>
			<TD>",$nom," ",$prenom,"</TD>
			<TD><a href='mailto:$email'>$email</A></TD>
			<TD>$datecreation</TD>
			<TD>$lastconnexion</TD>
			<TD>$delete</TD>
		</TR>";
	}	
}
?>