<?php
class template{	
	
	function listTemplate(){
		$buffer="";
		$buffer.="
		<FORM enctype=\"multipart/form-data\" action=\"index.php\" method=\"POST\">
			<table border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
				<tr class='windcontenu3'>
					<td></td>
					<td align=\"left\">NOM</td>
					<td>ETAT / OPTION</td>
					<td>SUPPRIMER</td>
				</tr>
				<tr class='winline'>
					<td colspan=\"4\"></TD>
				</tr>";

				$buffer.=template::checkTemplate();
				
				$buffer.= "
				<tr class='winline'>
					<TD colspan=4></TD>
				</tr>
				<tr class='windcontenu3'>
					<td colspan=4 align='right'>
						<table align=\"right\">
							<tr>
								<td align=\"left\">	
									Charger un nouveau template &nbsp;
									<input name=\"fichier\" type=\"file\" />
									<input type=\"submit\" value=\"charger\" class=\"windcontenu3\" onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\"/>
									<input type=\"hidden\" name=\"cat\" value=\"259\"/><br/>
									<input type=\"hidden\" name=\"list\"/>
									<input type=\"hidden\" name=\"loadskin\"/>									
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		</form>";		
		
		// -------------------------------------------------------------------
		// DISPLAY
		// -------------------------------------------------------------------
		
		windowscreate("GESTIONNAIRE DE TEMPLATE",null,null,"debut",0);		
			print $buffer;
		windowscreate(null,null,null,null,null);
		
	}	
	
	function checkTemplate(){		
		$resconfig=mysql_query("SELECT skinsite FROM config");
		$rowconfig = mysql_fetch_array($resconfig);
		$skinsite=$rowconfig['skinsite'];
	
		$buffer="";						
		$rep= "../template/";
		
		$choixactuel="";
		$dir = opendir($rep);
		$color=0;
		$count=0;

		$buffer.= "$choixactuel<br/>\n";	
		while ($f = readdir($dir)) {
			$count++;
			if(is_file($rep.$f) && ($count>=3)) {
				if ($f!="index.html"){
					if ($choixactuel!=$f){
						$color++;
						$ext=strtolower(strrchr($f, '.'));
						$repCheck=substr($f,0,strlen($f)-strlen($ext));
						if (file_exists($rep.$repCheck)){
							$etat="<a href=\"index.php?cat=259&list&opskin=2&skin=".$repCheck."\">Selectionner</a>  - <a href=\"index.php?cat=259&list&opskin=4&skin=".$repCheck."\">D�sinstaller</a> - <a href=\"".$rep.$f."\">T�l�charger</a>";
							$delete=1;
						}else{
							$etat="<a href=\"index.php?cat=259&list&opskin=3&skin=".$repCheck."\">Installer</a> - <a href=\"".$rep.$f."\">T�l�charger</a>";
							$delete=3;
						}
						
						if ($repCheck==$skinsite){
							$etat="Template s�l�ctionn� - <a href=\"".$rep.$f."\">T�l�charger</a>";
							$delete=2;
						}
						
						$buffer.= template::lineTemplate($f,$repCheck,$color,$etat,$delete);
					}
				}
			}
	  	}
		closedir($dir);
		
		return($buffer);
		
	}
	
	function lineTemplate($f,$nomSkin,$color,$etat,$delete){
		
		$deleteoption="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer le template $nomSkin','index.php?cat=259&opskin=1&skin=$nomSkin&list'); return false;\"><img src='".SKIN."/images/delete.png' border=0></A>";

		switch(true){
			case ($delete==1):
				$select="<img src='".SKIN."/images/premierepage_off.gif' border=0>";
				break;
			case ($delete==2):
				$deleteoption="";	
				$select="<img src='".SKIN."/images/premierepage_on.gif' border=0>";
				break;
			case ($delete==3):
				$select="<img src='".SKIN."/images/stop.gif' border=0>";
				break;
		}
		
		if ($color % 2 == 0) {
			 $class="windcontenu"; 
		}else{ 
			$class="windcontenu2"; 
		}
		$buffer="
		<tr  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">	
			<TD width=20>$select</td>
			<td align=\"left\">&nbsp;$nomSkin</td>			
			<td>$etat</td>
			<td  width=50>$deleteoption</td>
		</tr>";
		return($buffer);
	}
	
	
	function EffacerRepSkin($path){				
	    if ($path[strlen($path)-1] != "/"){
	        $path .= "/";	
        }        
	    if (is_dir($path)){
	        $d = opendir($path);
	        while ($f = readdir($d)){
	            if ($f != "." && $f != ".."){
	               $rf = $path . $f;
	                if (is_dir($rf)){
	                    template::EffacerRepSkin($rf);
                	}else{
	                    unlink($rf);
                    }
	            }
	        }	        
	        closedir($d);	
	       rmdir($path);		     
	    }
	}
	
	
}
?>