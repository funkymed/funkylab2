<?php
	if (isset($_GET['listcategorie'])){
		$typeset="&listcategorie=".$_GET['listcategorie'];		
	}else{
		$typeset="";
	}
	$wait="
		<object classid=\"clsid:d27cdb6e-ae6d-11cf-96b8-444553540000\" codebase=\"http://fpdownload.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=8,0,0,0\" width=\"24\" height=\"24\" id=\"ANIMATION\" align=\"middle\">
		<param name=\"allowScriptAccess\" value=\"sameDomain\" />
		<param name=\"movie\" value=\"".SKIN."/images/wait.swf\" />
		<param name=\"quality\" value=\"high\" />
		<param name=\"wmode\" value=\"transparent\" />
		<param name=\"bgcolor\" value=\"#dddddd\" />
		<embed src=\"".SKIN."/images/wait.swf\" quality=\"high\" wmode=\"transparent\" bgcolor=\"#dddddd\" width=\"24\" height=\"24\" name=\"ANIMATION\" align=\"middle\" allowScriptAccess=\"sameDomain\" type=\"application/x-shockwave-flash\" pluginspage=\"http://www.macromedia.com/go/getflashplayer\" />
		</object>
	";
	switch($contentwin){
		
//==================================================================================================
//	BILLET : CATEGORIE
//==================================================================================================
				
		case "ADDCAT":
		
			$annuler="index.php?cat=243&list$typeset";
			
			$altnom="Nom qui definis la categorie";
			$aldec="Affichage decroissant des pages de la categorie";
			$altcroi="Affichage decroissant des pages de la categorie";
			$altformnormal="Affichage des pages standard";
			$altformresume="Affichage des pages en resum�";
			$altformlien="Affichage des pages par uniquement leur nom";

			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$titre= stripslashes($rowtypepage['nom']);
				$option= explode(",",$rowtypepage['option_element']);
				$value=$rowtypepage['parent'];
				$sens=$option[0];
				$format=$option[1];
				$nbparpage=$option[2];
				$next="MODIFCAT";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";				
				$overrideskin=$rowtypepage['overrideskin'];
			}else{
				$overrideskin="";
				$hiddenmodif="";
				$titre="";
				$sens="croissant";
				$format="normal";
				$nbparpage="10";		
				$next="ADDCAT";		
			}		
			
			$listdescategories=listbillet($value);
			
			if ($sens=="croissant"){
				$checkcroissant="CHECKED";
				$checkdecroissant="";
			}else{
				$checkcroissant="";
				$checkdecroissant="CHECKED";
			}
			
			switch($format){
				case "normal":
					$checkformatnormal="CHECKED";
					$checkformatresume="";
					$checkformatlien="";
					break;
				case "resume":
					$checkformatnormal="";
					$checkformatresume="CHECKED";
					$checkformatlien="";
					break;
				case "lien":
					$checkformatnormal="";
					$checkformatresume="";
					$checkformatlien="CHECKED";
					break;
			}
						
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"200\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<fieldset>
								<legend>Information sur la categorie</legend>
								<table align=\"left\">
									<tr>
										<td>Nom</td><td> <INPUT NAME=\"titre\" value=\"$titre\" SIZE=\"50\" class='loginput' alt='$altnom' title='$altnom'/></td>
									</tr>
									<tr>
										<td>Parent</td><td>$listdescategories</td>
								 	</tr>
								 	<tr>
										<td colspan=\"2\">Nombre de pages visibles � la fois <input name=\"nbparpage\"  value='$nbparpage' SIZE=1 class='loginput' value='10'/></td>
									</tr>
								 </table>
							</fieldset>
							
							
								<fieldset>
									<legend>Sens</legend>
									<P align='left'>
										<INPUT type=radio class='noneradio' name='sensaffichage' value='croissant' $checkcroissant  alt='$altcroi' title='$altcroi'>Croissant<BR>
										<INPUT type=radio class='noneradio' name='sensaffichage' value='decroissant' $checkdecroissant alt='$aldec' title='$aldec'>Decroissant
									</P>
								</fieldset>
								
								<fieldset>
									<legend>Format</legend>
									<p align='left'>
										<INPUT type=radio class='noneradio' name='formatpage' value='normal' $checkformatnormal alt='$altformnormal' title='$altformnormal'>Normal (page complete)<BR>
										<INPUT type=radio class='noneradio' name='formatpage' value='resume' $checkformatresume alt='$altformresume' title='$altformresume'>Resum�<BR>
										<INPUT type=radio class='noneradio' name='formatpage' value='lien' $checkformatlien alt='$altformlien' title='$altformlien'>Lien
									</p>
								</fieldset>
							
								<fieldset>
										<legend>Skin Override</legend>";
											$contentwin.=listskin("../template/","skinsite",$overrideskin);
									$contentwin.="									
									</fieldset>	
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
										".endformulaire($hiddenmodif,$next,$value,$page)."
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
			</form>
			";
			
			break;	
				
//==================================================================================================
//	BILLET : TRADUCTION TITRE CATEGORIE
//==================================================================================================
				
		case "ADDTRADCAT":
		
			$annuler="index.php?cat=243&list$typeset";

			if ($valuemodif!=null){
				@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$titre= stripslashes($rowtypepage['nom']);
				$langue=$rowtypepage['option_element'];
				$next="MODIFTRADCAT"; 
				$value=$rowtypepage['parent'];
			}else{				
				$idLangue=$_GET['id'];
				$resNOMCAT=mysql_query("SELECT * FROM billet WHERE id=$idLangue");
				$rowNOMCAT = mysql_fetch_array($resNOMCAT);	
				$titre= stripslashes($rowNOMCAT['nom']);				
				$hiddenmodif="";
				$langue="";
				$next="ADDTRADCAT";		
			}				
			
			$annuler="index.php?cat=243&list$typeset";
					
			$bufferlangue=listlangue($langue);				
			
			$contentwin="
				<form>
					<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
						<tr>
							<td valign=\"top\" align=\"center\">
								<fieldset>
									<legend>Traduction du titre de la categorie</legend>
									<table align=\"left\">
										<tr>
											<td>Nom</td><td> <INPUT NAME=\"titre\" value=\"$titre\" SIZE=\"50\" /></td>
										</tr>
										<tr>
											<td>Langue</td><td>$bufferlangue</td>
										</tr>
									 </table>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td valign=\"bottom\" align=\"right\">
								<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
									<tr>
										<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
											<a href='$annuler'>ANNULER</A>
										</td>
										<td valign=\"top\" align=\"center\">
											<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					".endformulaire($hiddenmodif,$next,$value,$page)."					
				</form>
				";
				
				break;	
								
//==================================================================================================
//	BILLET : PAGE
//==================================================================================================
			
		case "ADDPAGE":
		
			$annuler="index.php?cat=243&list$typeset";
		
			$altnom="Nom qui defini la page";
			$altformat="La page serat format� seulons votre choix";
			$altcom="Les visiteurs pourront commenter la page";
			$altcom2="Les visiteurs ne pourront pas commenter la page";
			$override="La page aurat un habillage definis";
				
			$check1="";
			$check3="";
			$check2="";
			$check4="";
			$check5="checked=\"checked\"";
			$check6="checked=\"checked\"";
			$check7="checked=\"checked\"";
			$comcheck="checked=\"checked\"";
			$overrideskin="";
			
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				
				$nom=stripslashes($rowtypepage['nom']);
				$overrideskin=$rowtypepage['overrideskin'];
				$value=$rowtypepage['parent'];
				$option=explode(",",$rowtypepage['option_element']);
				
				if (count($option)<4){
					$option=$rowtypepage['option_element'].",1,1";
					$option=explode(",",$option);
				}
				
				if ($option[0]=="actif"){
					$comcheck="checked=\"checked\"";
				}else{					
					$comcheck="";
				}
				
				if ($option[1]=="1"){
					$check5="checked=\"checked\"";
				}else{
					$check5="";
				}				
				if ($option[2]=="1"){
					$check6="checked=\"checked\"";
				}else{
					$check6="";
				}			
				if ($option[3]=="1"){
					$check7="checked=\"checked\"";
				}else{
					$check7="";
				}				
				
				switch($rowtypepage['zone']){
					case "1b":
						$check1="CHECKED";
						break;
					case "1b2m":
						$check2="CHECKED";
						break;
					case "1m2b":
						$check3="CHECKED";
						break;
					case "1m2m3m":
						$check4="CHECKED";
						break;						
				}
				$next="MODIFPAGE";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				
			}else{
				$check1="CHECKED";
				$actifcom="CHECKED";
				$hiddenmodif="";
				$nom="";
				$next="ADDPAGE";
			}		
			
			$listdescategories=listbillet($value);
			
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<fieldset>
								<legend>Information sur la page</legend>
								<table align=\"left\">
									<tr>
										<td>Categorie</td><td>$listdescategories</td>
								 	</tr>
									<tr>
										<td>Nom</td><td> <INPUT NAME=\"titre\" SIZE=\"50\" class='loginput' value=\"$nom\"/></td>
									</tr>
								 </table>
							</fieldset>
							
							
							<fieldset>
								<legend alt='$altformat' title='$altformat'>Format de la page</legend>
								<table width=100%>
									<tr>								
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/page/format_1b.gif\" width=64 height=64><BR><INPUT type=radio class='noneradio' name='formatpage' value='1b' $check1></TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/page/format_1b2m.gif\" width=64 height=64><BR><INPUT type=radio class='noneradio' name='formatpage' value='1b2m' $check2></TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/page/format_1m2b.gif\" width=64 height=64><BR><INPUT type=radio class='noneradio' name='formatpage' value='1m2b' $check3></TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/page/format_1m2m3m.gif\" width=64 height=64><BR><INPUT type=radio class='noneradio' name='formatpage' value='1m2m3m' $check4></TD>
									</tr>
								</table>
							</fieldset>
							
							<fieldset>
								<legend>Option</legend>
								<table width=\"100%\">
									<tr>
										<td>
											
											<input type=checkbox class='noneradio' name='option_titre' $check6>Titre<br/>
											<input type=checkbox class='noneradio' name='option_cadre' $check7>Cadre
										</td>
										<td valign=\"top\">
											<input type=checkbox class='noneradio' name='option_signature' $check5> Signature et date<br/>
											<input type=checkbox class='noneradio' name='commentaire' $comcheck> Commentaire actif<br/>											
										</td>
									</tr>
								</table>
							</fieldset>
							
							<fieldset>
								<legend alt='$override' title='$override'>Skin Override</legend>";
									$contentwin.=listskin("../template/","skinsite",$overrideskin);
							$contentwin.="									
							</fieldset>	
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."
			</form>
			";
			break;	
//==================================================================================================
//	BILLET : TRADUCTION TITRE PAGE
//==================================================================================================
				
		case "ADDTRADPAGE":
		echo"ok";
			$annuler="index.php?cat=243&list$typeset";

			if ($valuemodif!=null){
				@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$titre= stripslashes($rowtypepage['nom']);
				$langue=$rowtypepage['option_element'];
				$next="MODIFTRADPAGE"; 
				$value=$rowtypepage['parent'];
			}else{				
				$idLangue=$_GET['id'];
				$resNOMCAT=mysql_query("SELECT * FROM billet WHERE id=$idLangue");
				$rowNOMCAT = mysql_fetch_array($resNOMCAT);	
				$titre= stripslashes($rowNOMCAT['nom']);				
				$hiddenmodif="";
				$langue="";
				$next="ADDTRADPAGE";		
			}				
			
			$annuler="index.php?cat=243&list$typeset";
					
			$bufferlangue=listlangue($langue);				
			
			$contentwin="
				<form>
					<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
						<tr>
							<td valign=\"top\" align=\"center\">
								<fieldset>
									<legend>Traduction du titre de paragraphe</legend>
									<table align=\"left\">
										<tr>
											<td>Nom</td><td> <INPUT NAME=\"titre\" value=\"$titre\" SIZE=\"50\" /></td>
										</tr>
										<tr>
											<td>Langue</td><td>$bufferlangue</td>
										</tr>
									 </table>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td valign=\"bottom\" align=\"right\">
								<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
									<tr>
										<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
											<a href='$annuler'>ANNULER</A>
										</td>
										<td valign=\"top\" align=\"center\">
											<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					".endformulaire($hiddenmodif,$next,$value,$page)."					
				</form>
				";
				
				break;

//==================================================================================================
//	BILLET : VIDEO
//==================================================================================================
					
	case "ADDVIDEO":
	
			$annuler="index.php?cat=243&list$typeset";
	
			$check="";
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$nom=stripslashes($rowtypepage['nom']);
				$option=$rowtypepage['option_element'];
				$value=$rowtypepage['parent'];
				$next="MODIFVIDEO";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				if ($option=="download"){
					$check="CHECKED";
				}
				$zone=$rowtypepage['zone'];
			}else{
				$zone="1";
				$hiddenmodif="";
				$next="ADDVIDEO"; 
			}
			
			$zoneselect=selectzone($zone,$value);
				
			$contentwin="	
			<form>
				<table border=\"0\" width=\"90%\" height=\"490\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">						
	
							$zoneselect
						
							<fieldset>
								<legend>Option</legend>	
								<input type=checkbox name='downloadable' class='noneradio' $check>Mettre un lien pour telecharger la video
							</fieldset>
							
							<fieldset>
								<legend>Video</legend>								
									<DIV id=\"minibrowser\">$wait</div>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."				
			</form>
			";
			break;

			
//==================================================================================================
//	BILLET : AUDIO
//==================================================================================================
		
	case "ADDAUDIO":
	
			$annuler="index.php?cat=243&list$typeset";
			$checkdownload="";
			$check="";
			if ($valuemodif!=null){
				@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$nom=stripslashes($rowtypepage['nom']);
				$value=$rowtypepage['parent'];
				$next="MODIFAUDIO";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$zone=$rowtypepage['zone'];
				
				
				if ($rowtypepage['option_element']==true){
					$checkdownload="checked=\"checked\"";
				}
				
			}else{
				$zone="1";
				$hiddenmodif="";
				$next="ADDAUDIO"; 
				$nom="";
			}
			
			$zoneselect=selectzone($zone,$value);
				
			$contentwin="	
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">						
	
							$zoneselect
							
							<fieldset>
								<legend>Fichier Audio (mp3,midi,wav)</legend>	
								<table width=\"100%\">
									<tr>
										<td>Nom du morceau</td>
										<td><input type=\"text\" name=\"nom\" value=\"$nom\"></td>
									</tr>
									<tr>
										<td>Fichier</td>
										<td><div id=\"minibrowser\">$wait</div></td>
									</tr>
								</table>
							</fieldset>		
							
							<fieldset>
								<legend>Option</legend>
								<p align=\"left\">
									<input type=checkbox class='noneradio' name='download' $checkdownload> Permettre de telecharger la musique
								</p>
							</fieldset>		
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."				
			</form>
			";
			break;	
//==================================================================================================
//	BILLET : IMAGE DE PAGE
//==================================================================================================
			
	case "ADDIMAGEPAGE":
	
			$annuler="index.php?cat=243&list$typeset";
	
			$check="";
			if ($valuemodif!=null){
				@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$nom=stripslashes($rowtypepage['nom']);
				$value=$rowtypepage['parent'];
				$next="MODIFIMAGEPAGE";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$zone=$rowtypepage['zone'];
				
				$optionadd=explode("/",$rowtypepage['option_element']);
				$taille=$optionadd[0];
				if ($optionadd[1]=="on"){
					$checkzoom="CHECKED";
				}else{
					$checkzoom="";
				}
				
			}else{
				$zone="1";
				$hiddenmodif="";
				$next="ADDIMAGEPAGE"; 
				$taille='100';
				$checkzoom="";
			}
			
			$zoneselect=selectzone($zone,$value);
				
			$contentwin="	
			<form>
				<table border=\"0\" width=\"90%\" height=\"490\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">						
	
							$zoneselect
							
							<fieldset>
								<legend>Image</legend>								
									<DIV id=\"minibrowser\">$wait</div>
							</fieldset>
							
							<fieldset>
								<legend>Taille de l'image dans le paragraphe</legend>	
								<input name='taille' size='1' style='text-align:center;width:20;' value='$taille'>% 
							</fieldset>		
							
							<fieldset>
								<legend>Zoom</legend>
								<input type=checkbox class='noneradio' name='zoom' $checkzoom> Clic pour voir la taille d'origine
							</fieldset>		
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."				
			</form>
			";
			break;	
			
//==================================================================================================
//	BILLET : DATE
//==================================================================================================
					
	case "ADDDATE":
	
			$check1="";
			$check2="";
			$check3="";
			$annuler="index.php?cat=243&list$typeset";
			
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$nom=stripslashes($rowtypepage['nom']);
				
				$value=$rowtypepage['parent'];
				$next="MODIFDATE";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				
				switch($rowtypepage['option_element']){
					case "limite":
						$check3="CHECKED";
						break;
					case "start":
						$check2="CHECKED";
						break;
					default:
						$check1="CHECKED";
						break;
				}
				
				$datedujour=datetiret($rowtypepage['date_debut']);
				$datelimite=datetiret($rowtypepage['date_fin']);
				
				if ($datedujour=="00/00/0000"){ $datedujour=date("d/m/Y");}
				if ($datelimite=="00/00/0000"){ $datelimite=date("d/m/Y");}
					
				
			}else{
				$check1="CHECKED";
				$hiddenmodif="";
				$next="ADDDATE";
				$datedujour=date("d/m/Y");
				$datelimite=$datedujour;
			}	
			
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							
							<fieldset>
								<legend>Date</legend>
								<P align='left'>";
								
								$contentwin.="
								<INPUT type=radio class='noneradio' name='datemode' value='' $check1> aujourd'hui le ".decodedate(date("Y-m-d"))."<BR>
								<INPUT type=radio class='noneradio' name='datemode' value='start' $check2> � partir de <INPUT NAME=\"debut_start\" SIZE=10 height=10 class='loginput' value='$datedujour'/><BR>
								<INPUT type=radio class='noneradio' name='datemode' value='limite' $check3> de <INPUT NAME=\"debut_limite\" SIZE=10 height=10 class='loginput' value='$datedujour'/> � <INPUT NAME=\"fin_limite\" SIZE=10 height=10 class='loginput' value='$datelimite'/>
								</P>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."				
			</form>
			";
			break;	
					
//==================================================================================================
//	BILLET : PARAGRAPHE
//==================================================================================================
					
	case "ADDTEXTE":
	
			$check1="";
			$check2="";
			$check3="";
			$check4="";
			$annuler="index.php?cat=243&list$typeset";
			
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$nom=stripslashes($rowtypepage['nom']);
				$value=$rowtypepage['parent'];
				$next="MODIFTEXTE";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$texte=stripslashes($rowtypepage['texte']);
				
				switch($rowtypepage['option_element']){
					case "left":
						$check1="CHECKED";
						break;
					case "right":
						$check2="CHECKED";
						break;
					case "centre":
						$check3="CHECKED";
						break;
					case "justifie":
						$check4="CHECKED";
						break;
				}
				$zone=$rowtypepage['zone'];
			}else{
				$texte="";
				$check1="CHECKED";
				$zone="1";
				$hiddenmodif="";
				$next="ADDTEXTE"; 
			}
			
			$zoneselect=selectzone($zone,$value);
	
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
						
							$zoneselect
						
							<fieldset>
								<legend>Alignement</legend>
								<table width=80%>
									<tr>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/paragraphe/gauche.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='left' $check1><BR>Gauche</TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/paragraphe/droite.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='right' $check2><BR>Droite</TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/paragraphe/centre.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='centre' $check3><BR>Centr�</TD>
										<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/paragraphe/justifie.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='justifie' $check4><BR>Justifi�</TD>
									</tr>
									
								</table>
								
							</fieldset>
							
							<fieldset>
								<legend>Texte du paragraphe</legend>
								<textarea id=\"elm1\" name=\"texte\" rows=\"8\" cols=\"40\">$texte</textarea>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			break;	
			
//==================================================================================================
//	BILLET : TRADUCTION PRAGRAPHE
//==================================================================================================
					
	case "ADDTRADTEXTE":

			$annuler="index.php?cat=243&list$typeset";
			
			if ($valuemodif!=null){
				@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$nom=stripslashes($rowtypepage['nom']);
				$value=$rowtypepage['parent'];
				$next="MODIFTRADTEXTE";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$texte=stripslashes($rowtypepage['texte']);
				$langue=$rowtypepage['option_element'];
			}else{
				$texte="";
				$langue="";				
				$hiddenmodif="";
				$next="ADDTRADTEXTE"; 				
			}
			
			$bufferlangue=listlangue($langue);			
	
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"340\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							
							<fieldset>
								<legend>Texte du paragraphe</legend>
								<textarea id=\"elm1\" name=\"texte\" rows=\"12\" cols=\"40\">$texte</textarea><br/>
							</fieldset>
							<fieldset>
								<legend>Langue</legend>
								$bufferlangue
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			
			break;	

//==================================================================================================
//	BILLET : HTML
//==================================================================================================
					
	case "ADDHTML":
			$annuler="index.php?cat=243&list$typeset";
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$nom=stripslashes($rowtypepage['nom']);
				$value=$rowtypepage['parent'];
				$next="MODIFHTML";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$html=stripslashes($rowtypepage['texte']);				
				$zone=$rowtypepage['zone'];
			}else{
				$html="";
				$zone="1";
				$hiddenmodif="";
				$next="ADDHTML"; 
			}
			
			$zoneselect=selectzone($zone,$value);
	
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
						
							$zoneselect
							
							<fieldset>
								<legend>Texte du paragraphe</legend>
								<textarea name=\"html\" rows=\"12\" cols=\"40\">$html</textarea>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			
			break;		
//==================================================================================================
//	BILLET : RSS
//==================================================================================================
			
		case "ADDRSS":
			$annuler="index.php?cat=243&list$typeset";
			$check1="";
			$check2="";
			$check3="";
			$check4="";
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$nom=stripslashes($rowtypepage['nom']);
				$next="MODIFRSS"; 
				$value=$rowtypepage['parent'];
				$option=$rowtypepage['option_element'];
				$option=explode(",",$option);
				$nbrss=$option[0];
				switch($option[1]){
					case "tiresans":
						$check1="checked=\"checked\"";
						break;
					case "titreavec":
						$check2="checked=\"checked\"";
						break;
					case "titredescsans":
						$check3="checked=\"checked\"";
						break;
					case "titredescavec":
						$check4="checked=\"checked\"";
						break;
					default:
						$check1="checked=\"checked\"";
						break;
				}
				$zone=$rowtypepage['zone'];
			}else{
				$check1="checked=\"checked\"";
				$nbrss="20";
				$nom="";
				$hiddenmodif="";
				$next="ADDRSS"; 
				$zone="1";
			}
			$zoneselect=selectzone($zone,$value);
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							$zoneselect
							
							<fieldset>
								<legend>Url du FLux RSS</legend>
								<P align='left'>
									<input name=\"fluxrss\" SIZE=70 height=10 class='loginput' value=\"$nom\"/>
								</P>
							</fieldset>
							<fieldset>
								<legend>Options d'affichage</legend>
								<p align='left'>
									<input type=\"text\" name=\"nbrss\" size=\"5\" class='loginput' value=\"$nbrss\" maxlength=\"2\"/> Nombre d'item maximum<br/>
									<input type=radio class='noneradio' name='rssoption' value='tiresans' $check1>Titres des items sans date de publication<br/>
									<input type=radio class='noneradio' name='rssoption' value='titreavec' $check2>Titres des items avec date de publication<br/>
									<input type=radio class='noneradio' name='rssoption' value='titredescsans' $check3>Titres des items et description sans date de publication<br/>
									<input type=radio class='noneradio' name='rssoption' value='titredescavec' $check4>Titres des items et description avec date de publication<br/>
								</p>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			break;	
//==================================================================================================
//	BILLET : URL
//==================================================================================================
			
	case "ADDURL":
	
			$check1="";
			$check2="";
			$annuler="index.php?cat=243&list$typeset";
			
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$value=$rowtypepage['parent'];
				$next="MODIFURL";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$nom=stripslashes($rowtypepage['nom']);
				$url=$rowtypepage['texte'];
				
				switch($rowtypepage['option_element']){
					case "_top":
						$check1="CHECKED";
						break;
					case "_blank":
						$check2="CHECKED";
						break;
				}
				$zone=$rowtypepage['zone'];
			}else{
				$nom="";
				$url="";
				
				$check1="CHECKED";
				$zone="1";
				$hiddenmodif="";
				$next="ADDURL"; 
			}
			
			$zoneselect=selectzone($zone,$value);	
	
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
						
							$zoneselect
						
							<fieldset>
								<legend>Lien</legend>
								<P align='left'>
									<table>
										<tr>
											<td>Nom</td><td><INPUT NAME=\"nomdulien\" SIZE=30 height=10 class='loginput' value=\"$nom\"/></td>
										</tr>
										<tr>
											<td>Url</td><td><INPUT NAME=\"lien\" SIZE=30 height=10 class='loginput' value='$url'/></td>
										</tr>
									</table>
								</P>
							</fieldset>
													
							<fieldset>
								<legend>Option</legend>
								<P align='left'>
									<INPUT type=radio class='noneradio' name='ouvrirlien' value='_top' $check1>Ouvrir le lien dans la page courante<BR>
									<INPUT type=radio class='noneradio' name='ouvrirlien' value='_blank' $check2>Ouvrir le lien dans une nouvelle page
								</P>
							</fieldset>				
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			break;	
			
//==================================================================================================
//	BILLET : FICHIER
//==================================================================================================
						
		case "ADDFILE":
	
			$check1="";
			$check2="";
			$annuler="index.php?cat=243&list$typeset";
			
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);
				$value=$rowtypepage['parent'];
				$next="MODIFFILE";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$nom=stripslashes($rowtypepage['nom']);
				$zone=$rowtypepage['zone'];
			}else{
				$nom="";
				$check1="CHECKED";
				$zone="1";
				$hiddenmodif="";
				$next="ADDFILE"; 
			}
					
			$zoneselect=selectzone($zone,$value);	
			
			$contentwin="	
			<form>
				<table border=\"0\" width=\"90%\" height=\"510\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
						
							$zoneselect
						
							<fieldset>
								<legend>Fichier</legend>			
								
									Nom <INPUT NAME=\"nomfichier\" SIZE=30 height=10 class='loginput' value=\"$nom\"/><BR><BR>
																					
									<DIV id=\"minibrowser\"></div>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."				
			</form>
			";
			break;		
					
//==================================================================================================
//	BILLET / PARAGRAPHE : TITRE
//==================================================================================================
					
		case "ADDTITRE":
		
			$annuler="index.php?cat=243&list$typeset";
		
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$nom=stripslashes($rowtypepage['nom']);
				$next="MODIFTITRE"; 
				$value=$rowtypepage['parent'];
			}else{
				$nom="";
				$hiddenmodif="";
				$next="ADDTITRE"; 
			}	
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<fieldset>
								<legend>Titre du paragraphe</legend>
								<P align='left'>
									<INPUT NAME=\"titre\" SIZE=70 height=10 class='loginput' value=\"$nom\"/>
								</P>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			break;
//==================================================================================================
//	BILLET : TRADUCTION TITRE PARAGRAPHE
//==================================================================================================
				
		case "ADDTRADTITRE":
		
			$annuler="index.php?cat=243&list$typeset";

			if ($valuemodif!=null){
				@mysql_select_db(BASEDEFAUT) or die("Impossible de se connecter � la base de donn�es");
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$titre= stripslashes($rowtypepage['nom']);
				$langue=$rowtypepage['option_element'];
				$next="MODIFTRADTITRE"; 
				$value=$rowtypepage['parent'];
			}else{				
				$idLangue=$_GET['id'];
				$resNOMCAT=mysql_query("SELECT * FROM billet WHERE id=$idLangue");
				$rowNOMCAT = mysql_fetch_array($resNOMCAT);	
				$titre= stripslashes($rowNOMCAT['nom']);				
				$hiddenmodif="";
				$langue="";
				$next="ADDTRADTITRE";		
			}				
			
			$annuler="index.php?cat=243&list$typeset";
					
			$bufferlangue=listlangue($langue);				
			
			$contentwin="
				<form>
					<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
						<tr>
							<td valign=\"top\" align=\"center\">
								<fieldset>
									<legend>Traduction du titre de paragraphe</legend>
									<table align=\"left\">
										<tr>
											<td>Nom</td><td> <INPUT NAME=\"titre\" value=\"$titre\" SIZE=\"50\" /></td>
										</tr>
										<tr>
											<td>Langue</td><td>$bufferlangue</td>
										</tr>
									 </table>
								</fieldset>
							</td>
						</tr>
						<tr>
							<td valign=\"bottom\" align=\"right\">
								<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
									<tr>
										<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
											<a href='$annuler'>ANNULER</A>
										</td>
										<td valign=\"top\" align=\"center\">
											<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>
					".endformulaire($hiddenmodif,$next,$value,$page)."					
				</form>
				";
				
				break;

//==================================================================================================
//	BILLET / PARAGRAPHE : IMAGE
//==================================================================================================
		
		case "ADDIMAGE":
	
			$check1="";
			$check2="";
			$check3="";
			$check4="";
			$check5="";
			$check6="";
			$annuler="index.php?cat=243&list$typeset";
			
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);
				$value=$rowtypepage['parent'];
				$next="MODIFIMAGE";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$value=$rowtypepage['parent'];
				switch($rowtypepage['option_element']){			
					case "align_haut_gauche":
						$check1="CHECKED";
						break;
					case "align_haut_centre":				
						$check2="CHECKED";
						break;						
					case "align_haut_droite":
						$check3="CHECKED";
						break;
					case "align_bas_gauche":
						$check4="CHECKED";
						break;
					case "align_bas_centre":				
						$check5="CHECKED";
						break;
					case "align_bas_droite":
						$check6="CHECKED";
						break;
				}	
				
				$optionadd=explode("/",$rowtypepage['zone']);
				$taille=$optionadd[0];
				if ($optionadd[1]=="on"){
					$checkzoom="CHECKED";
				}else{
					$checkzoom="";
				}
				
				
			}else{
				$nom="";
				$check1="CHECKED";
				$hiddenmodif="";
				$next="ADDIMAGE"; 
				$taille='100';
				$checkzoom="";
			}
					
			$contentwin="	
			<form>
				<table border=\"0\" width=\"90%\" height=\"400\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<table width=100%>
								<tr>
									<td width=50%>
										<fieldset>
											<legend>Haut</legend>
											<table width=100%>
												<tr>
													<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/image/topleft.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='align_haut_gauche' $check1><BR>Gauche</TD>
													<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/image/topcenter.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='align_haut_centre' $check2><BR>Centr�</TD>
													<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/image/topright.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='align_haut_droite' $check3><BR>Droite</TD>								
												</tr>
											</table>
										</fieldset>
									</td>
									<!--
									<td>
										<fieldset>
											<legend>Bas</legend>
											<table width=100%>
												<tr>
													<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/image/botomleft.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='align_bas_gauche' $check4><BR>Gauche</TD>
													<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/image/botomcenter.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='align_bas_centre' $check5><BR>Centr�</TD>
													<TD align=\"center\"><IMG SRC=\"".SKIN."/images/billet/image/botomright.gif\" width=21 height=21><BR><INPUT type=radio class='noneradio' name='alignement' value='align_bas_droite' $check6><BR>Droite</TD>								
												</tr>
											</table>								
										</fieldset>
									</td>
									-->
								</tr>
							</table>
							
							<fieldset>
								<legend>Image</legend>								
								<TABLE width=100%><TR><TD align='left'>
										<DIV id=\"minibrowser\" align='center'></div>
								</TD></TR></TABLE>
							</fieldset>
							
							<fieldset>
								<legend>Taille de l'image dans le paragraphe</legend>	
								<input name='taille' size='1' style='text-align:center;width:20;' value='$taille'>% 
							</fieldset>		
							
							<fieldset>
								<legend>Zoom</legend>
								<input type=checkbox class='noneradio' name='zoom' $checkzoom> Clic pour voir la taille d'origine
							</fieldset>													
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."			
			</form>
			";
			break;	
			
//==================================================================================================
//	BILLET : GALERIE
//==================================================================================================
					
		case "ADDGAL":
			$check1="CHECKED";
			$check2="";
			$check3="CHECKED";
			$check4="";
			$check5="";
			$annuler="index.php?cat=243&list$typeset";
			
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM billet WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);
				$next="MODIFGAL";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$zone=$rowtypepage['zone'];
				$value=$rowtypepage['parent'];
				
				$idgal=$rowtypepage['texte'];
				
				$option=explode(",",$rowtypepage['option_element']);
				
				$nbparpage=$option[0];
				switch($option[1]){
					case "galerie":
						$check1="CHECKED";
						$check2="";
						break;
					case "random":
						$check1="";
						$check2="CHECKED";
						break;
				}
				
				switch($option[2]){
					case "lien":
						$check3="CHECKED";
						$check4="";
						$check5="";
						break;
					case "icone":
						$check3="";
						$check4="CHECKED";
						$check5="";

						break;
					case "detail":
						$check3="";
						$check4="";
						$check5="CHECKED";
						break;
				}
								
			}else{
				$nom="";
				$hiddenmodif="";
				$next="ADDGAL"; 
				$nbparpage="6";
				$idgal="0";
				$zone="1";
			}		
		
			$zoneselect=selectzone($zone,$value);
			
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							
							$zoneselect
							
							<fieldset>
								<legend>Choix de la galerie</legend>
								<P align='left'>
								Selectionnez une galerie ".listgalerie($idgal)."
							</fieldset>	
							
							<fieldset>
								<legend>Option</legend>
								<P align='left'>
									<input type=radio name='option' value='galerie' $check1>Afficher toute la galerie. <input name=\"nbparlignegalerie\" SIZE=1 class='loginput' style='width:20;text-align:center;' value='$nbparpage'/> par ligne<br/>
									<input type=radio name='option' value='random' $check2><input name=\"nbparlignerandom\" SIZE=1 class='loginput' style='width:20;text-align:center;' value='$nbparpage'/> �l�ment(s) en random.
								</P>
							</fieldset>	
							
							<fieldset>
								<legend>Affichage</legend>
								<P align='left'>								
									<input type=radio name='affichage' value='lien' $check3>Afficher en lien (qui pointe sur le detail)<br/>
									<input type=radio name='affichage' value='icone' $check4>Afficher juste en ic�ne (sans detail)<br/>
									<input type=radio name='affichage' value='detail' $check5>Afficher en ic�ne avec le nom et le detail
								</P>
							</fieldset>		
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."
			</form>
			";
			break;				
			
//==================================================================================================
//	MENU : SOUS MENU
//==================================================================================================
			
		case "ADDMENU":
		
			$check1="";
			$check2="";
			$check3="";
			$check4="";		
			$check5="";
			$annuler="index.php?cat=247&list";			
			
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM menu WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);
				$next="MODIFMENU";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$value=$rowtypepage['parent'];
				$nom=stripslashes($rowtypepage['nom']);
				$image=$rowtypepage['image'];
				$ordre=$rowtypepage['ordre'];
				$contenuset="0";
				$url="";
				$galset="0";
				
				switch($rowtypepage['type']){
					case "type_bouton":
						$checkprincip1="";
						$checkprincip2="";	
						if ($rowtypepage['url']=="lien"){
							$checkprincip1="checked=checked";	
						}
						if ($rowtypepage['url']=="image"){
							$checkprincip2="checked=checked";	
						}
						break;
					case "type_contenu":
						$option=explode(",",$rowtypepage['url']);
						$contenuset=$option[0];
						$check1="checked=\"checked\"";				
						if ($option[1]==true){
							$check5="checked=\"checked\"";
						}						
						break;
					case "type_gallerie":
						$option=explode(",",$rowtypepage['url']);
						$galset=$option[0];
						$check2="checked=\"checked\"";
						if ($option[1]==true){
							$check5="checked=\"checked\"";
						}	
						break;
					case "type_url":
						$url=$rowtypepage['url'];		
						$check3="checked=\"checked\"";
						break;
					case "type_submenu":
						$check4="checked=\"checked\"";
						break;
				}				
				
			}else{	
				$contenuset="0";
				$galset="0";
				$check1="checked=\"checked\"";
				$nom="";
				$image="";
				$type="";
				$url="http://";									
				$hiddenmodif="";
				$ordre="0";
				$next="ADDMENU"; 
				$checkprincip1="checked=\"checked\"";
				$checkprincip2="";
				if (isset($_GET['id'])){
					$check1="checked=\"checked\"";
				}else{
					$check4="checked=\"checked\"";
					$check1="DISABLED";	
					$check2="DISABLED";	
					$check3="DISABLED";	
				}
			}
			
			$resparent=mysql_query("SELECT * FROM menu WHERE id=$value");
			$rowparent = mysql_fetch_array($resparent);			
			
			if ($rowparent['type']=="type_menu"){
				$hiddenmodif.="<input type='hidden' name='add_type' value='type_bouton'>\n
								<input type='hidden' name='url'>";
				$validimage=false;
			}else{
				$validimage=true;
			}
			
			if (($rowparent['type']!="type_submenu") && ($rowparent['type']!="type_bouton")){
				$check4="DISABLED";
			}
			
			if ($validimage==false){
				$varnomimage="
					<fieldset>
						<legend><input type=\"radio\" name=\"btnprincipal\" value=\"lien\" $checkprincip1>Bouton principal en lien</legend>
						<table>
							<tr>
								<td>Nom dans le menu</td><td><INPUT NAME='add_nom' SIZE=30 value=\"$nom\"></td>
							</tr>
						</table>
					</fieldset>
					<br/>
					<fieldset>
						<legend><input type=\"radio\" name=\"btnprincipal\" value=\"image\" $checkprincip2>Bouton principal en Image</legend>
						<table>
							<tr>
								<td valign='top'>Nom de l'image</td>
								<td>".listbtn(CURRENT_SKIN,$image)."</td>
							</tr>
							<tr>
								<td align='right' valign='top'><img src='".SKIN."/images/alert.gif' align='middle'></td>
								<td>
									L'image doit correspondre a cette nomemclature<br/>
									btn_IMAGE_on.gif ou btn_IMAGE_off.gif<br/>
									L'image doit etre dans le repertoire image du template
								</td>
							</tr>
						</table>
					</fieldset>
					";
				$typeselect="";
			}else{
				$varnomimage="";
				$typeselect="
				<fieldset>
					<legend>Bouton</legend>
					<table>
						<tr>
							<td>Nom dans le menu</td><td><INPUT NAME='add_nom' SIZE=30 value=\"$nom\"></td>
						</tr>
					</table>
				</fieldset>
				<br/>
				<fieldset>
					<legend align='left'>Type</legend>
					<table width=90% align='center'>
						<tr>
							<td><input type='radio' class='noneradio' name='add_type' value=\"type_contenu\" $check1>Contenu</td>
							<td>".listbillet($contenuset,"all")."</td>
						</tr>
						<tr>
							<td><input type='radio' class='noneradio' name='add_type' value=\"type_gallerie\" $check2>Galerie</td>
							<td>".listgalerie($galset)."</td>
						</tr>
						<tr>
							<td><input type='radio' class='noneradio' name='add_type' value=\"type_url\" $check3>Url</td>
							<td><INPUT NAME='add_url' SIZE=30 value='$url'></td>
						</tr>
						<tr>
							<td><input type='radio' class='noneradio' name='add_type' value=\"type_submenu\" $check4>Sous Menu</td>
							<td></td>
						</tr>
					</table>
				</fieldset>
				<br/>
				<fieldset>
					<legend align='left'>Option</legend>
					<table width=90% align='center'>
						<tr>
							<td><input type=\"checkbox\" name=\"btn_develop\" $check5> Developper l'arborescence du billet ou de la galerie</td>
						</tr>
					</table>
				</fieldset>
				
				";					
			}
		
			$contentwin="		
					<FORM ACTION='index.php' method='GET'>						
							<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
								<TR>
									<TD valign='top'>
										$varnomimage
										
										$typeselect
										
									</TD>
								</TR>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<input type='hidden' name='ordre' value='$ordre'>
				".endformulaire($hiddenmodif,$next,$value,$page)."
				</FORM>
				";		
			break;
			
//==================================================================================================
//	MENU
//==================================================================================================
			
		case "ADDNEWMENU":
		
			$check1="";
			$check2="";	
			
			$checktype1="";
			$checktype2="";
			$checktype3="";
			
			$annuler="index.php?cat=247&list";
			
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM menu WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);
				$next="MODIFMENU";
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$value=$rowtypepage['parent'];
				$nom=stripslashes($rowtypepage['nom']);
				$image=$rowtypepage['image'];
				$ordre=$rowtypepage['ordre'];
				
				
				$optionmenu=explode(",",$rowtypepage['url']);				
				
				switch($optionmenu[0]){					
					case "deroulant":
						$checktype1="checked=\"checked\"";
						break;
					case "lien":
						$checktype2="checked=\"checked\"";
						break;
					case "tableau":
						$checktype3="checked=\"checked\"";
						break;		
				}
				
				switch($optionmenu[1]){
					case "vertical":
						$check1="checked=\"checked\"";
						break;
					case "horizontal":
						$check2="checked=\"checked\"";
						break;
				}			
					
			}else{	
				$res=mysql_query("SELECT * FROM menu WHERE parent=0 ORDER BY id");			
				$nb=array();			
				while ($row = mysql_fetch_array($res)){   					
					$nb[]=substr($row['nom'],5,1);				
				}		
				
				sort ($nb);			
				if (!is_array($nb)){
					$nb=1;
				 }else{
					$nb=$nb[count($nb)-1]+1;				
				}
				
				
				$nom="#MENU$nb#";
				$contenuset="0";
				$galset="0";
				$check1="checked=\"checked\"";
				$checktype1="checked=\"checked\"";					
				$hiddenmodif="";
				$ordre="0";
				$next="ADDMENU";
			}			
		
			$contentwin="		
					<FORM ACTION='index.php' method='GET'>						
							<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
								<TR>
									<TD valign='top'>
										<FIELDSET>
											<LEGEND align='left'>Information</LEGEND>
											<p align='justify'>Pour afficher ce menu vous devez le d�clarer dans votre template par son nom</p>
											<center>$nom</center>
										</FIELDSET>
										<br/>
										<FIELDSET>
											<LEGEND align='left'>Type de menu</LEGEND>
											<input type='radio' class='noneradio' name='typemenu' value=\"deroulant\" $checktype1>D�roulant<br/>
											<input type='radio' class='noneradio' name='typemenu' value=\"lien\" $checktype2>Lien<br/>
											<input type='radio' class='noneradio' name='typemenu' value=\"tableau\" $checktype3>Tableau
										</FIELDSET>																				
										<br/>
										<FIELDSET>
											<LEGEND align='left'>Affichage</LEGEND>
											<input type='radio' class='noneradio' name='affichage' value=\"vertical\" $check1>Vertical<br/>
											<input type='radio' class='noneradio' name='affichage' value=\"horizontal\" $check2>Horizontal
										</FIELDSET>										
									</TD>
								</TR>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				<input type='hidden' name='ordre' value='$ordre'>
				<input type='hidden' name='add_nom' value='$nom'>
				<input type='hidden' name='add_type' value='type_menu'>
				<input type='hidden' name='add_image' value=''>
				".endformulaire($hiddenmodif,$next,$value,$page)."
				</FORM>
				";		
			break;			
			
//==================================================================================================
//	GALERIE : ELEMENT
//==================================================================================================
									
		case "ADDELEMENTGAL":
			
			if ($valuemodif!=null){
				
				$res=mysql_query("SELECT * FROM photo WHERE id = '".$valuemodif."'");
				$row = mysql_fetch_array($res);
				
				$idactuel = $row['id'];
				$nom = stripslashes($row['nom']);
				$file = $row['file'];
				$desc = stripslashes($row['description']);
				$type = $row['type'];
				$prix = $row['prix'];
				$tailleX = $row['tailleX'];
				$tailleY = $row['tailleY'];
				$mots = $row['motscle'];
				$date = $row['date'];
				$pays = $row['pays'];
				$reso = $row['resolution'];
				$plimus = $row['plimus'];
				$hiddenmodif="
				<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$next="MODIFELEMENTGAL";
			}else{
				
				$nom="";
				$file="";
				$desc="";
				
				$type="";
				if (isset($_GET['listgalerie'])){
					$type=$_GET['listgalerie'];
				}
				$prix="";
				$tailleX="";
				$tailleY="";
				$mots="";
				$date=date("d/m/Y");
				$pays="";
				$reso="";
				$plimus="";
				$hiddenmodif="<input type=\"hidden\" name=\"listgalerie\" value='".$_GET['listgalerie']."'>";
				$next="ADDELEMENTGAL";
			}
			
			if (isset($_GET['listcategorie'])){
				$annuler="index.php?cat=244&list&type=".$_GET['listgalerie'];
			}else{
				$annuler="index.php?cat=244&list";
			}
			
			//".optionlist_ok("type_photo","id","type",$type).
			
			$contentwin="
			<FORM ACTION='index.php' method='GET'>						
					<table border=\"0\" width=\"90%\" height=\"620\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
						<TR>
							<TD valign='top' align='center'>
								<fieldset>
									<legend>Information</legend>
									<table border=0 width=100%>
										
										<tr height=10>
											<td valign='top'>Nom</td><td valign='top'><INPUT NAME='image_nom' SIZE=20 VALUE='$nom'></td>
											<td valign='top'>Type</td><td valign='top'> ".listgalerie($type)."</td>
										</tr>
										<tr  height=10>
											<td valign='top'>Date</td><td valign='top'><INPUT NAME='image_date' SIZE=10 value='$date'></td>
											<td valign='top'>Lieu/Pays</td><td valign='top'><INPUT NAME='image_pays' SIZE=10 value='$pays'></td>
										</tr>
									</table>
								</fieldset>
								
								<fieldset>
									<legend>Fichier</legend>
									<DIV id=\"minibrowser\">$wait</div>
								</fieldset>	
								
								<fieldset>
									<legend>Information supl�mentaire</legend>					
									<table width='100%' border=0>
										<tr>
											<td align='left'>Mots cl�</td><td><INPUT class='loginbox' NAME='image_mots' SIZE=50 value='$mots'></td>
										</tr>
										<tr>
											<td align='left'>Description</td><td><INPUT class='loginbox' NAME='desc' SIZE=50 value='$desc'></td>
											
										</tr>
									</table>
								</fieldset>
								
								<fieldset>
									<legend>Photo information sur l'original</legend>
									<table width='100%' border=0>
										<tr>
											<td>Px Largeur</td>
											<td><INPUT NAME='image_tailleX' SIZE=5 value='$tailleX'></td>
											<td>Px Hauteur</td>
											<td><INPUT NAME='image_tailleY' SIZE=5 value='$tailleY'></td>
										</tr>
										<tr>
											<td>RESOLUTION</td><td colspan=3><INPUT NAME='image_resolution' SIZE=30 value='$reso'></td>
										</tr>
									</table>
								</fieldset>
								
								<fieldset>
									<legend>Information commercial</legend>	
									PLIMUS <INPUT NAME='image_plimus' SIZE=30 VALUE='$plimus'> PRIX <INPUT NAME='image_prix' SIZE=4 value='$prix'> �
								</fieldset>
								
							</td>
						</tr>
						<tr>
							<td valign=\"bottom\" align=\"right\">
								<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
									<tr>
										<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
											<a href='$annuler'>ANNULER</A>
										</td>
										<td valign=\"top\" align=\"center\">
											<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
										</td>
									</tr>
								</table>
							</td>
						</tr>
					</table>	
					".endformulaire($hiddenmodif,$next,$value,$page)."
				</FORM>	";
				break;		
			
//==================================================================================================
//	GALERIE
//==================================================================================================
			
		case "ADDTYPEGAL":
		
			$annuler="index.php?cat=245&list";
		
			if ($valuemodif!=null){
				$txt="SELECT * FROM type_photo WHERE id='$valuemodif'";
				$res_modiftype=mysql_query($txt);
				$row = mysql_fetch_array($res_modiftype);  
				$type = stripslashes($row['type']);
				$parent = $row['parent'];
				$comment = stripslashes($row['commentaire']);
				$next="MODIFTYPEGAL";
				$hiddenmodif="
				<input type=\"hidden\" name='list'>				
				<input type=\"hidden\" name='modifID' value='$valuemodif'>";
			}else{
				$type ="";
				$comment ="";
				if (isset($_GET['id'])){
					$parent=$_GET['id'];
				}else{
					$parent ="0";
				}
				$next="ADDTYPEGAL";
				$hiddenmodif="";
			}		
				
			$contentwin="
			<FORM ACTION='index.php' method='GET' >
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign='top' align='center'>
							<fieldset>
								<legend>Parent</legend>
								".listypeGALERIE($parent)."
							</fieldset>
							<br/>
							<fieldset>
								<legend>Nom</legend>
								<input  NAME='update_type' SIZE=20 value='$type'/>	
							</fieldset>
							<br/>
							<fieldset>
								<legend>Commentaire</legend>
								<textarea id=\"elm1\" name=\"comment\" rows=\"8\" cols=\"40\">$comment</textarea>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."
			</FORM>";	
			break;
			
//==================================================================================================
//	COMPTE UTILISATEUR
//==================================================================================================
			
		case "ADDUSER":
			$annuler="index.php?cat=255&list";
			$Administrateur="";
			$check_Newsletter="";
			$check_Contenu="";
			$check_Galerie="";
			$check_Menu="";
			$check_Megabrowser="";
			$check_comments="";
			$check_restrictedbillet="";
			$check_pub="";
			if ($valuemodif!=null){
				$res=mysql_query("SELECT * FROM admin WHERE id = '".$valuemodif."'");
				$row = mysql_fetch_array($res);
				$id = $row['id'];
				$login = $row['login'];
				$pass = $row['pass'];
				$autorisation= $row['autorisation'];
				$nom= $row['nom'];
				$prenom= $row['prenom'];
				$email= $row['email'];
				$datecreation= $row['datecreation'];
				$contenuset=$row['optionadmin'];
				$derniereconnexion= $row['derniereconnexion'];
				$admin= $row['admin'];
				$autorisation= $row['autorisation'];
				$autorisation_array=explode(",",$autorisation);
				$next="MODIFUSER";
				
				$hiddenmodif="		
				<input type=\"hidden\" name='modifID' value='$valuemodif'>";			
				
				if(testuseradmin()!=true){				
					$hiddenmodif="				
					<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				}
				
				if($admin==true)				{	$Administrateur="CHECKED";			}
				if($autorisation_array[0]==true){	$check_Newsletter="CHECKED";		}
				if($autorisation_array[1]==true){	$check_Contenu="CHECKED";			}
				if($autorisation_array[2]==true){	$check_Galerie="CHECKED";			}
				if($autorisation_array[3]==true){	$check_Menu="CHECKED";				}
				if($autorisation_array[4]==true){	$check_Megabrowser="CHECKED";		}
				if($autorisation_array[5]==true){	$check_comments="CHECKED";			}
				if($autorisation_array[6]==true){	$check_restrictedbillet="CHECKED";	}
				if($autorisation_array[7]==true){	$check_pub="CHECKED";	}
					
			}else{
				$next="ADDUSER";
				$id = "";
				$login = "";
				$pass = "";
				$autorisation="";
				$nom= "";
				$prenom= "";
				$email= "";
				$datecreation= "";
				$derniereconnexion= "";
				$admin= "";
				$autorisation= "";				
				$hiddenmodif="";		
				$check_restrictedbillet="";	
				$contenuset =0;	
			}	
			
			$contentwin="
			<FORM ACTION='index.php' method='GET' >
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign='top' align='center'>
							<FIELDSET>
								<LEGEND>Informations utilisateur</LEGEND>
								<TABLE border=0 align='center' cellspacing='0' cellpadding='2' width=100%>
									<TR>
										<TD align='left' width=150>Login</td><td><INPUT class='userbox' NAME='add_login' SIZE=20 value='$login'></TD>
										<TD align='left'>Password</td><td><INPUT class='userbox' NAME='add_pass' SIZE=20 value='$pass' type='password'></TD>	
									</TR>
									<TR>
										<TD align='left'>Nom</td><td><INPUT class='userbox' NAME='add_nom' SIZE=20 value='$nom'></TD>
										<TD align='left'>Pr�nom</td><td><INPUT class='userbox' NAME='add_prenom' SIZE=20 value='$prenom'></TD>	
									</TR>
									<TR>
										<TD align='left'> Email</td><td><INPUT class='userbox' NAME='add_email' SIZE=20 value='$email'><br/></TD>
										<TD></TD>
									</TR>			
								</TABLE>
							</FIELDSET>";
							
							if(testuseradmin()==true){
								$listdescategories=listbillet($contenuset);
								$contentwin.="
								<BR>
								<FIELDSET>
									<LEGEND>Autorisations</LEGEND>
									<TABLE border=0 align='center' cellspacing='0' cellpadding='0' width=90%>
										<tr>
											<td width=10><input name='check_admin' class='noneradio' type=checkbox $Administrateur></td><td>Administrateur</td>
										</tr>						
										<tr>
											<td><input name='check_newsletter' class='noneradio' type=checkbox $check_Newsletter></td><td>Newsletter</td>
										</tr>						
										<tr>
											<td><input name='check_contenu' class='noneradio' type=checkbox $check_Contenu></td><td>Contenu</td>
										</tr>						
										<tr>
											<td><input name='check_galerie' class='noneradio' type=checkbox $check_Galerie></td><td>Galerie</td>
										</tr>						
										<tr>
											<td><input name='check_menu' class='noneradio' type=checkbox $check_Menu></td><td>Menu</td>
										</tr>
										<tr>
											<td><input name='check_megabrowser' class='noneradio' type=checkbox $check_Megabrowser></td><td>Gestionnaire de fichier</td>
										</tr>
										<tr>
											<td><input name='check_comments' class='noneradio' type=checkbox $check_comments></td><td>Gestionnaire de commentaire de la blacklist</td>
										</tr>
										<tr>
											<td><input name='check_restrictedbillet' class='noneradio' type=checkbox $check_restrictedbillet></td><td>Restraindre � une categorie $listdescategories
											</td>
										</tr>
										<tr>
											<td><input name='check_pub' class='noneradio' type=checkbox $check_pub></td><td>Bandeau PUB</td>
										</tr>
								 	</TABLE>
								</FIELDSET>
								";
							}else{
								$contentwin.="
								<input type=\"hidden\" name=\"contenu\" value=\"$contenuset\">
								";
							}
						$contentwin.="
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."
			</FORM>";	
			break;
//==================================================================================================
//	COMMENTAIRES 
//==================================================================================================
					
	//==================================================================================================
	//	COMMENTAIRES : BLACKLIST
	//==================================================================================================
			
		case "ADDBLACKLIST":
		
			$annuler="index.php?cat=241&list$typeset";
		
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM blacklist WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				$hiddenmodif="<input type=\"hidden\" name='modifID' value='$valuemodif'>";
				$mot=stripslashes($rowtypepage['mot']);
				$next="MODIFBLACKLIST"; 
			}else{
				$mot="";
				$hiddenmodif="";
				$next="ADDBLACKLIST"; 
			}
				
			$contentwin="
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<fieldset>
								<legend>Mot interdit</legend>
								<P align='left'>
									<INPUT NAME=\"mot\" SIZE=70 height=10 class='loginput' value=\"$mot\"/>
								</P>
							</fieldset>
							<br/>
							<fieldset>
								<legend><img src='".SKIN."/images/alert.gif' align='middle'>Important</legend>
								<P align='left'>
									La blacklist permet de bloquer un mot, des combinaisons de mots, ou des bouts de phrase. Dans la partie configuration de l'administration vous pouvez indiquer de filtrer les commentaires � partir de l'�criture de celui ci, ou alors de le faire par vous meme � partir d'ici.<br/>
									Il est �galement possible de filtrer les tags HTML.
									
								</P>
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			break;		
	//==================================================================================================
	//	COMMENTAIRES 
	//==================================================================================================
			
		case "ADDCOMMENT":
		
			$annuler="index.php?cat=242&list$typeset&page=0";
			$disabled="";
			if ($_GET['select']==1){
				$resCOM=mysql_query("SELECT * FROM comments WHERE id=$valuemodif");
				$rowCOM = mysql_fetch_array($resCOM);	
				
				
								
				$next="MODIFCOMMENT"; 								
				$id=$rowCOM['id'];
				$commentdate=$rowCOM['commentdate'];
				$billetid=$rowCOM['billetid'];
				$pseudo=$rowCOM['pseudo'];
				$email=$rowCOM['email'];
				$text=stripslashes($rowCOM['text']);
				$ip_utilisateur=$rowCOM['ip_utilisateur'];			
				$hiddenmodif="
				<input type=\"hidden\" name='modifID' value='$valuemodif'>
				<input type=\"hidden\" name=\"page\" value=\"0\"/>				
				<input type=\"hidden\" name=\"billetid\" value=\"$billetid\"/>";
			}else{				
				
				
				$resCOM=mysql_query("SELECT * FROM comments WHERE id=$valuemodif");
				$rowCOM = mysql_fetch_array($resCOM);	
				$billetid=$rowCOM['billetid'];
				
				echo $valuemodif;
				$disabled="disabled";
				$mot="";
				
				$pseudo=recupeall($_SESSION["funkylab_id"],"admin","nom")." ".recupeall($_SESSION["funkylab_id"],"admin","prenom");
				$email=recupeall($_SESSION["funkylab_id"],"admin","email");
				
				$hiddenmodif="
				<input type=\"hidden\" name='modifID' value='$valuemodif'>
				<input type=\"hidden\" name=\"page\" value=\"0\"/>
				<input type=\"hidden\" name=\"pseudo\" value=\"$pseudo\"/>
				<input type=\"hidden\" name=\"email\" value=\"$email\"/>
				<input type=\"hidden\" name=\"billetid\" value=\"$billetid\"/>
				";
				
				$text="";
				$next="ADDCOMMENT"; 
			}	
			
			$contentwin="			
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<fieldset>
								<legend>Auteur du message</legend>
								<table align=\"left\">
									<tr>
										<td>Pseudo</td>
										<td><input name=\"pseudo\" size=\"60\" class=\"loginput\" value=\"$pseudo\" $disabled/></td>
									</tr>
									<tr>
										<td>Email</td>
										<td><input name=\"email\" size=\"60\" class=\"loginput\" value=\"$email\" $disabled/></td>
									</tr>
								</table>
							</fieldset>
							<fieldset>
								<legend>Message</legend>
								<textarea cols=\"45\" rows=\"10\" name=\"message\" id=\"elm1\">$text</textarea>								
							</fieldset>
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			break;	
	//==================================================================================================
	//	BANNIERES PUB 
	//==================================================================================================
			
		case "ADDPUB":
		
			$annuler="index.php?cat=256&list";
		
			if ($valuemodif!=null){
				$restypepage=mysql_query("SELECT * FROM bannieres WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				
				$url=$rowtypepage['url'];
				$nbclic=$rowtypepage['nbclic'];
				$hiddenmodif="
				<input type=\"hidden\" name='modifID' value='$valuemodif'>
				<input type=\"hidden\" name='nbclic' value='$nbclic'>
				";
				$next="MODIFPUB"; 
			}else{
				$file="";
				$url="http://";
				$nbclic="0";
				$hiddenmodif="<input type=\"hidden\" name='nbclic' value='$nbclic'>";
				$next="ADDPUB"; 
			}
		
			$contentwin="	
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<fieldset>
								<legend>Banni�re de pub</legend>
								<P align='left'>
									URL <INPUT NAME=\"url\" SIZE=60 class='loginput' value=\"$url\"/>
								</P>
								<DIV id=\"minibrowser\" align='center'></div>
								
								Nombre de clic : <b>$nbclic</b>
							</fieldset>

							<br/>
							<fieldset>
								<legend><img src='".SKIN."/images/alert.gif' align='middle'>Important</legend>
								<P align='left'>
									Veuillez utiliser un m�me format pour l'enssemble de vos bandeaux publicitaire (exemple : 468x60 pixels).
									
								</P>
							</fieldset>
							
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			
			break;
	//==================================================================================================
	//	BANNIERES PUB 
	//==================================================================================================
			
		case "ADDEMAIL":
		
			$annuler="index.php?cat=252&listmail";
		
			if ($valuemodif!=null){
				
				$restypepage=mysql_query("SELECT * FROM mail_newsletter WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				
				$ipEMAIL=$rowtypepage['IPcreation'];
				$email=$rowtypepage['mail'];
				$hiddenmodif="
				<input type=\"hidden\" name='modifID' value='$valuemodif'>
				<input type=\"hidden\" name='listmail'>
				
				";
				$next="MODIFEMAIL"; 
			}else{
				$ipEMAIL="";
				$email="";
				$hiddenmodif="<input type=\"hidden\" name='listmail'>";
				$next="ADDEMAIL"; 
			}
		
			$contentwin="	
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<fieldset>
								<legend>Email abonn�e</legend>
								<table>
									<tr>
										<td>EMAIL <INPUT NAME=\"email\" SIZE=20 class='loginput' value=\"$email\"/></td>
										<td>IP <INPUT NAME=\"ipEMAIL\" SIZE=20 class='loginput' value=\"$ipEMAIL\"/></td>
									</tr>
								</table>
							</fieldset>
							
							<br/>
							<fieldset>
								<legend><img src='".SKIN."/images/alert.gif' align='middle'>Attention</legend>
								<P align='left'>
									Afin de valider l'email, quand vous validerez les modifications votre nom et pr�nom seront enregistr�s � la place de l'IP. La date d'enregistrement de l'email serat mis � la date d'aujourd'hui.
								</P>
							</fieldset>
							
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			
			break;
			
	//==================================================================================================
	//	MESSAGERIE
	//==================================================================================================
			
		case "ADDMSG":
		
			$annuler="index.php?cat=253&list";
		
			if ($valuemodif!=null){
				
				$restypepage=mysql_query("SELECT * FROM messagerie WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);	
				
				$hiddenmodif="
				<input type=\"hidden\" name='modifID' value='$valuemodif' />
				<input type=\"hidden\" name='list' />";
				
				$next="READMSG"; 
				
				$objet=stripslashes($rowtypepage['objet']);
				$message=stripslashes($rowtypepage['message']);
				$from=$rowtypepage['fromqui'];
				$from=recupeall($from,"admin","nom")." ".recupeall($from,"admin","prenom");
				
				$zoneauteur="<legend>Auteur du message </legend><input type=\"text\" value=\"$from\" size=\"60\" disabled />";
				$zoneobjet="<input type=\"text\" name=\"objet\" size=\"60\" value=\"$objet\" disabled/>";
				$zonemessage="<TextArea class=\"message\" name=\"message\" COLS=\"45\" ROWS=\"10\" readonly id=\"elm1\">$message</textarea>";
				$btn="
					<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
						<tr>
							<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
								<a href='$annuler'>OK</A>
							</td>
						</tr>
					</table>
				";
				
				messagerie::checkifread($valuemodif);
				
			}else{
				$hiddenmodif="<input type=\"hidden\" name='list'>";
				$next="ADDMSG"; 
				$objet="";
				
				if (isset($_GET['envoyerA'])){
					$from=$_GET['envoyerA'];
				}else{
					$from="";
				}
							
				$zoneauteur="<legend>Destinataire</legend>".messagerie::list_contact_mail("admin","id","nom",$from);
				$zoneobjet="<input type=\"text\" name=\"objet\" size=\"60\"/>";
				$zonemessage="<TextArea class=\"message\" name=\"message\" COLS=\"45\" ROWS=\"10\" id=\"elm1\"></textarea>";
				
				$btn="
					<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
						<tr>
							<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
								<a href='$annuler'>ANNULER</A>
							</td>
							<td valign=\"top\" align=\"center\">
								<INPUT TYPE='submit' VALUE='ENVOYER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
							</td>
						</tr>
					</table>
				";
			}
			
			
			$contentwin="	
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<fieldset>
								$zoneauteur
							</fieldset>	
							<fieldset>
								<legend>Objet</legend>
								$zoneobjet
							</fieldset>		
							<fieldset>
								<legend>Message</legend>
								$zonemessage
								
							</fieldset>	
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							$btn
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			
			break;
			
			
	//==================================================================================================
	//	NEWSLETTER
	//==================================================================================================
			
		case "ADDNEWSLETTER":
		
			$annuler="index.php?cat=251&listletter";
			
			if ($valuemodif!=null){				
				$restypepage=mysql_query("SELECT * FROM newsletter WHERE id=$valuemodif");
				$rowtypepage = mysql_fetch_array($restypepage);		
							
				$titre=stripslashes($rowtypepage['titre']);
				$edito=stripslashes($rowtypepage['edito']);
				$billets=explode(",",$rowtypepage['billets']);
				$lastbillet=$rowtypepage['lastbillet'];	
							
				$hiddenmodif="
				<input type=\"hidden\" name='modifID' value='$valuemodif'>
				<input type=\"hidden\" name='listletter'>
				
				";
				$next="MODIFNEWSLETTER"; 
			}else{
				$titre="";	
				$edito="";	
				$billets="";	
				$lastbillet=0;
				$hiddenmodif="<input type=\"hidden\" name='listletter'>";
				$next="ADDNEWSLETTER"; 
			}
			
			$multiplebillet=listbillet_multiple($billets,"all");
		
			$contentwin="	
			<form>
				<table border=\"0\" width=\"90%\" height=\"320\" cellspacing=\"0\" cellpadding=\"0\" align=\"center\">
					<tr>
						<td valign=\"top\" align=\"center\">
							<fieldset>
								<legend>Edito</legend>
								<table>
									<tr>
										<td>Titre <input size=\"59\"  name=\"titre_edito\" value=\"$titre\"/></td>
									<tr>
									</tr>
										<td><textarea cols=\"40\" rows=\"6\" name=\"texte_edito\">$edito</textarea></td>
									</tr>
								</table>
							</fieldset>		
							<fieldset>
								<legend>Billets</legend>
								<table>
									<tr>
										<td>$multiplebillet<br/><br/></td>
									</tr>
									<tr>
										<td>Nombre des derniers billets <input type=\"text\" name=\"lastbillet\" size=\"2\" maxlength=\"2\" class='loginput' value=\"$lastbillet\"></td>
									</tr>
								</table>
							</fieldset>													
						</td>
					</tr>
					<tr>
						<td valign=\"bottom\" align=\"right\">
							<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\">
								<tr>
									<td class=\"winbuton\" onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\" valign=\"middle\" align=\"center\">
										<a href='$annuler'>ANNULER</A>
									</td>
									<td valign=\"top\" align=\"center\">
										<INPUT TYPE='submit' VALUE='VALIDER' class='winbuton' onmouseover=\"className='winbutonover'\" onmouseout=\"className='winbuton'\">
									</td>
								</tr>
							</table>
						</td>
					</tr>
				</table>
				".endformulaire($hiddenmodif,$next,$value,$page)."					
			</form>
			";
			
			break;
}
	
//==================================================================================================
//	CHOIX DE LA ZONE OU METTRE LES ELEMENTS DES BILLETS
//==================================================================================================
		
	function selectzone($zone,$id){		
		
		$check1="";
		$check2="";
		$check3="";
		
		$namevar="check".$zone;
		
		$$namevar="CHECKED";
		
		$buffer="";
		$restypepage=mysql_query("SELECT * FROM billet WHERE id=$id");
		$rowtypepage = mysql_fetch_array($restypepage);			
		
		switch($rowtypepage['zone']){
			case "1b":
				$buf="acune zone selectionnable<input type=hidden name='zone' value='1'>";
				break;
			case "1b2m":
				$buf="
					<table>
						<tr>
							<td align='center'><IMG SRC=\"".SKIN."/images/billet/ico_page_1bset2m.gif\"><BR><input type=radio class='noneradio' name='zone' value='1' $check1></td>
							<td align='center'><IMG SRC=\"".SKIN."/images/billet/ico_page_1b2mset.gif\"><BR><input type=radio class='noneradio' name='zone' value='2' $check2></td>
						</tr>
					</table>
				";
				break;
			case "1m2b":
				$buf="
					<table>
						<tr>
							<td align='center'><IMG SRC=\"".SKIN."/images/billet/ico_page_1mset2b.gif\"><BR><input type=radio class='noneradio' name='zone' value='1' $check1></td>
							<td align='center'><IMG SRC=\"".SKIN."/images/billet/ico_page_1m2bset.gif\"><BR><input type=radio class='noneradio' name='zone' value='2' $check2></td>
						</tr>
					</table>
				";
				break;	
			case "1m2m3m":
				$buf="
					<table>
						<tr>
							<td align='center'><IMG SRC=\"".SKIN."/images/billet/ico_page_1mset2m3m.gif\"><BR><input type=radio class='noneradio' name='zone' value='1' $check1></td>
							<td align='center'><IMG SRC=\"".SKIN."/images/billet/ico_page_1m2mset3m.gif\"><BR><input type=radio class='noneradio' name='zone' value='2' $check2></td>
							<td align='center'><IMG SRC=\"".SKIN."/images/billet/ico_page_1m2m3mset.gif\"><BR><input type=radio class='noneradio' name='zone' value='3' $check3></td>
						</tr>
					</table>
				";
				break;	
		}
		
		$buffer="
		<fieldset>
			<legend>Zone d'affichage</legend>	
			$buf
		</fieldset>";		
		
		return($buffer);
	}
	
//==================================================================================================
//	BAS DES BALISES </FORM>
//==================================================================================================
		
	function endformulaire($hiddenmodif,$next,$value,$page){	
		
		if (($next=="ADDMENU") || ($next=="MODIFMENU")){
			$cat=247;
		}else{
			$cat=243;
		}
		
		if (($next=="ADDELEMENTGAL") || ($next=="MODIFELEMENTGAL")){
			$cat=244;
		}		
		
		if (($next=="ADDELEMENTGAL") || ($next=="MODIFELEMENTGAL")){
			$cat=244;
		}
		
		if (($next=="ADDTYPEGAL") || ($next=="MODIFTYPEGAL")){
			$cat=245;
		}	

		if (($next=="ADDUSER") || ($next=="MODIFUSER")){
			$cat=255;
		}					
		
		if (($next=="ADDCOMMENT") || ($next=="MODIFCOMMENT")){
			$cat=242;
		}
		
		if (($next=="ADDBLACKLIST") || ($next=="MODIFBLACKLIST")){
			$cat=241;
		}		
		
		if (($next=="ADDPUB") || ($next=="MODIFPUB")){
			$cat=256;
		}
		
		if (($next=="ADDEMAIL") || ($next=="MODIFEMAIL")){
			$cat=252;
		}
		if (($next=="ADDNEWSLETTER") || ($next=="MODIFNEWSLETTER")){
			$cat=251;
		}
		if ($next=="ADDMSG"){
			$cat=253;
		}
		
		
		if (($cat==243) && ($next!="ADDPAGE") && ($next!="MODIFPAGE") && ($next!="ADDCAT") && ($next!="MODIFCAT")){
			if (isset($_GET['listcategorie'])){
				$hiddenmodif.="<input type=\"hidden\" name=\"listcategorie\" value=\"".$_GET['listcategorie']."\">";	
			}
		}
		
		$buffer="
			<input type=\"hidden\" name='ADDWORK' value='$next'>
			<input type=\"hidden\" name='CATID' value='$value'>
			<input type=\"hidden\" name='cat' value='$cat'>
			<input type=\"hidden\" name='list'>
			$hiddenmodif	
		";
		
		return($buffer);
	}
	
?>