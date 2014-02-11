<?

	/**** 
	
		MINIBROWSER AJAX
		PAR Cyril PEREIRA
		LE 30 octobre 2005
	
	 ****/
	 
	require_once('config.php');
	$files=array();
	$dirs=array();
	$preview=false;	
	
	if (isset($_GET['minibrowserlist'])){
		$dirset=$_GET['minibrowserlist'];
		if ($_GET['minibrowserlist']==""){
			$dirset=$dirROOT;
		}	
		
		$ext=strtolower(strrchr($dirset, '.'));
	
		if (in_array($ext, $extvalid)){
			$preview=true;
			$filepreview=$dirset;
			preview($filepreview,$root);

			$oldirarray=explode("/",$dirset);
			$dirset="";
			for ($xx=0;$xx<=count($oldirarray)-2;$xx++){
				$dirset.="/".$oldirarray[$xx];
			}
		}	
	}

	$dirset=cleanurl($dirset);

	//echo "<P>debug > DIRSET > $dirset</P>";
	//echo "<P>Repertoire courant : $dirset</P>";
	
	$parent="";
	$oldirarray=explode("/",$dirset);
	for ($xx=0;$xx<=count($oldirarray)-2;$xx++){
		$parent.="/".$oldirarray[$xx];
	}
	$parent=cleanurl($parent);		
		
	$listdisplay="	
	<OPTION value=''>Selectionnez un fichier
	<OPTION value=\"$parent\">Retour\n";
	
	$checkext=$_GET['filter'];
	
	if ($checkext=="true"){
		switch($_GET['exttype']){
			case "image":
				$extvalid=array('.jpg','.jpeg','.gif','.png');
				break;
			case "video":
				$extvalid=array('.mpg','.mpeg','.avi','.flv','.mov','.rm','.ram','.wmv');
				break;	
			case "audio":
				$extvalid=array('.mp3','.wav','.midi');
				break;	
		}	
	}
	
		
	$foldirer = $dirset;				
	$dossier = opendir($foldirer);
	
	while (false !== ($Fichier=@readdir($dossier))){	
		if (substr($Fichier,0,1)!="."){
			if (is_dir($dirset."/".$Fichier)){
				$dirs[]=$Fichier.'/';
				$dirfound=true;
			}else{
				$ext=strtolower(strrchr($Fichier, '.'));
				if ($checkext==true){																		
					if (in_array($ext, $extvalid)){
						$testthumb=explode("_",$Fichier);
						if ($testthumb[0]!="thumb"){
							$files[]=$Fichier; 
							$filefound=true;
						}
					}
				}else{
					$testthumb=explode("_",$Fichier);
					if ($testthumb[0]!="thumb"){
						$files[]=$Fichier; 
						$filefound=true;
					}
				}
			}
		}
	}	
	natcasesort($dirs);
	foreach ($dirs as $dirs){
		$value=cleanurl($dirset."/".$dirs);
		$dirs=cleanurl($dirs);		
		$listdisplay.="\t<OPTION value='$value'>[$dirs]\r";
	}
	
	natcasesort($files);		
	
	if ($preview==true){
		$filearray=explode("/",$filepreview);
		$filepreview=$filearray[count($filearray)-1];		
	}
	
	foreach ($files as $file){
		$value=cleanurl($dirset."/".$file);
		if ($preview==true){
			if ($file==$filepreview){
				$valuecheck="SELECTED";
			}else{
				$valuecheck="";
			}
		}else{
			$valuecheck="";
		}
		
		$listdisplay.="\t<OPTION value='$value' $valuecheck>".reduitletext($file,15)."\r";
	}
	
	print "
	<SELECT NAME='minibrowserlist' style='font-size:9px;' onchange=\"writediv(this.value)\"  />\n".$listdisplay."</SELECT>
	";
	
	function reduitletext($buffer,$newlenght){
		if (strlen($buffer)>$newlenght){
			$buffer= substr($buffer, 0, $newlenght);			
			$buffer=$buffer."..";
		}
		return($buffer);
	}
		
	function cleanurl($buffer){
		
		$buffer=trim($buffer);
				
		if (substr($buffer, 0,1)=="/"){			
			$buffer=substr($buffer, 1,strlen($buffer)-1);
		}
		
		if (substr($buffer, -1, 1)=="/"){
			$buffer=substr($buffer, 0,strlen($buffer)-1);
		}
		
		
		$buffer=ereg_replace("//","", $buffer);			
		
		return($buffer);
	}
	
	function preview($dirset,$rooturl){
		$ext=strtolower(strrchr($dirset, '.'));	
		switch(TRUE){
			case ($ext==".jpg" || $ext==".jpeg" || $ext==".gif" || $ext==".png"):	
				$size = getimagesize($dirset);					
				$width=$size[0];
				$height=$size[1]; 			
				
				$maxW=500;
				$maxH=92;
				$ratio=$height/92;					
				$thumbW=$width/$ratio;
				$thumbH=$height/$ratio;		
				
				if ($thumbW>=370){
					$thumbW=370;
					$thumbH=$height/($width/$thumbW);	
					
				}
				
				$fullsizeW=$width+30;
				$fullsizeH=$height+60;
							
				echo "
				<a href=\"javascript:open_newpopup('../view.php?pic=".$dirset."&nowatermark','viewserbrowser',$fullsizeW,$fullsizeH,'no','no');\">
				<img src=\"../$dirset\" width=$thumbW height=$thumbH border=0/></A><BR>Cliquez pour zoomer<BR>";
				break;
				
			case ($ext==".flv"):
 				flvplayer($dirset,$rooturl);
				break;						
			case ($ext==".mov" || $ext==".mpg" || $ext==".mpeg"):
				quicktime($rooturl.$dirset);
				break;					
			case ($ext==".avi" || $ext==".wmv" ):
				wmvplayer($rooturl.$dirset);
				break;					
			case ($ext==".mid" || $ext==".mp3" || $ext==".wav"):
				playerAudio($dirset,$rooturl);
				break;				
			default:
				echo"<a href=\"$rooturl/$dirset\" target=\"_blank\">Cliquez ici pour voir le fichier</A><BR><BR>";
				break;
		}
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