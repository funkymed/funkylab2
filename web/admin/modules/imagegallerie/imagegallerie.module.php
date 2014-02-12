<?php
class imagegallerie{
		
	function listimage($typeselect){
		$limit=6;
		echo checkbase(BASEDEFAUT,CAT,"0","&list");	
		
		$txt="<table cellpadding=0 cellspacing=0 border=0><tr><td class=\"onglet_over\"><a href='index.php?cat=245&list'>Galeries</a></td><td class=\"onglet\">Elements</td></tr></table>";
		
		windowscreate("ELEMENTS $txt",BASE,BASE,"debut",CAT);
		echo"
		<FORM ACTION='index.php' method='GET' >
			<table border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
				<tr class='winline'>
					<td colspan=6></TD>
				</tr>
				<tr>
					<td>
						<table border=0 cellspacing='0' cellpadding='2' align='center'>
							<tr>";
					
							if (($typeselect!="ALL") && ($typeselect!="")){
								$res=mysql_query("SELECT * FROM ".BASE." WHERE type='$typeselect' ORDER BY id");
							}else{
								$res=mysql_query("SELECT * FROM ".BASE." ORDER BY id");
							}	
							
							$count=0;
							
							while ($row = mysql_fetch_array($res)) {
								echo "<td valign='top'>".imagegallerie::displayvignette($row,BASE,CAT,$count)."</td>";
								$count+=1;
								if ($count>=$limit){
									$count=0;
									echo "</tr><tr>";
								}
							}
					echo "
						</tr></table>
					</td>
				</tr>
				<tr class='winline'>
					<TD colspan=6></TD>
				</tr>
				<tr class='windcontenu3'>
					<TD align='right'>
						<SELECT NAME='select' class='listselect'>\n
							<OPTION VALUE='ADDELEMENTGAL'>AJOUTER
							<OPTION VALUE='1'>MODIFIER
							<OPTION VALUE='2'>EFFACER
						</select>
						<INPUT TYPE='submit' VALUE='ok' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">
					</TD>
				</tr>
			</TABLE>
			<input type='hidden' name='cat' value='".CAT."'>
			<input type='hidden' name='list'>
			<input type='hidden' name='listgalerie' value='$typeselect'>
			<input type='hidden' name='GALERIE'>
		</FORM>";
		
		windowscreate(null,null,null,null,null);
	}
	
	function displayvignette($row,$directory,$cat,$color){
		$id=$row['id'];
		$nom=$row['nom'];
		if ($nom==""){
			$nom="aucun";
		}
		
		if(isset($_GET['listgalerie'])){
			$typeset=$_GET['listgalerie'];
		}else{
			$typeset="ALL";
		}
		
		$type=$row['type'];
		$file=$row['file'];
		$urlfile=$directory."/".$file.".jpg";
		$directory="../".$directory;
		
		$vignettevar=imagegallerie::fileformat($file);
		
		$check="";
		$class="";
		$disable="";
		if (isset($_GET['select'])){
			$select=$_GET['select'];
		}else{
			$select=0;
		}
				
		if ((isset($_GET['id'])) && ($select!=2)){
				$disable="DISABLED";
				if ($_GET['id']==$id){
					$check="CHECKED";
					$class="FIELDSETOVER";
				}		
		}	
		
	$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer l\'�l�ment : $nom ?','index.php?cat=".CAT."&select=2&id=$id&list&listgalerie=$typeset'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
			
		$buffer="
		<fieldset class='$class' onmouseover=\"className='FIELDSETOVER'\" onmouseout=\"className='$class'\">
			<legend align='right'>$delete</legend>
			<table height=100 align='center'>
				<tr>
					<td align='center' valign='middle'>$vignettevar</td>
				</tr>
				<tr>
					<td><INPUT type=radio name='id' value='$id' $disable $check> <a href='index.php?cat=".CAT."&select=1&id=$id&list&listgalerie=$typeset'>".reduitletext($nom,10)."</A></td>
				</tr>
			</table>
		</fieldset>
		";
		return($buffer);
	}
	
	function fileformat($file){	
		$root=ROOT;
		$iconeW=64;
		$iconeH=64;
		$dirbuffer=explode("/",$file);
		$lengeht=strlen($dirbuffer[count($dirbuffer)-1]);			
		$dirset="../".substr($file, 0, strlen($file)-$lengeht);
		$file=$dirbuffer[count($dirbuffer)-1];
				
		$lenghtname=10;
		$buffer="";
		
		$ext=strtolower(strrchr($file, '.'));
		$filesizedisp=@number_format((@filesize($dirset.$file)/1024),2);
		
		$filesize=@filesize($dirset.$file)/1024;
		
		$datemodifbufer=@date ("Y-m-d/H:i:s", filemtime($dirset."/".$file));
		
		$datemodifbufer=explode("/",$datemodifbufer);
		$datemodif=decodedate($datemodifbufer[0])." � ".$datemodifbufer[1];
		
		$fileurlencode=codefilename(urlencode($file));
		
		switch(TRUE){
			
			/************ FICHIER IMAGE ******************/
			case ($ext==".jpg" || $ext==".jpeg" || $ext==".gif" || $ext==".png"):
			
				$size = @getimagesize($dirset."/".$file);					
				$width=$size[0];
				$height=$size[1]; 
				$wpx=$size[0]+20;
				$hpx=$size[1]+50;
								
				createthumb("thumb_".$file,$file,$dirset,$size);
				
				$buffer.= "
				<a href=\"javascript:open_newpopup('../view.php?pic=$dirset$fileurlencode','viewserbrowser',$wpx,$hpx,'no','no');\">		
				<IMG SRC='$dirset/thumb_".decodefilename(urlencode($file))."' border=0 alt='$file $datemodif' title='$file $datemodif' $width px / $height px'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;
				
				/************ FICHIER AUDIO ******************/
			case ($ext==".mp3"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',340,280,'no','no');\">		
				<IMG SRC='$root/megabrowser/template/img/audioMP3.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;
			case ($ext==".wav"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/audioWAV.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;
			case ($ext==".ogg"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/audioOGG.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;						
			case ($ext==".mod" || $ext==".xm"):
				$buffer.= "<a href=\"".$dirset.$fileurlencode."\"><IMG SRC='$root/megabrowser/template/img/audioMOD.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;					
			case ($ext==".mid"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/audioMIDI.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;	
				
				/************ FICHIER VIDEO ******************/
				
			case ($ext==".avi" || $ext==".mpg" || $ext==".mpeg" || $ext==".wmv"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/mediaplayer.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;	
			case ($ext==".flv"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',360,300,'yes','yes');\"><IMG SRC='$root/megabrowser/template/img/videoFLV.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;
			case ($ext==".mov"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',340,300,'no','no');\"><IMG SRC='$root/megabrowser/template/img/videoMOV.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;
			case ($ext==".rm" || $ext==".ram"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/videoRM.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;							
																											
				/************ FICHIER TEXTE ******************/					
			case ($ext==".txt"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',700,300,'yes','yes');\"><IMG SRC='$root/megabrowser/template/img/txt.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;
			case ($ext==".html" || $ext==".htm"):
				$fileurlencode=decodefilename($fileurlencode);
				$buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'><IMG SRC='$root/megabrowser/template/img/html.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;
			case ($ext==".swf"):
			$fileurlencode=decodefilename($fileurlencode);
				$buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'><IMG SRC='$root/megabrowser/template/img/swf.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;

			case ($ext==".doc"):
				$fileurlencode=decodefilename($fileurlencode);
				$buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'><IMG SRC='$root/megabrowser/template/img/word.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;								
				
			case ($ext==".xls"):
				$fileurlencode=decodefilename($fileurlencode);
				$buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'><IMG SRC='$root/megabrowser/template/img/excel.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;								
											
			case ($ext==".pdf"):
				$fileurlencode=decodefilename($fileurlencode);
				$buffer.= "<a href=\"".$dirset.$fileurlencode."\" target='_blank'><IMG SRC='$root/megabrowser/template/img/pdf.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;							
				
				/************ ZIP ******************/					
			case ($ext==".zip"):
				$buffer.= "<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset.$fileurlencode."','viewserbrowser',700,300,'yes','yes');\"><IMG SRC='$root/megabrowser/template/img/zip.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname)."</a>";
				break;							
											
				/************ CONNAIS PAS ******************/					
			default:
				$buffer.= "<IMG SRC='$root/megabrowser/template/img/none.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
				".reduitletext($file,$lenghtname);
				break;
		}
	
		$buffer.="
				<BR>
				$filesizedisp ko";
		
		return($buffer);
	}
}

function codefilename($file){
	$file=str_replace("&", ";et", $file);
	$file=str_replace("%2C", ";virgule", $file);			
	$file=str_replace("%26", ";et", $file);
	$file=str_replace("+", ";plus", $file);
	$file=str_replace("%82", ";chelou1", $file);
	$file=str_replace("%8A", ";chelou2", $file);
	return($file);
}	

function decodedirname($file){
	$file=str_replace(";et", "&", $file);
	$file=str_replace("%26", "&", $file);
	$file=str_replace(";plus", " ", $file);
	return($file);
}	

function decodefilename($file){
	$file=str_replace("+", "%20", $file);
	$file=str_replace(";plus", "%20", $file);
	$file=str_replace(";virgule","%2C",  $file);	
	$file=str_replace("&", "%26", $file);
	$file=str_replace(";et", "&", $file);
	return($file);
}	

function decoderealfile($file){
	$file=str_replace("%20", " ", $file);
	$file=str_replace("%26", "&", $file);
	$file=str_replace("%2C", ",", $file);
	return($file);
}	

function createthumb($file,$originalfile,$dir,$size){
	if (file_exists($dir."/".$file)) { 
	}else{
		$width=$size[0];
		$height=$size[1]; 
		
		$ratio=$width/92;
		
		$thumbW=$width/$ratio;
		$thumbH=$height/$ratio;
		
		switch($size[2]){
			case 1:
				$image_src=imagecreatefromgif($dir."/".$originalfile);
				break;
			case 2:
				$image_src=imagecreatefromjpeg($dir."/".$originalfile);
				break;
			case 3:
				$image_src=imagecreatefrompng($dir."/".$originalfile);
				break;
		}
					
		$image_dest=imagecreatetruecolor($thumbW,$thumbH);
		imagecopyresized($image_dest, $image_src, 0, 0, 0, 0,$thumbW,$thumbH, $width, $height);
		
		if(!imagejpeg($image_dest,$dir."/".$file)){
			echo "la cr�ation de la vignette a echou�e pour l'image $image";
			exit;
		} 
		@chmod($dir."/".$file,0644);
		
	}
}				

?>