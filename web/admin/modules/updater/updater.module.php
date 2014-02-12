<?php
class updater{	
	

	
	function listupdate(){
		$buffer="";
		$buffer.="
		<FORM enctype=\"multipart/form-data\" action=\"index.php\" method=\"POST\">
			<table border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
				<tr class='windcontenu3'>
					<td></td>
					<td align=\"left\">NOM</td>
					<td>VERSION</td>
					<td>ETAT/OPTION</td>
					<td>SUPPRIMER</td>
				</tr>
				<tr class='winline'>
					<td colspan=\"5\"></TD>
				</tr>";

				$buffer.=updater::ListPatch();
				
				$buffer.= "
				<tr class='winline'>
					<TD colspan=\"5\"></TD>
				</tr>
				<tr class='windcontenu3'>
					<td colspan=\"5\" align='right'>
						<table align=\"right\">
							<tr>
								<td align=\"left\">	
									<input name=\"fichier\" type=\"file\" />
									<input type=\"submit\" value=\"charger\" class=\"windcontenu3\" onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\"/>
									<input type=\"hidden\" name=\"cat\" value=\"239\"/><br/>
									<input type=\"hidden\" name=\"list\"/>
									<input type=\"hidden\" name=\"loadpatch\"/>									
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
		
		windowscreate("GESTIONNAIRE DE PATCH <font size=-1>version ".VERSION."</font>",null,null,"debut",0);		
			print $buffer;
		windowscreate(null,null,null,null,null);
		
	}	
	
	function ListPatch(){		

		$buffer="";						
		$rep= "modules/updater/patch/";
		
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
						$ext=strtolower(strrchr($f, '.'));
						$repCheck=substr($f,0,strlen($f)-strlen($ext));		
						$version="";
						$etat="";
						if ($ext==".lab"){
							$txtfile=substr($f,0,strlen($f)-strlen($ext)).".txt";
							if (is_file($rep.$txtfile)){
								$file_open=fopen($rep.$txtfile, 'r');
								$version =fread($file_open,1024*8);
								fclose($file_open);
								if (VERSION==$version){
									$etat=3;
								}else{
									if (VERSION>$version){
										$etat=3;
									}else{
										$etat=1;
									}					
								}			
							}else{
								$etat=2;
							}
							$color++;
							$buffer.= updater::lineTab($f,$repCheck,$color,$etat,$version);
						}
					}
				}
			}
	  	}
		closedir($dir);
		return($buffer);
		
	}
	
	function lineTab($f,$name,$color,$etat,$version){		
		$deleteoption="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer le patch $name','index.php?cat=239&delete=$name&list'); return false;\"><img src='".SKIN."/images/delete.png' border=0></A>";				
		
		switch(true){
			case ($etat==1):
				$select="";
				$install="<a href=\"index.php?cat=239&list&install=$name\">Cliquez pour mettre � jour</a>";
				break;
			case ($etat==2):
				$select="<img src='".SKIN."/images/viewoff.png' border=0>";
				$install="Le fichier n'est pas correct";
				$version="-";
				break;
			case ($etat==3):
				$select="<img src='".SKIN."/images/viewon.png' border=0>";
				$install="Patch d�j� install�";
				break;
		}		
		
		if ($color % 2 == 0) {
			 $class="windcontenu"; 
		}else{ 
			$class="windcontenu2"; 
		}
		
		$buffer="
		<tr  class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\">	
			<td width=20>$select</td>
			<td align=\"left\">&nbsp;$name</td>			
			<td>$version</td>
			<td>$install</td>
			<td  width=50>$deleteoption</td>
		</tr>";
		
		return($buffer);
	}
}
?>