<?
/* =================================================================================================
 * Funkylab V2.05
 * Par Cyril Pereira
 * cyril.pereira@gmail.com
 * =================================================================================================
 *	Mentions légales
 *  Funkylab est entiérement réalisé en Php/Sql & Javascript
 *  Personne n'a le droit d'utiliser quelque bout que se soit de ce CMS sans l'accord préalable de
 *  Cyril Pereira.
 * ==================================================================================================
 * ==================================================================================================
 * 	DISPLAY TEMPLATE (Parse le template et affiche à l'endroit des balises le contenu)
 * ==================================================================================================
 *		Il faut obligatoirement utiliser les repertoires
 * 		"images" et "css" qui se trouvent dans le repertoire du template
 * 		OPTIONS :
 * 			#CALENDRIER#	: Calendrier des billets
 * 			#PUB#			: Bandeau de pub
 * 			#MENUX# 		: X = ID du menu
 * 			#CONTENU#		: Resultat des manipulations sur le site
 * 			#MENTION#		: Information concernant la création de la page (temps de calcul)
 * 			#META#			: Meta tags
 * 			#NEWSLETTER#	: Inscription à la newsletter
 * 			#RECHERCHE# 	: Moteur de recherche
 * 			#SOUSCATEGORIE#	: Affichage des sous categories et des sous galeries
 *			#LANGUE#		: MenuList pour choisir la langue du site
 * 			#BILLETX#		: ID du billet (categeorie, page, galerie)
//=================================================================================================*/
	
//==================================================================================================
//	HEADER (Permet de chargement forcé de nouvelle page)
//==================================================================================================

	header("Content-type: text/html"); 	
	header("Content-Disposition: filename=index.php\r\n\r\n"); 
	header("Expires: Mon, 26 Jul 1997 05:00:00 GMT");             
	header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT"); 
	header("Cache-Control: no-cache, must-revalidate"); 
	header("Pragma: no-cache"); 
	
	session_start();
	
	$timedepart= get_micro_time();
	function get_micro_time(){
		list($usec, $sec)=explode(" ",microtime());
		return ((float)$usec+(float)$sec);
	}		
	
//==================================================================================================
//	LECTURE DE LA CONFIGURATION ETABLIE PAR L'ADMIN
//==================================================================================================
	$rsspdf="";
	include "admin/config/config.bdd.url.php";
	include "admin/config/root.config.php"; 
	include "admin/include/globale.inc.php"; 
	include "includes/class.menu.php"; 
	include "includes/class.billet.php"; 
	include "includes/class.calendrier.php"; 
	include "includes/class.finder.php"; 
	include "includes/class.comment.php"; 
	$buffer_template_HTML=file_get_contents(CURRENT_SKIN."/template.html");
 	$buffer_template_HTML=ereg_replace("images/",CURRENT_SKIN."/images/",$buffer_template_HTML);
	$buffer_template_HTML=ereg_replace("css/",CURRENT_SKIN."/css/",$buffer_template_HTML);
	$tableglobal="";
	
	if (isset($_GET['new_langue'])){
		$_SESSION['langue']=$_GET['new_langue'];
	}
	
	if (!isset($_SESSION['langue'])){
		$serverLANGUAGE=$_SERVER['HTTP_ACCEPT_LANGUAGE'];
		$serverLANGUAGE=sendNameLangue($serverLANGUAGE);		
		$_SESSION['langue']=$serverLANGUAGE['langue'];
		$_SESSION['pays']=$serverLANGUAGE['pays'];
				
	}
	
	$langueFORM="";
	
		
			
	$langueFORM.="<form action=\"index.php\" method=\"GET\">";
	$langueFORM.=listlangue($_SESSION['langue']);
	if ($_SERVER['QUERY_STRING']){
		$varS=explode("&",$_SERVER['QUERY_STRING']);	
		foreach($varS as $var){
			$var=explode("=",$var);
			if ($var[0]!="new_langue"){
				if (count($var)>1){
					$langueFORM.="\n<input type=\"hidden\" name=\"".$var[0]."\" value=\"".$var[1]."\">\n";
				}else{
					$langueFORM.="<input type=\"hidden\" name=\"".$var[0]."\" >\n";
				}
			}
		}
	}	
	$langueFORM.="</form>";
	
	
//==================================================================================================
//	OPERATION AVANT LECTURE DE LA PAGE
//==================================================================================================
	//==================================================================================================
	//	AJOUT DE COMMENTAIRE
	//==================================================================================================
		
		if (isset($_GET['newcomment'])){
				$tableglobal.= comment::checkcomment($_GET['add_pseudo'],$_GET['add_mail'],$_GET['add_commentaire'],$_GET['billet']);		
		}	
	
//==================================================================================================
//	MENU (Si un ou plusieurs #nom# de menu est présent dans le template il est affiché)
//==================================================================================================

		$res=mysql_query("SELECT nom,id FROM menu WHERE parent=0 ORDER BY id");			
		$nb=array();		
		$menuHTML="";	
		while ($row = mysql_fetch_array($res)){ 
			if (strstr ($buffer_template_HTML,stripslashes($row['nom']))){				
				$menuHTML.=Generate_Menu::makemenuhtml($row['id']);	
			 	$menuHTML.=Generate_Menu::javascriptmenu($row['id']);
			 	$buffer_template_HTML=ereg_replace(stripslashes($row['nom']),$menuHTML,$buffer_template_HTML);				
	 		}
		}	
			 
//==================================================================================================
//	CONTENU (Si une variable de type cat, page, galerie est invoqué elle serat affiché ici)
//==================================================================================================


	$QUERY_STRING=$_SERVER['QUERY_STRING'];
	
	if ((isset($_GET['billet'])) || ($QUERY_STRING=="") ||  (isset($_GET['new_langue'])) || (isset($_GET['month']))){	
		
	//==================================================================================================
	//	LISTE DES SOUS CATEGORIES
	//==================================================================================================
		$langue=$_SESSION['langue'];	
		$souscat="";
		if (isset($_GET['billet'])){			
			$testcount=0;
			if (isset($_GET['menuset'])){
				$urlmenu="&menuset=".$_GET['menuset'];
			}else{
				$urlmenu="";
			}
			
			$reselement=mysql_query("SELECT * FROM billet WHERE type='categorie' AND parent='".$_GET['billet']."' AND visible='1'");
			while ($rowelement = mysql_fetch_array($reselement)){		 
				$idSOUSCAT=$rowelement['id'];
				$nomSOUSCAT=stripslashes($rowelement['nom']);
				$query="SELECT nom FROM billet WHERE parent='$idSOUSCAT' AND option_element='$langue'";
				$resTITRE=mysql_query($query);
				while ($rowTITRE = mysql_fetch_array($resTITRE)){
					$nomSOUSCAT=stripslashes($rowTITRE['nom']);
				}
				
				$souscat.="<a href=\"index.php?billet=".$rowelement['id']."$urlmenu\">". $nomSOUSCAT."</a> - ";
				$testcount++;
			}
			if ($testcount>0){
				
				$souscat="
				<table border=\"0\" id=\"souscat\">
					<tr>
						<td>
								".substr($souscat,0,strlen($souscat)-2)."
						</td>
					</tr>
				</table>";
			}
		}
		
		//==================================================================================================
		//	AFFICHAGE BILLET
		//==================================================================================================

		if (isset($_GET['billet'])){			
			$pageid=$_GET['billet'];
			
			$querybillet="SELECT * FROM billet WHERE id='$pageid' AND visible='1'";	
			
		}else{
			
		//==================================================================================================
		//	AFFICHAGE DES BILLET EN DATE DU JOUR SELECTIONNE
		//==================================================================================================

			if (isset($_GET['day'])){
				$date_debut=$_GET['year']."-".$_GET['month']."-".$_GET['day'];
				$querybillet="SELECT * FROM billet WHERE date_debut = '$date_debut' AND type='page' AND visible='1'";			
				
			}else{
				
		//==================================================================================================
		//	AFFICHAGE DES BILLET EN DATE DU MOIS SELECTIONNE
		//==================================================================================================

				if (isset($_GET['month'])){
					$date_debut=$_GET['year']."-".$_GET['month']."-01";
					$jourdanslemois=date("t", mktime (0,0,0,$_GET['month'],1,$_GET['year']));						
					$date_fin=$_GET['year']."-".$_GET['month']."-".$jourdanslemois;				
					$querybillet="SELECT * FROM billet WHERE date_debut >= '$date_debut' AND date_debut <= '$date_fin' AND type='page' AND visible='1'";							
				}else{
					
			//==================================================================================================
			//	AFFICHAGE DU BILLET OU DE LA CATEGORIE CHOISIS POUR LA PREMIERE PAGE
			//==================================================================================================
	
					$resZERO=mysql_query("SELECT * FROM billet WHERE premierepage='1'");		
					$rowZERO = mysql_fetch_array($resZERO);	
					$pageid=$rowZERO['id'];				
					$querybillet="SELECT * FROM billet WHERE id='$pageid' AND visible='1'";	
				}
			}
		}

		//==================================================================================================
		//	CREATION DU BILLET
		//==================================================================================================

		$restypepage=mysql_query($querybillet);
			
		while ($rowtypepage = mysql_fetch_array($restypepage)){
			
			$type=$rowtypepage['type'];	
			$option=$rowtypepage['option_element'];	
			$pageid=$rowtypepage['id'];
			
			switch($type){
				case "page":
					if (!isset($_GET['month'])){
						$rsspdf.="<a href=\"pdf.php?billet=$pageid\" target=\"_blank\"><img src=\"".CURRENT_SKIN."/images/pdf.gif\" border=\"0\" alt=\"telecharger la version PDF de cette page\"></a>";
					}
					$page=Generate_Contenu::createpage($pageid,null);	
					$tableglobal.=$page;
					break;
					
				case "categorie":	
					$page="";
					$option=explode(",",$option);
					if ($option[0]=="croissant"){
						$DESC="";
					}else{
						$DESC="DESC";
					}
					
					$LIMIT="LIMIT ".$option[2];
					
					$resultpage=mysql_query("SELECT * FROM billet WHERE parent='$pageid' AND type='page' AND visible='1' ORDER BY id $DESC $LIMIT");			
					while ($row = mysql_fetch_array($resultpage)){ 
						$page.=Generate_Contenu::createpage($row['id'],$option[1]);						
					}
					$tableglobal.=$page;
					break;	
					
				default:
					break;	
			}
		}
	}	
		 
		 
	//==================================================================================================
	//	CHEMIN DE FER (Si une variable de type cat, page, galerie est invoqué elle serat affiché ici)
	//	LIE AVEC LE CONTENU TYPE BILLET
	//==================================================================================================
	
		if ((isset($_GET['billet'])) || ($QUERY_STRING=="") || (isset($_GET['new_langue']))){	
			if (isset($_GET['billet'])){
				$pageid=$_GET['billet'];
			}else{
				$resZERO=mysql_query("SELECT * FROM billet WHERE premierepage='1'");		
				$rowZERO = mysql_fetch_array($resZERO);	
				$pageid=$rowZERO['id'];
			}
			$cheminfer=cheminfer("",$pageid);
		}else{
			$cheminfer="";
		}	
		
		function cheminfer($cheminfer,$id){
			$langue=$_SESSION['langue'];
			$resFER=mysql_query("SELECT nom,id,parent FROM billet WHERE id='$id'");	
			$rowFER = mysql_fetch_array($resFER);
			$nomFER=strtoupper(stripslashes($rowFER['nom']));
			$idFER=stripslashes($rowFER['id']);
			
			if (isset($_GET['menuset'])){
				$urlmenu="&menuset=".$_GET['menuset'];
			}else{
				$urlmenu="";
			}
			
			$query="SELECT nom FROM billet WHERE parent='$idFER' AND option_element='$langue'";
			$resTITRE=mysql_query($query);
			while ($rowTITRE = mysql_fetch_array($resTITRE)){
				$nomFER=stripslashes($rowTITRE['nom']);
			}
			
			if ($rowFER['parent']==0){
				$cheminfer.="<a href='index.php?billet=$idFER".$urlmenu."'>$nomFER</a> ";					
			}else{
				$cheminfer.=cheminfer($cheminfer,$rowFER['parent'])." > <a href='index.php?billet=$idFER".$urlmenu."'>$nomFER</a>";	
			}			
			return($cheminfer);	
		}	
		
//==================================================================================================
//	GALERIE
//==================================================================================================
	
	if (isset($_GET['galerie'])){
		$page="";
		$galerieid=0;
		$titregalerie="GALERIE";
		$galeriepardefaut=6;
		
		if ($_GET['galerie']>=1){
			$galerieid=$_GET['galerie'];
			$resGAL=mysql_query("SELECT * FROM type_photo WHERE id='$galerieid'");	
			$rowGALERIE = mysql_fetch_array($resGAL);		
			$titregalerie=strtoupper(stripslashes($rowGALERIE['type']));
			$rowGALERIE=array(
				"id"=>$galerieid,
				"texte"=>$galerieid,
				"nom"=>'',
				"option_element"=>"$galeriepardefaut,galerie,detail"
			);	
			
			if (isset($_GET['file'])){				
				$galerieid=$_GET['galerie'];
				$page=Generate_Contenu::display_file($_GET['file']);
			}else{
				$page=Generate_Contenu::displaygalerie($rowGALERIE);					
				$galerie_txt=sous_galerie("",$galerieid);
			}
		}else{
			$rowGALERIE=array(
				"id"=>0,
				"texte"=>0,
				"nom"=>'',
				"option_element"=>"$galeriepardefaut,galerie,detail"
			);	
			$page=Generate_Contenu::displaygalerie($rowGALERIE);
		}
		$souscat=sous_galerie("",$galerieid);	
		$cheminfer=cheminfer_GALERIE("",$galerieid);
		$tableglobal=Generate_Contenu::page1b();
		
		$tableglobal=ereg_replace("#TITRE#","<a href='index.php?galerie=$galerieid'>".$titregalerie."</a>",$tableglobal);
		$tableglobal=ereg_replace("#CONTENU1#",$page,$tableglobal);
		$tableglobal=ereg_replace("#INFO#","",$tableglobal);
		
		$tableglobal="
			<table id='tablecontenu' cellspacing='5'>
				<tr>
					<td align='center' valign='top'>
						<div id='contenu'>$tableglobal</div>
					</td>
				</tr>
			</table>
		";
		$cheminfer="<a href='index.php?galerie'>GALERIES</a> > ".$cheminfer;
	}	
		
	//==================================================================================================
	//	LISTE DES SOUS GALERIE
	//==================================================================================================
	
		function sous_galerie($galerie_txt,$id){
			$resGAL=mysql_query("SELECT * FROM type_photo WHERE parent='$id'");	
				$count=0;
				$txt="";
				$galerie_txt="";
				while ($rowGAL = mysql_fetch_array($resGAL)){ 	
					$typeGAL=strtoupper(stripslashes($rowGAL['type']));
					$txt.="<a href='index.php?galerie=".$rowGAL['id']."'>$typeGAL</a>&nbsp;|&nbsp;";
					$count++;
				}
				if ($count>=1){
					$galerie_txt.="
					<table border=\"0\" id=\"souscat\">
						<tr>
							<td>$txt</td>
						</tr>
					</table>";
				}
				$galerie_txt.=$rowGAL['commentaire'];
			return($galerie_txt);	
		}
		
			 
	//==================================================================================================
	//	CHEMIN DE FER LIE AVEC LA GALERIE
	//==================================================================================================
	
		function cheminfer_GALERIE($cheminfer,$id){
			$resFER=mysql_query("SELECT type,id,parent FROM type_photo WHERE id='$id'");	
			$rowFER = mysql_fetch_array($resFER);
			$nomFER=strtoupper(stripslashes($rowFER['type']));
			$idFER=$rowFER['id'];
			if ($rowFER['parent']==0){
				$cheminfer.="<a href='index.php?galerie=$idFER'>$nomFER</a> ";					
			}else{
				$cheminfer.=cheminfer_GALERIE($cheminfer,$rowFER['parent'])." > <a href='index.php?galerie=$idFER'>$nomFER</a>";	
			}			
			return($cheminfer);	
		}
	
//==================================================================================================
//	AFFICHE LE CALENDRIER
//==================================================================================================
	if (isset($_GET['day'])){	
		$day=$_GET['day'];
	}else{
		$day=0;
	}
	
	if (isset($_GET['calendrier'])){	
		
		$month=$_GET['month'];
		$year=$_GET['year'];
		
		$calendrier=calendrier::display($_GET['month'],$_GET['year'],$day);
		
		if ($day!=0){
			$daytxt="&day=$day";
		}else{
			$daytxt="";
		}
		
		$dateview=decodedate("$year-$month-$day");
		
		$cheminfer="
			<a href='index.php?calendrier&month=$month&year=$year".$daytxt."'>
				$dateview
			</a>
		";
		
	}else{	
		$calendrier=calendrier::display(date("m"),date("Y"),$day);	
	}
	
	
//==================================================================================================
//	META TAGS
//==================================================================================================

	$METATAGS="	
		<TITLE>$nomsite</TITLE>
		<meta name=\"title\" content=\"$nomsite\"/>
		<meta name=\"description\" content=\"'\"/>
		<meta name=\"abstrac\" content=\"$nomsite\"/>
		<meta name=\"keywords\" content=\"$nomsite\"/>
		<meta name=\"identifier-url\" content=\"http://".$_SERVER['SERVER_NAME']."\"/>
		<meta name=\"category\" content=\"freelance\"/>
		<meta name=\"rating\" content=\"general\"/>
		<meta http-equiv=\"content-language\" content=\"fr\"/>
		<meta name=\"language\" content=\"fr\"/>
		<meta name=\"author\" content=\"Cyril Pereira\"/>
		<meta name=\"copyright\" content=\"Cyril Pereira\"/>
		<meta name=\"reply-to\" content=\"cyril.pereira@gmail.com\"/>
		<meta name=\"geography\" content=\"Bondy,France,93140\"/>
		<meta name=\"date-creation-yyyymmdd\" content=\"20060120\"/>
		<meta name=\"date-revision-yyyymmdd\" content=\"20060120\"/>
		<meta name=\"expires\" content=\"never\"/>
		<meta name=\"revisit-after\" content=\"15 days\"/>
		<meta name=\"robots\" content=\"all\"/>
		<link rel=\"stylesheet\" type=\"text/css\" href=\"".CURRENT_SKIN."/css/theme.css\">
		<link rel=\"stylesheet\" type=\"text/css\" href=\"".CURRENT_SKIN."/css/transmenuv.css\">
		<link rel=\"alternate\" type=\"application/rss+xml\" title=\"$nomsite\" href=\"rss.php\"/>
		<script language=\"javascript\" src=\"scripts/transmenu.js\"></script>	
		<script language=\"JavaScript1.2\">
			function open_newpopup(bUrl, bName, bWidth, bHeight, bResize){
				var lar=screen.width/2;
				var hau=screen.height/2;
				var lo=lar-bWidth/2;
				var ho=hau-bHeight/2;
				var newFenetre = window.open(bUrl,bName,'directories=no,location=no,toolbar=no,directories=no,menubar=no,resizable='+bResize+',scrollbars=no,status=no,top='+ho+',left='+lo+',width='+bWidth+',height='+bHeight);
				if (navigator.appVersion.indexOf(\"MSIE 4.0\") < 0) newFenetre.focus();
			}		
		</script>	
	";	
//==================================================================================================
//	NEWSLETTER
//==================================================================================================
	$newsletter="
	<form action\"index.php\" method=\"GET\">
		<fieldset>
			<legend>NEWSLETTER</legend>
			<p>Pour vous abonner ou vous désabonner de notre newsletter, entrez votre email ci-dessous et cliquez sur 'ok'</p>
			<input name=\"newsletter\" /><input type=\"submit\" value=\"ok\" />
		</fieldset>
	</form>";

	
	if (isset($_GET['newsletter'])){
		$email=$_GET['newsletter'];
		$tableglobal="
		<table border=0 id=\"botomform\" width=\"820\">
			<tr>
				<td align=\"center\">
					<fieldset>
						<legend>NEWSLETTER</legend>
						";
						if (verifmail($email)==true){
							$mail=$_GET['newsletter'];
							$IPcreation=$_SERVER['REMOTE_ADDR'];
							$date=codeladate();	
							if (checkifexiste($mail)==true){									
								$res=mysql_query("DELETE FROM mail_newsletter WHERE mail='$mail'");
								$tableglobal.=
								"Vous venez de vous dé-inscrire de notre newsletter.";
							}else{
								$query="INSERT INTO mail_newsletter (id,mail,date,IPcreation) VALUES ('','$mail','$date','$IPcreation')";	
								$resultat=mysql_query($query);	
								$tableglobal.=
								"
									<P>Vous venez de vous inscrire à notre newsletter.</P>
									<P>Si vous desirez vous desinscrire entrez à nouveau votre email dans la zone newsletter à gauche.</P>
								";
							}
						}else{
							$tableglobal.=
							"
								<P>Votre email semble invalide.</P>
								<P>Essayez de le saisir à nouveau.</P>
							";
						}
						$tableglobal.="
					</fieldset>
				</td>
			</tr>
		</table>";
	}
	
	

	
//==================================================================================================
//	MOTEUR DE RECHERCHE
//==================================================================================================
	$finder="<form action=\"index.php\" method=\"GET\" id=\"searchform\"><input name=\"search\" class=\"zoneinput\"/><input type=\"submit\" value=\"Recherche\" class=\"btn\"/></form>";
	$buffer_template_HTML=ereg_replace("#RECHERCHE#",$finder,$buffer_template_HTML);
	
	
	if (isset($_GET['search'])){		
		$souscat="Votre recherche sur : ".$_GET['search'];
		$tableglobal=finder::search($_GET['search']);
	}
	
	
	
//==================================================================================================
//	RSS & PDF
//==================================================================================================
	
	$rsspdf.="
	<a href=\"rss.php\" target=\"_blank\"><img src=\"".CURRENT_SKIN."/images/rss.gif\" border=\"0\" alt=\"tenez vous au courant avec le fils RSS\"></a>	
	";

	$buffer_template_HTML=ereg_replace("#RSS#",$rsspdf,$buffer_template_HTML);	
//==================================================================================================
//	AFFICHE LE BANDEAU PUB
//==================================================================================================

	$resPUB=@mysql_query("SELECT id,url,image FROM bannieres ORDER BY RAND() LIMIT 1");
	$pub="";
	while ($rowPUB = mysql_fetch_array($resPUB)){ 
		$imagePUB=$rowPUB['image'];
		$urlPUB=$rowPUB['url'];
		$idPUB=$rowPUB['id'];	
		$pub="<a href=\"index.php?idpub=$idPUB&bdpub=$urlPUB\" target=\"blank\"><img src=\"$imagePUB\" border=0></a>";		
	}
	$buffer_template_HTML=ereg_replace("#PUB#",$pub,$buffer_template_HTML);
	
	if (isset($_GET['bdpub'])){			
		$url2go=$_GET['bdpub'];	
		$idPUB=$_GET['idpub'];		
		$res=mysql_query("SELECT * FROM bannieres WHERE id='$idPUB'");		
		$row = mysql_fetch_array($res);		
		$clic=$row['nbclic']+1;   		
		$query="UPDATE bannieres SET  nbclic='$clic' WHERE id='$idPUB'";
		$result=mysql_query($query);		
		$METATAGS.="<meta http-equiv=\"refresh\" content=\"0;url=$url2go\">";
	}
	
//==================================================================================================
//	BILLET X (Si un ou plusieurs #BILLETX# est present meme dans les paragraphes alors il affiche le contenu)
//==================================================================================================
	
	$res=mysql_query("SELECT nom,id FROM billet WHERE type='categorie' OR type='page' ORDER BY id");			
	$nb=array();	
	while ($row = mysql_fetch_array($res)){ 
		$id=$row['id'];
		$billet="#BILLET".$id."#";
		if (strstr ($buffer_template_HTML,$billet)){	
		 	$page=Generate_Contenu::createpage($id,null);
		 	$buffer_template_HTML=ereg_replace($billet,$page,$buffer_template_HTML);				
 		}
	}
		
//==================================================================================================
//	AFFICHAGE
//==================================================================================================

	if (!isset($souscat)){$souscat="";}	
	$buffer_template_HTML=ereg_replace("#CONTENU#",$tableglobal,$buffer_template_HTML);		
	$buffer_template_HTML=ereg_replace("#SOUSCATEGORIE#",$souscat,$buffer_template_HTML);
	$buffer_template_HTML=ereg_replace("#CALENDRIER#",$calendrier,$buffer_template_HTML);	
	$buffer_template_HTML=ereg_replace("#NEWSLETTER#",$newsletter,$buffer_template_HTML);
	$buffer_template_HTML=ereg_replace("#META#",$METATAGS,$buffer_template_HTML);
	$buffer_template_HTML=ereg_replace("#CHEMINDEFER#",$cheminfer,$buffer_template_HTML);
	$buffer_template_HTML=ereg_replace("#LANGUE#",$langueFORM,$buffer_template_HTML);

		

//==================================================================================================
//	INFO EN BAS DE PAGE
//==================================================================================================
	
	$timearrive= get_micro_time();
	$temps=$timearrive-$timedepart;
	$temps=number_format($timearrive-$timedepart,2);
	$mention="<a href=\"http://add.my.yahoo.com/content?url=".ROOT."rss.php&lg=fr\"><img src=\"http://eur.i1.yimg.com/eur.yimg.com/i/fr/my/atml.gif\" alt=\"ajouter &agrave; mon yahoo!\" width=98 height=17 border=0 align=bottom></a>&nbsp;<a href=\"http://fr.my.msn.com/addtomymsn.armx?id=rss&ut=".ROOT."rss.php\"><img src=\"http://www.motamot.com/images/rss_msn.gif\" alt=\"ajouter &agrave; my msn\" width=91 height=17 border=0 align=bottom></a>&nbsp;<a href=\"http://www.newsgator.com/ngs/subscriber/subext.aspx?url=".ROOT."rss.php\"><img  src=\"http://www.motamot.com/images/rss_nwsgator.gif\" alt=\"ajouter &agrave; newsgator\" width=91 height=17 border=0align=bottom></a>&nbsp;<a href=\"http://fusion.google.com/add?feedurl=".ROOT."rss.php\"><img src=\"http://www.motamot.com/images/rss_google.gif\" alt=\"ajouter &agrave; mon google\" width=104 height=17 border=0align=bottom></a><br/>Propulsé avec <a href='http://www.funkylab.info'>Funkylab</a> en ".$temps." sec, optimisé pour <a href='http://www.mozilla-europe.org/fr/products/firefox/' target='blank'><img src='admin/".SKIN."/images/firefox.gif' border=0 align='absmiddle'></a>";
         	
	$buffer_template_HTML=ereg_replace("#MENTION#",$mention,$buffer_template_HTML);	
	
//==================================================================================================
//	AFFICHAGE DE LA PAGE
//==================================================================================================
	
	if ($online=="on"){
		print $buffer_template_HTML;
	}
	
?>