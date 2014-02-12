<?php
class imagetype
{
	static function listype(){
		
		echo checkbase(BASEDEFAUT,245,"0","&list");	
		
		$txt="<table cellpadding=0 cellspacing=0 border=0><tr><td class=\"onglet\">Galeries</td><td class=\"onglet_over\"><a href='index.php?cat=244&list&listgalerie=ALL'>Elements</td></tr></table>";;
		
		windowscreate("GALERIES $txt",null,null,"debut",3);
		echo"<FORM ACTION='index.php' method='GET' >
		<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
			<TR class='windcontenu3'>
				<td align='center'></td>
				<td align='center'>ID</td>
				<td align='center'>ELEMENTS</td>
				<td align='left'>TYPE</td>
				<td align='center'>LISTER</td>
				<td align='center'>VIDER</td>
				<td width=10>SUPPRIMER</td>
			</TR>
			<TR class='winline'>
				<td colspan=\"7\"></td>
			</TR>
			<TR  class='windcontenu2'>
				<td align='center'></td>
					<td align='center'></td>
				<td align='center'>",allcount(BASE),"</td>
				<td align='left'>All</td><td><a href='index.php?cat=".CAT."&list'>Lister</A></td>
				<td></td>
				<td></td>
			</TR>";	
			
					$res_prod=mysql_query("SELECT * FROM type_".BASE." WHERE parent=0 ORDER BY type");

					$count=0;
					$UL="";
					while ($row = mysql_fetch_array($res_prod)) 
					{      	  
						$count+=1;
						$count=imagetype::viewrow($row,$count,$UL);
					}
				echo"	
				</td>
			</TR>
			<TR class='winline'>
				<td colspan=\"7\"></td>
			</TR>
			<TR class='windcontenu3'>
				<td colspan=\"7\" align='right'>
					<SELECT NAME='select' class='listselect'>\n
						<OPTION VALUE='ADDTYPEGAL'>AJOUTER	
						<OPTION VALUE='1'>MODIFIER
						<!-- <OPTION VALUE='2'>EFFACER -->
						
					</select>
					<INPUT TYPE='submit' VALUE='ok' class='windcontenu3'>
				</td>
			</TR>
		</TABLE>
		<input type='hidden' name='cat' value=\"245\">
		<input type='hidden' name='list'>
		<FROM>";
		windowscreate(null,null,null,null,null);
	}


  static function viewrow($row,$count,$UL){
		$viewUL="";
		$id=$row['id'];
		$parent=$row['parent'];
		$type=stripslashes($row['type']);
		
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
				
		if ($parent==0) {
			$newpage="	
			<TR bgcolor='#FFFFFF' height='2'>
				<td colspan=7></td>
			</TR>";			
		}else{
			$newpage="";
		}		
		
		if ($parent!="0"){ $viewUL=$UL."<IMG SRC='".SKIN."/images/joinbottom.gif' border=0>";}
		
		$count=countbase("photo","type",$id);
		
		if ((imagetype::ifparent($id)==true)&&($count==0)){
			$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer la galerie $type ?','index.php?cat=245&select=2&id=$id&list'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
		}else{
			$delete="";
		}
		
		$vider="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment vider la galerie $type ?','index.php?cat=245&list&vider=$id'); return false;\">Vider</A>";
		
		echo"
		$newpage
		<TR  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">	
			<td width=20><INPUT type=radio name='id' value='$id' $check $disable></td>		
			<td><a href='index.php?cat=245&select=1&id=$id&list'>$id</A></td>
			<td>$count</td>
			<td align='left'>$viewUL $type</td>
			<td><a href='index.php?cat=244&list&listgalerie=$id'>Lister</A></td>
			<td>$vider</td>
			<td>$delete</td>
		</TR>";
		
		$UL.="<IMG SRC='".SKIN."/images/spacer.gif' border=0>";
		$reschild=mysql_query("SELECT * FROM type_photo WHERE parent=$id ORDER BY type");
		
		while ($rowchild = mysql_fetch_array($reschild)){
			imagetype::viewrow($rowchild,$count,$UL);
			$count+=1;
		}	
		
		return($count);
	}

  static function ifparent($id){
		$checkparentRES=mysql_query("SELECT * FROM type_photo WHERE parent=$id");
		$checkparentROW = mysql_fetch_array($checkparentRES);
		if($checkparentROW){ return(false); }else{ return(true); }
	}
}