<?
class banniere
{
	function listbanniere(){
		windowscreate("BANNIERES DE PUB",null,null,"debut",0);
		echo"
		<FORM ACTION='index.php' method='GET'>
			<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
				<TR class='windcontenu3'>
					<TD></TD>
					<TD>ID</TD>					
					<TD>ADRESSE WEB</TD>
					<TD>IMAGE</TD>
					<TD>NB CLIC</TD>
					<TD>SUPPRIMER</TD>
				</TR>
				<TR class='winline'>
					<TD colspan=\"10\"></TD>
				</TR>";
				$res=mysql_query("SELECT * FROM bannieres ORDER BY nbclic DESC");
				$count=0;
				while ($row = mysql_fetch_array($res)){  
					banniere::linewindows($row,"256",$count);
					$count++;
				}
				echo "
				<TR class='winline'>
					<TD colspan=10></TD>
				</TR>
				<TR class='windcontenu3'>
					<TD colspan=10 align='right'>
						<SELECT NAME='select' class='listselect'>\n
							<OPTION VALUE=\"ADDPUB\">AJOUTER
							<OPTION VALUE=\"1\" >MODIFIER
							<OPTION VALUE=\"2\">EFFACER
						</select>
						<INPUT TYPE='submit' VALUE='ok' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
					</TD>
				</TR>
			</TABLE>
			<input type=\"hidden\" name=\"list\">
			<input type=\"hidden\" name=\"cat\" value=\"256\">
		</FORM>";
		windowscreate(null,null,null,null,null);
	}
	
	function linewindows($row,$cat,$color){
		
		$id = $row['id'];
		$url = $row['url'];
		$image = $row['image'];
		$nbclic = $row['nbclic'];
		
		
		$size = getimagesize("../".$image);	
		
			$width=$size[0];
			$height=$size[1]; 
			
			$ratio=$width/256;
			
			$thumbW=$width/$ratio;
			$thumbH=$height/$ratio;
		
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
					$check="checked=\"checked\"";
					$class="winOVER";
				}		
			}	

			$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer cette pub ?','index.php?cat=$cat&select=2&id=$id&list'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
			
			echo"<TR  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">	
			<TD width=20><INPUT type=radio name='id' value='$id' $check $disable></TD>
			<TD><a href='index.php?id=$id&select=1&cat=256&list'>$id</A></TD>
			<TD><a href='$url' target='blank'>",reduitletext($url,50),"</A></TD>
			<TD><a href=\"javascript:open_newpopup('../view.php?pic=../$image&nowatermark','viewserbrowser',$width,$height,'no','no');\"><IMG SRC='../$image' width=\"$thumbW\" height=\"$thumbH\" border=\"0\" vspace=\"1\"></A></TD>
			<TD>$nbclic</TD>
			<TD>$delete</TD>
		</TR>";
	}		
}
?>