<?php

class menu{
		
	static function menulist (){
		$cat=247;
		echo checkbase(BASEDEFAUT,$cat,0,"&list");	
				
		windowscreate("MENU",null,null,"debut",$cat);
		echo"		
		<form>
			<fieldset>
				<legend>GESTION DU MENU</legend>
				<table border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
					<tr class='windcontenu3'>
						<td></td>
						<TD>ID</TD>
						<TD>IMAGE || VAR</TD>
						<TD align='left'>NOM</TD>
						<TD>LIEN</TD>
						<TD>SUPPRIMER</A></TD>
					</tr>
					<tr class='winline'>
						<TD colspan=6></td>
					</tr>";
					
					$res=mysql_query("SELECT * FROM menu WHERE parent=0 ORDER BY nom");
					
					$count=0;
					$UL="";
					while ($row = mysql_fetch_array($res)) 
					{      	  
						$count+=1;
						$count=menu::rowmenu($row,$count,$UL);
					}
					
					if ($count==0){
						echo "<TR><TD class='windcontenu' colspan=10>aucun resultat</TD></TR>";
					}
					
					echo "
					<TR class='winline'>
						<TD colspan=6></TD>
					</TR>
					<TR class='windcontenu3'>
						<TD colspan=6 align='right'>							
								<div id=\"menuListMenu\">
									<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"24\" height=\"24\" id=\"ANIMATION\" align=\"middle\">
									<param name=\"allowScriptAccess\" value=\"sameDomain\" />
									<param name=\"movie\" value=\"".SKIN."/images/wait.swf\" />
									<param name=\"quality\" value=\"high\" />
									<param name=\"wmode\" value=\"transparent\" />
									<param name=\"bgcolor\" value=\"#dddddd\" />
									<embed src=\"".SKIN."/images/wait.swf\" quality=\"high\" wmode=\"transparent\" bgcolor=\"#dddddd\" width=\"24\" height=\"24\" name=\"ANIMATION\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
									</object>
								</div>
							
						</td>
					</tr>
				</table>
				<input type='hidden' name='cat' value=$cat>
				<input type='hidden' name='list'>
			</fieldset>
		</form>";
		
		windowscreate(null,null,null,null,null);	
	}

  static function rowmenu($row,$count,$UL){
		$addurlpage="&cat=247&list";
		$viewUL="";
		$id=$row['id'];	
		$ordre=$row['ordre'];	
		$nom=stripslashes($row['nom']);	
		$image=$row['image'];
		$parent=$row['parent'];
		$type=$row['type'];
		$url=$row['url'];
		
		switch($type){
			case "type_bouton":
				$nom="<b>[BOUTON] ".$nom."</b>";
				break;
			case "type_contenu":
				$nom="Billet : ".$nom;
				$url="";
				break;
			case "type_gallerie":
				$nom="Galerie : ".$nom;
				$url="";
				break;
			case "type_url":
				$nom="Url : ".$nom;
				$url="<a href='$url' target='_blank'>$url</a>";
				break;
			case "type_submenu":
			$nom="<b>Sub : ".$nom."</b>";
				$url="";
				break;
			default:
				$url="";
				break;
		}
		
		if ($parent==0){ $bb="<B><a href=\"javascript:open_newpopup('modules/menu/menu.preview.php?menu=$id','viewmenu',640,600,'no','no');\">"; $bb_="</a></B>";}else{ $bb=""; $bb_="";}
		
		if ($parent!="0"){ $viewUL=$UL."<IMG SRC='".SKIN."/images/joinbottom.gif' border=0>";}
		$count+=1;
		if ($count % 2 == 0) {
			 $class="windcontenu"; 
		}else{ 
			$class="windcontenu2"; 
		}	
		
		$disable="";
		$check="";
		
		if (isset($_GET['select'])){
			$select=$_GET['select'];
		}else{
			$select=0;
		}
				
		if ((isset($_GET['id'])) && ($select!=2)){
				$disable="DISABLED";
				if ($_GET['id']==$id){
					$check="CHECKED";
					$class="winOVER";
				}		
		}
		
		$newordreup=$ordre-1;
		$newordredown=$ordre+1;	
		
		if ($parent==0) {
			$ordremodif="";
			$newpage="	
			<TR bgcolor=#FFFFFF height=2>
				<TD colspan=7></TD>
			</TR>";			
		}else{
			$newpage="";
			$ordremodif="<a href='index.php?hidepost=$id&ordrenew=$newordreup$addurlpage'><IMG SRC=\"".SKIN."/images/billet/ico_ordre_up.png\" border=0></A> $ordre <a href='index.php?hidepost=$id&ordrenew=$newordredown$addurlpage'><IMG SRC=\"".SKIN."/images/billet/ico_ordre_down.png\" border=0></A>";							
		}		
		
		if (menu::ifparent($id)==true){			
			$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer ".strip_tags($nom)."','index.php?list&cat=247&select=2&id=$id'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
		}else{
			$delete="";
		}
		
		echo"
		$newpage
		<tr  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">	
			<td width=20><input type=radio name='id' onchange=\"writeMenuListMenu($id)\" value='$id' $check $disable></td>		
			<td><a href='index.php?list&cat=247&select=1&id=$id'>$id</a></td>
			<td>$image</td>
			<td align='left'>$bb $viewUL $ordremodif $nom $bb_</td>
			<td>$url</td>
			<td>$delete</td>
		</tr>";
		
		$UL.="<IMG SRC='".SKIN."/images/spacer.gif' border=0>";
		$reschild=mysql_query("SELECT * FROM menu WHERE parent=$id ORDER BY ordre");
		
		while ($rowchild = mysql_fetch_array($reschild)){
			menu::rowmenu($rowchild,$count,$UL);
			$count+=1;
		}	
		
		return($count);
	}

  static function ifparent($id){
		$checkparentRES=mysql_query("SELECT * FROM menu WHERE parent=$id");
		$checkparentROW = mysql_fetch_array($checkparentRES);
		if($checkparentROW){ return(false); }else{ return(true); }
	}
}

?>