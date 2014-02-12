<?php
//==================================================================================================
//	CONFIGURATION
//==================================================================================================

	$resconfig=mysql_query("SELECT * FROM config");
	$rowconfig = mysql_fetch_array($resconfig);

	$nomsite=stripslashes($rowconfig['nomsite']);
	$online=$rowconfig['online'];
	$skinsite=$rowconfig['skinsite'];
	$skinadmin=$rowconfig['skinadmin'];
	
	$checkon="";
	$checkoff="";	
	
	$check1="";	
	$check2="";	
	$check3="";	
	$check4="";	
	$check5="";	
	$check6="";	
	$check7="";	
	$check8="";	
	$check9="";	
	
	switch($rowconfig['watermark_position']){
		case "topleft":
			$check1="CHECKED";	
			break;
		case "topcenter":
			$check2="CHECKED";	
			break;
		case "topright":
			$check3="CHECKED";	
			break;
		case "midleft":
			$check4="CHECKED";	
			break;
		case "midcenter":
			$check5="CHECKED";	
			break;
		case "midright":
			$check6="CHECKED";	
			break;
		case "botomleft":
			$check7="CHECKED";	
			break;
		case "botomcenter":
			$check8="CHECKED";	
			break;
		case "botomright":
			$check9="CHECKED";	
			break;
			
	}	
	
	
	if ($rowconfig['online']=="on")	  {		$checkon="CHECKED";		}else{		$checkoff="CHECKED";	}
	if ($rowconfig['watermark']==true){		$checkwater="CHECKED";	}else{		$checkwater="";			}
	if ($rowconfig['filtrecom']==true){		$checkfiltre="CHECKED";	}else{		$checkfiltre="";		}
	
	
	windowscreate("CONFIGURATION DU SITE",null,null,"debut",3);
		echo"
		<table border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
			<tr class='windcontenu3'>
				<td></td>
			</tr>
			<tr class='winline'>
				<TD colspan=3></td>
			</tr>
			<tr>
				<TD align='center'>
				<table><tr><TD align='center'>
					<FORM ACTION='index.php' method='GET' >
						<fieldset>
							<LEGEND>Configuration</LEGEND>
							<table border=0 width=90% align='center'>
								<tr>
									<td>Nom du site</td>
									<td><INPUT class='userbox' NAME='nomsite' SIZE=40 value=\"$nomsite\"></td>
								</tr>
								<tr>
									<td>En ligne</td>
									<td>
										ON <input type='radio' name='online' value='1' $checkon>
										OFF <input type='radio' name='online'  value='0'$checkoff>
									</td>
								</tr>
							</table>
						</fieldset>
						<br/>
						<fieldset>
							<LEGEND>Habillage</LEGEND>
							<table width=90% align='center'>
								<tr>
									<TD width=200>
										Habillage de l'administration <br/>							
										Habillage du site
									</td>
									<td>
										",listskin("template/","skinadmin",$skinadmin),"<br/>
										",listskin("../template/","skinsite",$skinsite),"
									</td>
								</tr>
							</table>
						</fieldset>	
						<br/>
						<fieldset>
							<LEGEND>Watermark sur les images des galeries</LEGEND>
							<p align='left'>
								<input type=checkbox name='watermark' $checkwater> Watermark sur les images<br>
								
								<table width=100%>
									<tr>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/config/watermark_top_left.gif\"><BR><INPUT type=radio class='noneradio' name='watermark_position' value='topleft' $check1></TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/config/watermark_top_center.gif\"><BR><INPUT type=radio class='noneradio' name='watermark_position' value='topcenter' $check2></TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/config/watermark_top_right.gif\"><BR><INPUT type=radio class='noneradio' name='watermark_position' value='topright' $check3></TD>								
									</tr>
									<tr>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/config/watermark_middle_left.gif\"><BR><INPUT type=radio class='noneradio' name='watermark_position' value='midleft' $check4></TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/config/watermark_middle_center.gif\"><BR><INPUT type=radio class='noneradio' name='watermark_position' value='midcenter' $check5></TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/config/watermark_middle_right.gif\"><BR><INPUT type=radio class='noneradio' name='watermark_position' value='midright' $check6></TD>								
									</tr>
									<tr>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/config/watermark_bottom_left.gif\"><BR><INPUT type=radio class='noneradio' name='watermark_position' value='botomleft' $check7></TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/config/watermark_bottom_center.gif\"><BR><INPUT type=radio class='noneradio' name='watermark_position' value='botomcenter' $check8></TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/config/watermark_bottom_right.gif\"><BR><INPUT type=radio class='noneradio' name='watermark_position' value='botomright' $check9></TD>								
									</tr>
								</table>
							
								<br/>								
								
								<DIV id=\"minibrowser\">Veuillez patentier...</div>
							</p>
						</fieldset>	
						<br/>
						<fieldset>
							<LEGEND>Commentaire</LEGEND>
							<p align='left'>
								<input type=checkbox name='filtrecom' $checkfiltre> Filtre des commentaires<br/>
							</p>
						</fieldset>	
						
						
						
						<br/>
						<INPUT TYPE='submit' VALUE='METTRE A JOUR' class='windcontenu3' onmouseover=\"className='winOVER3'\" onmouseout=\"className='windcontenu3'\">					
						<input type='hidden' name='updateconfig'>
						<input type='hidden' name='cat' value='254'>
					</FORM>
					</td></tr></table>
				</td>
			</tr>
		</table>";
		windowscreate(null,null,null,null,null);


?>