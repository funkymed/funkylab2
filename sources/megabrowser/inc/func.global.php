<?

	function EffacerAllFile($path){
		//echo "<b>".$path."</b><br/>";				
	    if ($path[strlen($path)-1] != "/"){
	        $path .= "/";	
        }        
	    if (is_dir($path)){
	        $d = opendir($path);
	        while ($f = readdir($d)){
	            if ($f != "." && $f != ".."){
	               $rf = $path . $f; // chemin relatif au fichier php
	                if (is_dir($rf)){
	                    EffacerAllFile($rf);
                	}else{
	                	//echo $rf."<br/>";
	                    unlink($rf);
                    }
	            }
	        }	        
	        closedir($d);	
	       rmdir($path);		     
	    }
	}
		
	function listgalerie(){
		$buffer="";		
		$restype=mysql_query("SELECT * FROM type_photo WHERE parent='0' ORDER BY type");		
		$select="";
		$buffer.="		
			<select name='listgalerie'  class='listpagecat'>
				<option value=\"ALL\"> Selectionnez une galerie";
				$ul="";
				while ($row = mysql_fetch_array($restype)) 
				{      	 
					$buffer.=listparentgalerie($row,$ul);			
				}	
				$buffer.="
			</select>";
		
		return($buffer);
	}		
	
	function listparentgalerie($row,$ul){
		$txt="";
		$select="";		
		$id = $row['id'];
		$type = $ul.stripslashes($row['type']);
		$txt.= "<option value='$id'> $type";
		
		$restype=mysql_query("SELECT * FROM type_photo WHERE parent='$id'");
		$ul.="&nbsp;&nbsp;&nbsp;&nbsp;";
		while ($row = mysql_fetch_array($restype)) 
		{      	 
			$txt.=listparentgalerie($row,$ul);			
		}	
		return($txt);
	}


	function reduitletext($buffer,$newlenght){
		if (strlen($buffer)>$newlenght){
			$buffer= substr($buffer, 0, $newlenght);			
			$buffer=$buffer."..";
		}
		return($buffer);
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
				echo "la création de la vignette a echouée pour l'image $image";
				exit;
			} 
			@chmod($dir."/".$file,0644);
			
		}
	}
	
	function decodedate($date){
		$dayFrancais=array('Lundi','Mardi','Mercredi','Jeudi','Vendredi','Samedi','Dimanche');
		$dayEnglish=array('Monday','Tuesday','Wednesday','Thursday','Friday','Saturday','Sunday');
		$mois=array('Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Aout','Septembre','Octobre','Novembre','Décembre');
		$explodate=explode("-",$date);
		$theday=date("l", mktime (0,0,0,$explodate[1],$explodate[2],$explodate[0]));
		for ($aa=0;$aa<=7;$aa++){
			if ($dayEnglish[$aa]==$theday){
				break;
			}			
		}	
		$newdate=$dayFrancais[$aa]." ".$explodate[2]." ".$mois[$explodate[1]-1]." ".$explodate[0];
		return($newdate);
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
	
	function flvplayer($dirset,$rooturl){
		echo "
		<object classid=\"clsid:D27CDB6E-AE6D-11cf-96B8-444553540000\" codebase=\"http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=7,0,0,0\" width=\"320\" height=\"260\" id=\"FLVPlayer\">
		<param name=\"movie\" value=\"$rooturl/megabrowser/tool/FLVPlayer_Progressive.swf\" />
		<param name=\"salign\" value=\"lt\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"scale\" value=\"noscale\" />
		<param name=\"FlashVars\" value=\"&MM_ComponentVersion=1&skinName=$rooturl/megabrowser/tool/Corona_Skin_3&streamName=$rooturl/$dirset&autoPlay=true&autoRewind=false\" />
		<embed src=\"$rooturl/megabrowser/tool/FLVPlayer_Progressive.swf\" flashvars=\"&MM_ComponentVersion=1&skinName=$rooturl/megabrowser/tool/Corona_Skin_3&streamName=$rooturl/$dirset&autoPlay=true&autoRewind=false\" quality=\"high\" scale=\"noscale\" width=\"320\" height=\"260\" name=\"FLVPlayer\" salign=\"LT\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
		</object>
		";		
	}
	
	function quicktime($video){
		echo"	
			<object classid=\"clsid:02BF25D5-8C17-4B23-BC80-D3488ABDDC6B\" codebase=\"http://www.apple.com/qtactivex/qtplugin.cab\" width=\"320\" height=\"260\">
				<param name=\"src\" value=\"$video\" />
				<param name=\"controller\" value=\"true\" />
				<param name=\"autoplay\" value=\"true\" />
				<object type=\"video/quicktime\" data=\"$video\" width=\"320\" height=\"260\" class=\"mov\">
					<param name=\"controller\" value=\"true\">
					<param name=\"autoplay\" value=\"true\">
					Installez le codec quicktime<BR>
					http://www.quicktime.com
				</object>
			</object>
		";
	}		
	
	function playerAudio($dirset,$rooturl){
		echo "
		
		<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"280\" height=\"30\" id=\"mp3player\" align=\"middle\">
		<param name=\"allowScriptAccess\" value=\"sameDomain\" />
		<param name=\"movie\" value=\"$rooturl/megabrowser/tool/audioPlayer.swf\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"bgcolor\" value=\"#ffffff\" />
		<param name=\"FlashVars\" value=\"&musicUrl=$rooturl/$dirset&autostart=true\" />
		<embed src=\"$rooturl/megabrowser/tool/audioPlayer.swf\" flashvars=\"&musicUrl=$rooturl/$dirset&autostart=true\" quality=\"high\" wmode=\"transparent\" bgcolor=\"#ffffff\" width=\"280\" height=\"30\" name=\"mp3player\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
		</object>
		";	
	}
		
	function wmvplayer($video){
		echo"	
			<object type=\"audio/mpeg\" width=\"320\" height=\"260\" data=\"mamusique.mp3\" classid=\"CLSID:22d6f312-b0f6-11d0-94ab-0080c74c7e95\" id=\"mediaplayer1\">
				<param name=\"filename\" value=\"$video\" />
				<param name=\"autostart\" value=\"true\" />
				<param name=\"loop\" value=\"false\" />
				<embed type=\"application/x-mplayer2\" pluginspage=\"http://www.microsoft.com/Windows/Downloads/Contents/Medi aPlayer/\"
				filename=\"$video\" autostart=\"True\"
				showcontrols=\"true\" showstatusbar=\"false\" showdisplay=\"true\"
				autorewind=\"true\" width=\"320\" height=\"260\"  />
			</object>							
		";					
	}	
?>