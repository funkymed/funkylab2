<?

class billet{
	
	function billetlist($page,$lettre,$orderby,$search){
		$cat=243;
		echo checkbase(BASEDEFAUT,$cat,$page,"&list");	
		
		$nbcom=allcount("billet");
		$txttitle="";
		$javascriptbillet="";
		$autorisation=$_SESSION["funkylab_autorisation"];
		if ($autorisation[6]=="1"){
			$txttitle.="GERER VOTRE ESPACE INTERNET<BR><center>";
			$_GET['listcategorie']=$_SESSION["funkylab_optionadmin"];
		}else{
			$txttitle.="BILLETS<BR><center>";
		}
		
		windowscreate($txttitle,null,null,"debut",251);
		echo"
		<form action='index.php' method='GET'>
			<fieldset>
				<legend>GESTIONNAIRE DE CONTENU</legend>				
				<table border=0 align='center' cellspacing='0' cellpadding='0' width=100%>
					<tr class='windcontenu3'>					
						<TD width=10></TD>
						<TD width=10>ID</TD>
						<TD width=\"80\">Visible</TD>
						<TD width=\"80\">Premiere Page</TD>
						<TD align=\"left\">Nom</TD>						
						<TD width=\"80\">Langue</TD>						
						<TD width=\"80\">Auteur</TD>	
						<TD width=\"80\">Delete</TD>	
					</tr>
					<tr class='winline'>
						<td colspan=9></td>
					</tr>";
								
				if (isset($_GET['listcategorie'])){
					$listcategorie=$_GET['listcategorie'];
					$typehidden="<input type=\"hidden\" name=\"listcategorie\" value=\"$listcategorie\">";
					$res=mysql_query("SELECT * FROM billet WHERE  id='$listcategorie' ORDER BY nom,zone,ordre");
					$javascriptbillet=billet::makejavascript($listcategorie);
				}else{
					$typehidden="";
					$res=mysql_query("SELECT * FROM billet WHERE parent=999999999 ORDER BY nom,zone,ordre");
				}
				
				$error=false;
				$ul="";
				
				while ($row = mysql_fetch_array($res)){ 
					$count=billet::rowmenu($row,0,$ul,$page,$lettre,$orderby,$search,"PARENT",0);
				}		
				
				echo "
				<tr class='winline'>
					<td colspan=9></td>
				</tr>
				<tr class='windcontenu3'>			
					<td colspan=9 align='right'>
						<table border=0 width=100%>
							<tr>
								<td align='right'>
									<div id=\"menuListBillet\">
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
					</td>
				</tr>
			</table>
			<input type=\"hidden\" name=\"cat\" value=$cat>
			<input type=\"hidden\" name='search' value='$search'>			
			<input type=\"hidden\" name='list'>		
			$typehidden
		</form>
		";			
		windowscreate(null,null,null,null,null);
		echo billet::legende();
		echo $javascriptbillet;
	}	
	
	
	
	function rowmenu($row,$count,$ul,$page,$lettre,$orderby,$search,$famille,$GRANDparent){
		if (isset($_GET['listcategorie'])){
			$typehidden="&listcategorie=".$_GET['listcategorie'];		
		}else{
			$typehidden="";
		}
		
		$addurlpage="&cat=243&list$typehidden";		
		$id = $row['id'];
		$nom =reduitletext(stripslashes($row['nom']),50);
		$visible = $row['visible'];
		$premierepage= $row['premierepage'];
		$parent = $row['parent'];
		$type = $row['type'];	
		$option = $row['option_element'];	
		$ordre = $row['ordre'];	
		$auteur = recupeall($row['auteur'],"admin","prenom")." ".recupeall($row['auteur'],"admin","nom");			
		$langue="";
		$date_debut = "";
		$date_fin = "";
		$alt="";
		$zone=$row['zone'];	
		$texte=stripslashes($row['texte']);
		$icone=billet::billet_icone($type,$option,$zone);
		$viewUL="";
		$visibletxt="";
		$premierepagetxt="";
		$disable="";
		$check="";
		
		$count++;
		
		if ($count % 2 == 0) {
			 $class="windcontenu"; 
		}else{ 
			$class="windcontenu2"; 
		}
		if (isset($_GET['select'])){
			$select=$_GET['select'];
		}else{
			$select=0;
		}	
		if (isset($_GET['id']) && $select!=2){
			$disable="DISABLED";
			if ($_GET['id']==$id){
				$check="CHECKED";
				$class="winOVER";
			}		
		}
		
		/*** si il n'y a pas de billet associé il peut etre effacer sans probleme ***/
		if (billet::ifparent($id)==true){		
			$alertName =reduitletext(stripslashes($row['nom']),50);
			$delete="<a href=\"#\" onclick=\"ConfirmChoice('Voulez vous vraiment effacer $alertName ?','index.php?select=2&id=$id$addurlpage'); return false;\"><IMG SRC='".SKIN."/images/delete.png' border=0></A>";
		}else{
			$delete="";
		}
		
		$zone=billet::checkzone($zone,$type,$parent);
					
		switch($type){
			case "traduction_categorie":
				$langue=$row['option_element'];
				break;
			case "traduction_page":
				$langue=$row['option_element'];
				break;
			case "traduction_texte":
				$langue=$row['option_element'];
				break;
			case "traduction_titre":
				$langue=$row['option_element'];
				break;							
			case "date":				
				$date_debut = $row['date_debut'];
				$date_fin = $row['date_fin'];
				if ($option=="limite"){
					$nom="visible du ".decodedate($date_debut)." au ".decodedate($date_fin);
				}else{
					$nom="visible à partir du ".decodedate($date_debut);
				}
				break;	
			case "gallerie":
				$nom=strtolower($row['nom']);
				break;
			case "page":
				$nom="<a href=\"javascript:open_newpopup('modules/billet/billet.preview.php?page=$id','viewserbrowser',880,600,'no','no');\">$nom</a>";	
				$icone="<a href=\"javascript:deroule('$id','".billet::countElementSince($id,0)."')\"><img src=\"".SKIN."/images/listopen.png\" id=\"fleche$id\" name=\"fleche$id\" border=\"0\"></a>".$icone;
				break;
			case "categorie":
				$nom="<a href=\"javascript:open_newpopup('modules/billet/billet.preview.php?page=$id','viewserbrowser',880,600,'no','no');\">$nom</a>";	
				break;
			case "texte":
				$nom=reduitletext(strip_tags($texte),50);
				break;
		}

		if ($parent=="0" || $type=="page" || $type=="categorie"){ 	
			$count=0;
			$GRANDparent=$id;
			
			$newpage="	
			<tr bgcolor=\"#ffffff\" height=\"2\">
				<td colspan=8></td>
			</tr>";	
					
			if ($premierepage==1){
				$premierepagetxt="<a href='index.php?hidepost=$id&premierepage=$premierepage$addurlpage'><IMG SRC='".SKIN."/images/premierepage_on.gif' border=0></A>";
			}else{
				$premierepagetxt="<a href='index.php?hidepost=$id&premierepage=$premierepage$addurlpage'><IMG SRC='".SKIN."/images/premierepage_off.gif' border=0></A>";
			}
			if ($visible==1){
				$visibletxt="<a href='index.php?hidepost=$id&hideset=$visible$addurlpage'><IMG SRC='".SKIN."/images/viewon.png' border=0></A>";
			}else{
				$visibletxt="<a href='index.php?hidepost=$id&hideset=$visible$addurlpage'><IMG SRC='".SKIN."/images/viewoff.png' border=0></A>";
			}
		}else{			
			$newpage="";			
			$newordreup=$ordre-1;
			$newordredown=$ordre+1;			
		}	
			
		/** Permet de ne pas afficher la gestion de l'ordre **/
		if ($type!="categorie" && $type!="page" && $type!="titre" && $type!="illustration" && $type!="picflux" && $type!="traduction_page" && $type!="traduction_texte" && $type!="traduction_categorie" && $type!="traduction_titre"){			
			$ordremodif="<a href='index.php?hidepost=$id&ordrenew=$newordreup$addurlpage'><IMG SRC=\"".SKIN."/images/billet/ico_ordre_up.png\" border=0></A> $ordre <a href='index.php?hidepost=$id&ordrenew=$newordredown$addurlpage'><IMG SRC=\"".SKIN."/images/billet/ico_ordre_down.png\" border=0></A>";
		}else{
			$ordremodif="";
		}	
		
		if ($type!="categorie" && $type!="page"){		
			$trcode="id=\"listobjchange-$GRANDparent-$count\"";
		}else{
			$trcode="";
		}

		
		if ($parent==0){ 
			$bb="<B>"; $bb_="</B>";
		}else{ 
			$bb=""; $bb_="";
			$viewUL=$ul."<IMG SRC='".SKIN."/images/joinbottom.gif' border=0>".$ordremodif.$zone;
			
		}
		
		echo"
		$newpage		
		<tr class='$class' onmouseover=\"className='winOVER'\" onmouseout=\"className='$class'\" $trcode>	
			<td width=20><input type=radio name='id' onchange=\"writeMenuListBillet($id)\"  value='$id' $check $disable></td>
			<td><a href='index.php?&select=1&id=".$id.$addurlpage."'>$id</a></td>
			<td>$visibletxt </td>
			<td>$premierepagetxt</td>
			<td align='left'>".$bb.$viewUL.$icone." $nom $bb_</td>
			<td>$langue</td>
			<td>$auteur</td>
			<td>$delete</td>
		</tr>\n";
			
		/*** CHILD **/
		$ul.="<img src='".SKIN."/images/spacer.gif' border=0>";
		if ($type=="categorie"){
			$option=$row['option_element'];	
			$option=explode(",",$option);
			if ($option[0]=="croissant"){
				$DESC="";
			}else{
				$DESC="DESC";
			}			
			$reschild=mysql_query("SELECT * FROM billet WHERE parent=$id ORDER BY id $DESC");
		}else{
			$reschild=mysql_query("SELECT * FROM billet WHERE parent=$id ORDER BY zone, ordre");
		}
		
		while ($rowchild = mysql_fetch_array($reschild)){
			$count=billet::rowmenu($rowchild,$count,$ul,$page,$lettre,$orderby,$search,"CHILD",$GRANDparent);			
		}		
		return($count);
	}
	
	function ifparent($id){
		$checkparentRES=mysql_query("SELECT * FROM billet WHERE parent=$id");
		$checkparentROW = mysql_fetch_array($checkparentRES);
		if($checkparentROW){ return(false); }else{ return(true); }
	}
	
	function countElementSince($id,$nb){
		$resCountChild=mysql_query("SELECT id FROM billet WHERE parent=$id");
		while ($rowCountChild = mysql_fetch_array($resCountChild)){
			$id=$rowCountChild['id'];
			$nb=billet::countElementSince($id,$nb);	
			$nb++;		
		}
		return($nb);		
	}

	function makejavascript($id){
		$javascriptbuffer="\r<script type=\"text/javascript\">\n";
		$listcategorie=$_GET['listcategorie'];
		$res=mysql_query("SELECT * FROM billet WHERE id='$listcategorie'");
		$buffer="";
		while ($row = mysql_fetch_array($res)){ 
			$buffer=billet::childJavaScript($row['id'],$row['type'],$buffer);
		}
		$javascriptbuffer.=$buffer;
		$javascriptbuffer.="</script>\n";		
		return($javascriptbuffer);
	}
	
	function childJavaScript($id,$type,$buffer){		
		$idbillet=$id;

		if (isset($_GET['id'])){
			$IDselect=billet::returnIdPageFrom($_GET['id']);
			if ($type=="page" && $_GET['id']!=$idbillet && $IDselect!=$idbillet){
				$buffer.="deroule('$idbillet','".billet::countElementSince($idbillet,0)."');\n";
			}
		}else{	
			if (isset($_GET['hidepost'])){
				$IDselect=billet::returnIdPageFrom($_GET['hidepost']);
				if ($type=="page" && $_GET['hidepost']!=$idbillet && $IDselect!=$idbillet){
					$buffer.="deroule('$idbillet','".billet::countElementSince($idbillet,0)."');\n";
				}
			}else{
				if (isset($_GET['CATID'])){
					$IDselect=billet::returnIdPageFrom($_GET['CATID']);
					if ($type=="page" && $_GET['CATID']!=$idbillet && $IDselect!=$idbillet){
						$buffer.="deroule('$idbillet','".billet::countElementSince($idbillet,0)."');\n";
					}
				}else{
					if ($type=="page"){
						$buffer.="deroule('$idbillet','".billet::countElementSince($idbillet,0)."');\n";
					}
				}
			}
		}
		
		$res=mysql_query("SELECT * FROM billet WHERE parent=$id");
		while ($row = mysql_fetch_array($res)){
			$buffer=billet::childJavaScript($row['id'],$row['type'],$buffer);
		}	
		return($buffer);
		
	}
	
	
	
	function returnIdPageFrom($id){
		$resCountParent=mysql_query("SELECT parent,type FROM billet WHERE id=$id");
		while ($rowCountParent = mysql_fetch_array($resCountParent)){
			$parent=$rowCountParent['parent'];
			$type=$rowCountParent['type'];
			if ($type=="page"){
				return($id);
				break;
			}
			$id=billet::returnIdPageFrom($parent);
		}
		return($id);
	}
	
	
	
	function checkzone($zone,$type,$parent){
		if ($zone>=1 && $type!="page" && $type!="illustration" && $type!="titre" && $type!="picflux" && $type!="traduction_page" && $type!="traduction_texte" && $type!="traduction_categorie" && $type!="traduction_titre"){
			$restypepage=mysql_query("SELECT * FROM billet WHERE id=$parent");
			$rowtypepage = mysql_fetch_array($restypepage);
			switch($rowtypepage['zone']){
				case "1b":
					$zone="ico_page_1b.gif";
					$alt="aucune zone";
					break;						
				case "1b2m":
					switch($zone){
						case 1:
							$zone="ico_page_1bset2m.gif";
							$alt="zone 1/2";
							break;
						case 2:
							$zone="ico_page_1b2mset.gif";
							$alt="zone 2/2";
							break;
					}
					break;	
				case "1m2b":
					switch($zone){
						case 1:
							$zone="ico_page_1mset2b.gif";
							$alt="zone 1/2";
							break;
						case 2:
							$zone="ico_page_1m2bset.gif";
							$alt="zone 2/2";
							break;
					}
					break;		
				case "1m2m3m":
					switch($zone){
						case 1:
							$zone="ico_page_1mset2m3m.gif";
							$alt="zone 1/3";
							break;
						case 2:
							$zone="ico_page_1m2mset3m.gif";
							$alt="zone 2/3";
							break;
						case 3:
							$zone="ico_page_1m2m3mset.gif";
							$alt="zone 3/3";
							break;
					}
					break;
			}
			$zone="<IMG SRC='".SKIN."/images/billet/$zone' alt=\"$alt\" title=\"$alt\"> ";
		}else{
			$zone="";
		}
		return($zone);
	}
	
	
	function billet_icone($type,$option,$zone){
		switch($type){
			case "categorie":
				$pic="icone_categorie.gif";
				$alt="categorie";
				break;
			
			case "page":
				switch($zone){					
					case "1b":
						$pic="icone_page_1b.gif";
						$alt="page 1 zone";	
						break;
					case "1b2m":
						$pic="icone_page_1b2m.gif";
						$alt="page 2 zones";	
						break;
					case "1m2b":
						$pic="icone_page_1m2b.gif";
						$alt="page 2 zones";	
						break;
					case "1m2m3m":
						$pic="icone_page_1m2m3m.gif";
						$alt="page 3 zones";	
						break;				
				}						
				break;			
				
			case "gallerie":
				$pic="ico_gallerie.gif";
				$alt="gallerie";
				break;
				
			case "texte":
				switch($option){					
					case "justifie":
						$pic="ico_texte_alignjust.gif";
						$alt="texte justifié";	
						break;
					case "left":
						$pic="ico_texte_alignleft.gif";
						$alt="texte fer à gauche";	
						break;
					case "right":
						$pic="ico_texte_alignright.gif";
						$alt="texte fer à droite";	
						break;
					case "centre":
						$pic="ico_texte_aligncenter.gif";
						$alt="texte centré";	
						break;				
				}				
				break;	
			
			case "image":
				switch($option){					
					case "align_haut_gauche":
						$pic="ico_texte_image_topleft.gif";
						$alt="image haut gauche";
						break;
					case "align_haut_centre":
						$pic="ico_texte_image_topcenter.gif";
						$alt="image haut centré";
						break;
					case "align_haut_droite":
						$pic="ico_texte_image_topright.gif";
						$alt="image haut droite";
						break;
					case "align_bas_gauche":
						$pic="ico_texte_image_botomleft.gif";
						$alt="image bas gauche";
						break;
					case "align_bas_centre":
						$pic="ico_texte_image_botomcenter.gif";
						$alt="image bas centré";
						break;
					case "align_bas_droite":
						$pic="ico_texte_image_botomright.gif";
						$alt="image bas droite";
						break;						
				}
				break;
				
			case "traduction_categorie":
				$pic="ico_traduction.png";
				$alt="html";
				break;
			case "traduction_page":
				$pic="ico_traduction.png";
				$alt="html";
				break;
			case "traduction_texte":
				$pic="ico_traduction.png";
				$alt="html";
				break;
			case "traduction_titre":
				$pic="ico_traduction.png";
				$alt="html";
				break;
				
				
				
			case "html":
				$pic="ico_html.gif";
				$alt="html";
				break;
				
			case "rss":
				$pic="ico_rss.gif";
				$alt="Flux Rss";
				break;
				
			case "titre":
				$pic="ico_texte_titre.gif";
				$alt="titre du texte";
				break;
				
			case "video":
				$pic="ico_video.gif";
				$alt="video";
				break;
			case "audio":
				$pic="ico_audio.gif";
				$alt="audio";
				break;
			case "imagepage":
				$pic="ico_image.gif";
				$alt="image";
				break;

			case "link":
				$pic="ico_link.gif";
				$alt="lien";
				break;
												
			case "date":
				switch($option){					
					case "start":
						$pic="icone_date.gif";
						$alt="date";
						break;						
					case "limite":
						$pic="icone_date_limite.gif";
						$alt="date avec limite";
						break;			
					default:
						$pic="icone_date.gif";
						$alt="date";
						break;
				}					
				break;
				
			case "file":
				$pic="ico_file.gif";
				$alt="fichier ".$option;								
				break;				
			
			default:
				$noimage="";
				$type="";
				$alt="";
				break;
		}
		
		
		if (isset($noimage)){
			$image="";
		}else{
			$image="<IMG SRC=\"".SKIN."/images/billet/$pic\" border=\"0\" alt=\"$alt\" title=\"$alt\">";
		}
		return($image);
	}
	

	function legende(){		
		$buffer="";
		
		$legende_nom=array('option','categorie','zone','page','paragraphe');
		//------------------------------------------
		// OPTIONS
		//------------------------------------------
		$legende['option']['image']=array(	
			'ico_traduction.png',
			'ico_ordre_up.png',
			'ico_ordre_down.png',
			'../viewoff.png',
			'../viewon.png',
			'../premierepage_on.gif',
			'../premierepage_off.gif'
		);
		
		$legende['option']['texte']=array(	
			'Traduction',
			'monter un element dans la zone courante',
			'descendre un element dans la zone courante',
			'categorie/page non visible',
			'categorie/page visible',
			'categorie/page mise en premiére page',
			'categorie/page pas mis en premiére page',
		);
		//------------------------------------------
		// CATEGORIE
		//------------------------------------------
		$legende['categorie']['image']=array(	
			'icone_categorie.gif'
		);
		
		$legende['categorie']['texte']=array(	
			'categorie'
		);
		
		//------------------------------------------
		// ZONE
		//------------------------------------------
		$legende['zone']['image']=array(			
			'icone_page_1b.gif',
			'ico_page_1b.gif',
			
			'icone_page_1b2m.gif',
			'ico_page_1bset2m.gif',
			'ico_page_1b2mset.gif',
			
			'icone_page_1m2b.gif',
			'ico_page_1mset2b.gif',
			'ico_page_1m2bset.gif',	
			
			'icone_page_1m2m3m.gif',			
			'ico_page_1mset2m3m.gif',
			'ico_page_1m2mset3m.gif',
			'ico_page_1m2m3mset.gif'
		);
		
		$legende['zone']['texte']=array(
			'1 zone',
			'zone 1/1',
				
			'2 zone',	
			'zone 1/2',
			'zone 2/2',
			
			'2 zone',	
			'zone 1/2',
			'zone 2/2',	
			
			'3 zone',				
			'zone 1/3',
			'zone 2/3',
			'zone 3/3'
		);
		//------------------------------------------
		// PAGE
		//------------------------------------------
		$legende['page']['image']=array(	
			'ico_file.gif',
			'ico_gallerie.gif',
			'ico_link.gif',
			'ico_image.gif',
			'ico_rss.gif',
			'ico_html.gif',
			'ico_video.gif',			
			'ico_audio.gif',
			'icone_date.gif',	
			'icone_date_limite.gif',
			'ico_texte_alignleft.gif',
			'ico_texte_aligncenter.gif',
			'ico_texte_alignright.gif',
			'ico_texte_alignjust.gif'
		);
		
		$legende['page']['texte']=array(	
			'Lien sur un fichier',
			'Galerie',
			'Lien',
			'Image',
			'Flux Rss',
			'Html',
			'Video',			
			'Audio',
			'Date de parution décallée',	
			'Date de validitée',
			'Paragraphe, aligné à gauche',
			'Paragraphe, centré',
			'Paragraphe, aligné à droite',
			'Paragraphe, justifié'
		);
		
		//------------------------------------------
		// PARAGRAPHE
		//------------------------------------------
		$legende['paragraphe']['image']=array(	
			'ico_texte_titre.gif',
			'ico_texte_image_topleft.gif',
			'ico_texte_image_topcenter.gif',
			'ico_texte_image_topright.gif'
		);
		
		$legende['paragraphe']['texte']=array(	
			'Titre du pragraphe',
			'Image alignée à gauche dans le paragraphe',
			'Image centrée dans le paragraphe',
			'Image aligné à droite dans le paragraphe'
		);
		
		$buffer.="<table align=\"center\"><tr>";
		for($YY=0;$YY<count($legende_nom);$YY++){
			$nomL=$legende_nom[$YY];
			$buffer.="<td align=\"center\"><fieldset><legend>$nomL</legend>";
			for($XX=0;$XX<count($legende[$nomL]['image']);$XX++){
				$image = $legende[$nomL]['image'][$XX];
				$alt = $legende[$nomL]['texte'][$XX];
				$buffer.= "<IMG SRC='".SKIN."/images/billet/$image' alt=\"$alt\" title=\"$alt\" align=\"absmiddle\"> ";
			}
			$buffer.="</fieldset></td>";
			
		}
		$buffer.="</tr></table>";
		return($buffer);
	}
}
?>	