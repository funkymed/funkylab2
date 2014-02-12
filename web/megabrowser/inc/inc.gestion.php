<?php

	$autorisation_session_check = $_SESSION["funkylab_autorisation"];		
	$buffer="";
	if ($admin==true){
		$buffer.="
		<TABLE id=\"adminoption\" cellspacing=0 cellpadding=0>
			<tr>
				<td class='onglet' onmouseover=\"className='ongletOVER'\" onmouseout=\"className='onglet'\">
					<a href='$filephp?dir=$dirset$addurl&boxnewrep'>Nouveau repertoire</a>
				</td>
				<td class='onglet' onmouseover=\"className='ongletOVER'\" onmouseout=\"className='onglet'\">
					<a href='$filephp?dir=$dirset$addurl&boxload'>Charger un fichier</a>
				</td>";
				if ($autorisation_session_check[2]=="1"){
					$buffer.="
					<td class='onglet' onmouseover=\"className='ongletOVER'\" onmouseout=\"className='onglet'\">
						<a href='$filephp?dir=$dirset$addurl&boxgalerie'>Ajouter � une galerie</a>
					</td>";
				}
			$buffer.="
					<!--<TD> <a href='$filephp?dir=$dirset$addurl&boxconfigview'>Config affichage</a></TD>-->
			</tr>
		</TABLE>";
	}
	
	
	$buffer.="	
	<TABLE border=0 class='tablevignette'><TR>";
				
	$count=0;
	
	$dirset=str_replace("///","/",  $dirset);		
	$dirset=str_replace("//","/",  $dirset);
	
	if (($dirset!=$dirROOT."/") && ($dirset!=$dirROOT)){				
		if (isset($_GET['dir']) || isset($_POST['dir'])){	
			$count=1;				
			$workedir=explode("/",$dirset);
			$countdir=count($workedir);
			$parentdir="";
			for ($AA=0;$AA<=$countdir-3;$AA++){
				$parentdir.=$workedir[$AA]."/";
			}
			$parentdir=$parentdir.$addurl;
			$parentdir=str_replace("///","/",  $parentdir);		
			$parentdir=str_replace("//","/",  $parentdir);		
			
			$buffer.= "<TD class='vignette' onmouseover=\"className='vignetteOVER'\" onmouseout=\"className='vignette'\" valign='top'><a href='$filephp?dir=$parentdir'><IMG SRC='$root/megabrowser/template/img/parent.png' width='$iconeW' height='$iconeH'' border=0><br>
			..</a></TD>";
		}
	}
	
	$totalrep=0;
	
	if($dirfound==true){
		natcasesort($dirs);			
			foreach ($dirs as $dirs){
 					 			
	 			$count+=1;
	 			$buffer.= "<TD class='vignette' onmouseover=\"className='vignetteOVER'\" onmouseout=\"className='vignette'\" valign='top'>
		 			<table border=0 width=100% height=100%>
		 			";	 			
	 			
		 			if ($admin==true){
		 				$buffer.= "
			 			<TR>
				 			<TD class='infobox'>					 			
								<TABLE width='100%'>
								<TR>
									<TD align='left'>
										<a href=\"#\" onclick=\"RenameDirPrompt('Renommer $dirs','$dirs'); return false;\">Renommer</A>
									</TD>
									<TD align='right'>	
										<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer le repertoire $dirs et son contenu ?','$filephp?dir=$dirset$addurl&deleteDIR=$dirs'); return false;\"><IMG SRC='$root/megabrowser/template/img/delete.png' border=0></A>
									</TD>
								</TR>
								</TABLE>
				 			</TD>
			 			</TR>
			 			";
		 			}
		 					 			
		 			$newdirview=$dirset."/".$dirs.$addurl;
					$newdirview=str_replace("///","/",  $newdirview);		
					$newdirview=str_replace("//","/",  $newdirview);					
		 			
		 			$buffer.= "
		 			<TR>
			 			<TD align='center' valign='top'>
				 			<a href='$filephp?dir=$newdirview'><IMG SRC='$root/megabrowser/template/img/dossier.png' width='$iconeW' height='$iconeH' border=0 alt=\"$dirs\" title=\"$dirs\"><br>
							".reduitletext($dirs,$lenghtname)."</a>";
							if ($admin==true){
								$buffer.= "
								<br/><br/>
								<a href='$filephp?zipit=$dirset/$dirs$addurl&dirname=$dirs&dir=$dirset'>Zipper</a><br/>
								";
							}
						$buffer.= "
						</TD>
					</TR>
				</TABLE>
			</TD>";
			
			if ($count>=$maxparligne){
	 			$buffer.= "</TR><TR>";
	 			$count=0;
 			}
 			$totalrep+=1;
		} 
	}
	
	$filetotalsize=0;
	$totalfile=0;
	
	
	if (strlen($dirset)-1!="/"){
		$dirset=$dirset."/";
	}	
	
	if($filefound==true){
		natcasesort($files);
		foreach ($files as $file){
			$count+=1;
			$buffer.= "<TD class='vignette' onmouseover=\"className='vignetteOVER'\" onmouseout=\"className='vignette'\" valign='top'>
			<table border=0 width=100% height=100%>";
			if ($admin==true){
 				$buffer.= "	
				<TR>
					<TD class='infobox'>
					
						<TABLE width='100%'>
							<TR>
								<TD align='left'>
									<a href=\"#\" onclick=\"RenameDirPrompt('Renommer $file','$file'); return false;\">Renommer</A>
								</TD>
								<TD align='right'>
									<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer ce fichier : $file ?','$filephp?dir=$dirset$addurl&delete=$file'); return false;\"><IMG SRC='$root/megabrowser/template/img/delete.png' border=0></A>
								</TD>
							</TR>
						</TABLE>
					</td>
				</TR>";
			}
			
			$buffer.= "
			<TR>
				<TD align='center' valign='top'>
			";
			
			$ext=strtolower(strrchr($file, '.'));
			$filesizedisp=number_format((filesize($dirset."/".$file)/1024),2);
			
			$filesize=filesize($dirset."/".$file)/1024;
			
			$datemodifbufer=date ("Y-m-d/H:i:s", filemtime($dirset."/".$file));
			
			$datemodifbufer=explode("/",$datemodifbufer);
			$datemodif=decodedate($datemodifbufer[0])." � ".$datemodifbufer[1];
			
			$fileurlencode=codefilename(urlencode($file));
			
 			$newfileview=$dirset.$fileurlencode;
			$newfileview=str_replace("///","/",  $newfileview);		
			$newfileview=str_replace("//","/",  $newfileview);					
			
			switch(TRUE){
				
				/************ FICHIER IMAGE ******************/
				case ($ext==".jpg" || $ext==".jpeg" || $ext==".gif" || $ext==".png"):
				
					$picture_error=false;
					$size = @getimagesize($dirset."/".$file);
					
					if (($size['mime']=="image/jpeg") || ($size['mime']=="image/gif") || ($size['mime']=="image/png")){					
						$width=$size[0];
						$height=$size[1];						
						if (($width>=1800) || ($height>=1800)){
							$picture_error=true;
						}else{						
							$wpx=$size[0];
							$hpx=$size[1];															
							echo createthumb("thumb_".$file,$file,$dirset,$size);
						}
					}else{
						$picture_error=true;
					}
					
					if ($picture_error==false){												
						$buffer.= "
						<a href=\"javascript:open_newpopup('view.php?pic=$newfileview','viewserbrowser',$wpx,$hpx,'no','no');\">		
						<IMG SRC='$dirset/thumb_".decodefilename(urlencode($file))."' border=0 alt='$file $datemodif' title='$file $datemodif' $width px / $height px'><br>
						".reduitletext($file,$lenghtname)."</a>";
					}else{
						$buffer.= "<IMG SRC='$root/megabrowser/template/img/none.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br/>".reduitletext($file,$lenghtname);
					}
					
					break;
					
					/************ FICHIER AUDIO ******************/
				case ($ext==".mp3"):
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',340,280,'no','no');\">		
					<IMG SRC='$root/megabrowser/template/img/audioMP3.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					break;
				case ($ext==".wav"):
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/audioWAV.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					break;
				case ($ext==".ogg"):
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/audioOGG.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					break;						
				case ($ext==".mod" || $ext==".xm"):
					$buffer.= "<a href=\"".$newfileview."\"><IMG SRC='$root/megabrowser/template/img/audioMOD.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					break;					
				case ($ext==".mid"):
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/audioMIDI.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					break;	
					
					/************ FICHIER VIDEO ******************/
					
				case ($ext==".avi" || $ext==".mpg" || $ext==".mpeg" || $ext==".wmv"):
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/mediaplayer.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					break;	
				case ($ext==".flv"):
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',360,300,'yes','yes');\"><IMG SRC='$root/megabrowser/template/img/videoFLV.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					break;
				case ($ext==".mov"):
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',340,300,'no','no');\"><IMG SRC='$root/megabrowser/template/img/videoMOV.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					break;
				case ($ext==".rm" || $ext==".ram"):
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',340,280,'no','no');\"><IMG SRC='$root/megabrowser/template/img/videoRM.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					break;							
																												
					/************ FICHIER TEXTE ******************/					
				case ($ext==".txt"):
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',700,300,'yes','yes');\"><IMG SRC='$root/megabrowser/template/img/txt.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
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
					$buffer.= "<a href=\"javascript:open_newpopup('view.php?pic=".$newfileview."','viewserbrowser',700,300,'yes','yes');\"><IMG SRC='$root/megabrowser/template/img/zip.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname)."</a>";
					
					if ($admin==true){
						$buffer.= "<BR><BR>
						<a href='$filephp?unzip=$fileurlencode&dir=$dirset'>Extraire ici</a><br/>
						";
					}
					break;							
												
					/************ CONNAIS PAS ******************/					
				default:
					$buffer.= "<IMG SRC='$root/megabrowser/template/img/none.png' width='$iconeW' height='$iconeH' border=0 alt='$file $datemodif' title='$file $datemodif'><br>
					".reduitletext($file,$lenghtname);
					break;
			}
			
			$buffer.="<br/><br/>
						<a href=\"".$dirset.$file."\" target=\"_blank\">T�l�charger</a>
					<br/><br/>
					$filesizedisp ko
					</TD></TR></TABLE>
					</TD>";
					
			$filetotalsize+=$filesize;
			$totalfile+=1;
			
			if ($count>=$maxparligne){
	 			$buffer.= "</TR><TR>";
	 			$count=0;
 			}
 			
		}		
	}
	
	$buffer.= "</TR></TABLE>";
	
	$buffer0.= "<P>Le repertoire contient  <b>$totalrep dossier(s)</b> et <b>$totalfile fichier</b> pour <b>".number_format($filetotalsize,2)." ko</b></P>";
	
	
?>